<?
class Service extends CRUD{
	protected $modelClassName = 'ServiceModel';
	/**
	 * Подключение услуги.
	 * Если услуга периодическая, то добавление в реестр подписок.
	 * 1 числа списываем абонентку за весь пакет всех подключенных услуг.
	 * Сейчас снимаем за подключение сервиса его полную стоимость и  вычисляем,
	 * сколько дней уже прошло с начала месяца
	 * (в течение которых пользователь услугу не использовал),
	 * стоимость сервиса за эти дни запоминаем как сумму к уменьшению ближайшей абонентки.
	 * Таким образом компенсируем юзеру неиспользованные в этом месяце дни,
	 * и заодно не даем ему отказаться от только что подключенной услуги.
	 * (иначе бы юзеры могли подключать какую нибудь услугу 30 числа и отключать 31,
	 * а в нашем случае при таком сценарии они теряют стоимость месяца)
	 * @param $userId
	 * @param $serviceId
	 *
	 * @return int
	 * @throws Exception
	 */
	function buy($userId, $serviceId) {
		$service = (new ServiceModel)->load($serviceId);
		$user    = (new CjclubUser)->load($userId);
		if ($service->price > $user->balance) {
			throw new Exception('Недостаточно средств для покупки');
		}
		if ($service->type == 'once') {
			/*
			 * разовая покупка
			 */

		} else {
			/*
			 * абонентка
			 */
			/*
			 * запомнить, сколько юзеру скинуть на стоимость подписки сервиса ближайшего 1 числа
			 * (так как он заплатил сейчас за весь месяц, но не использует его)
			 */
			$servicePricePerDay = $service->price / 31;
			$daysPassed = date('d');
			$toRebate = ($daysPassed - 1) * $servicePricePerDay;
			dbReplace('users_subscribe_services', ['userId' => $userId, 'serviceId' => $serviceId, 'toRebate' => $toRebate, 'active' => 'yes']);
		}
		$user->balance -= $service->price;
		$user->save();
		/*
		 * пишем лог покупок
		 */
		$log = (new PaymentsLogModel);
		$log->userId = $userId;
		$log->date = new SQLvar('NOW()');
		$log->comment = sprintf('Подключение сервиса %s', $service->name);
		$log->sum = $service->price;
		$log->create();
		return $user->balance;
	}
	/**
	 * Вычисляет и списывает ежемесячную абонентскую плату по подключенным сервисам
	 * @param $userId
	 */
	function chargeMonthlyPayment($userId) {
		$user = (new CjclubUser)->load($userId);
		/*
		 * получить список сервисов пользователя
		 */
		$servicesSubscribedTo = dbSelect('users_subscribe_services', 'serviceId, toRebate', "userId = $userId AND active = 'yes' AND paidTill < NOW()", DB_SELECT_OBJS);
		if (!$servicesSubscribedTo || (count($servicesSubscribedTo) == 0)) return;
		$servicesIds = array_keys($servicesSubscribedTo);
		$servicesIds = implode(',', $servicesIds);
		$toRebate = array_sum($servicesSubscribedTo);
		$services = (new DModelsCollection('ServiceModel'))->load("id IN ($servicesIds)");
		$sum = 0;
		foreach ($services as $service) {
			$sum += $service->price;
		}
		$sum -= $toRebate;
		/*
		 * если у юзера нет денег, приостановить все сервисы
		 */
		if ($user->balance < $sum) {
			dbUpdate('users_subscribe_services', ['active' => 'no'], "userId = $userId AND serviceId IN ($servicesIds)");
			// todo какие либо действия по этому поводу
		} else {
			$user->balance -= $sum;
			$user->save();
			/*
			 * пишем лог покупок
			 */
			$log = (new PaymentsLogModel);
			$log->userId = $userId;
			$log->date = new SQLvar('NOW()');
			$log->comment = sprintf('Абонентская плата за подключенные сервисы');
			$log->sum = $sum;
			$log->create();
			/*
			 * обновляем дату проплаты у сервисов
			 */
			$lastDateInMonth = date('Y-m-t');
			dbUpdate('users_subscribe_services', ['paidTillDate' => $lastDateInMonth], "userId = $userId AND serviceId IN ($servicesIds)");
		}
	}
	/**
	 * Отправить деньги другому пользователю
	 * @param $userIdFrom
	 * @param $userIdTo
	 * @param $amount
	 * @param string $comment
	 *
	 * @throws Exception
	 */
	function sendMoneyToUser($userIdFrom, $userIdTo, $amount, $comment = '') {
		$userFrom = (new CjclubUserModel)->load($userIdFrom);
		$amountToPay = $amount * CONFIG::$INTERUSER_TRANSFER_MONEY_FEE;
		if ($userFrom->balance < $amountToPay) {
			throw new Exception('Недостаточно средств, чтобы выполнить перевод');
		}
		$userTo = (new CjclubUserModel)->load($userIdTo);
		if (!$userTo) {
			throw new Exception('Аресат не найден');
		}
		$userFrom->balance -= $amountToPay;
		$userTo->balance += $amount;
		$userFrom->save();
		/*
		 * лог для отправителя
		 */
		$log = (new PaymentsLogModel);
		$log->userId = $userIdFrom;
		$log->date = new SQLvar('NOW()');
		$log->comment = sprintf('Перевод пользователю %s', $userTo->login);
		$log->sum = $amountToPay;
		$log->create();

		$userTo->save();
		/*
		 * лог для получателя
		 */
		$log = (new PaymentsLogModel);
		$log->userId = $userIdTo;
		$log->date = new SQLvar('NOW()');
		$log->comment = sprintf('Перевод от пользователя %s', $userFrom->login);
		$log->sum = -$amount;
		$log->create();
	}

	/**
	 * Получить список пользователей
	 */
	function getUsers($id) {
		Page::setName('Пользователи с подключенной услугой');
		$usersQuery = dbSelect('users_subscribe_services', 'userId', "serviceId = $id", DB_GETQUERY);
		$users = (new DModelsCollection('CjclubUserModel'))->load("id IN ($usersQuery)");
		$result = new CRUDEnvelope();
		$result->model = $users;
		$result->form = new DForm();
		$result->form->add(new StaticInput('name'));
		$result->form->add((new TemplateInput)->setTemplate('<a href="/admin/users/{id}">к пользователю</a>')->bindToModelProperties('id,name'));
		return $result;
	}
}
?>