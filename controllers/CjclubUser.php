<?
class CjclubUser extends CRUD {
	protected $modelClassName = 'CjclubUserModel';
    protected $fieldLabels = [
        'login'      => 'Логин',
        'email'      => 'E-mail',
        'name'       => 'Меня зовут',
        'regdate'    => 'Дата регистрации',
        'balance'    => 'Баланс',
        'hasAvatar'  => 'Аватарка',
        'gtype'      => 'Пол',
    ];
	/**
	 * @var FilterForm
	 */
	protected $filter;
	function buildFilter(){
		/*
		 * Модель данных фильтра
		 */
		$filter = $this->getModelInstance()
            ->maskPropertyList('login,name,email,type_id')
            ->sets($_REQUEST);
		/*
		 * Преобразователь Модель->SQL
		 */
		$sql = new ModelToSQL($filter, true);
        if (isset($_REQUEST['login']) && !empty($_REQUEST['login']))
            $sql->setPatternFor('login', 'login LIKE "%%'.$_REQUEST['login'].'%%"');
        if (isset($_REQUEST['name']) && !empty($_REQUEST['name']))
            $sql->setPatternFor('name', 'name LIKE "%%'.$_REQUEST['login'].'%%"');
        if (isset($_REQUEST['email']) && !empty($_REQUEST['email']))
            $sql->setPatternFor('email', 'email LIKE "%%'.$_REQUEST['login'].'%%"');
        if (isset($_REQUEST['type_id']) && !empty($_REQUEST['type_id']))
            $sql->setPatternFor('type_id', 'type_id = '.$_REQUEST['type_id']);
		/*
		 * форма
		 */
		$filterForm = DForm::generateByModel($filter);
        $filterForm->generateLabels('filter_');
		$filterForm
			->addAttribute('class="filter"')
			->addAttribute('method="get"');
		$filterForm->add(
            (new StaticInput)
				->setValue(sprintf('<div id="title" class="title">%s</div>', s('filtertitle'))),
			DForm::$PREPEND // вставить заголовок в начало формы
			);
        // Чтение типов из базы
        $type = new DModelDynamic(array(
            new DModelProperty('id', 'int'),
            new DModelProperty('value', 'string'),
        ));
        $type->setProxy(new DModelProxyDatabase('usertypes'));
        $types = new DModelsCollection($type);
        $types->load('true ORDER BY id');
        $filterForm->add(
            (new Selector)
                ->setName('type_id')
                ->addOption('', '')
                ->addOptions($types->getAsHash('value'))
                ->setLabel('Тип')
        );
        // Кнопки применения и сброса фильтра
        $filterForm->add((new Submit)->setLabel('Применить')->addAttribute('style="display: inline-block;"'));
        if (count($_REQUEST) > 1)
            $filterForm->add((new Button())
                    ->setLabel('Все')
                    ->addAttribute(sprintf('onclick="location=\'%s/%s\'"', CONFIG::$PATH_URL,'admin/users'))
                    ->addAttribute('style="display: inline-block;"')
            );
		$filterForm->setAction('admin/users.html');

		$this->dataForView
			->createPropertyByValue('filterModel', $filter)
			->createPropertyByValue('filterSql', $sql)
			->createPropertyByValue('filterForm', $filterForm);
	}
	/*
	function getModelInstance() {
		$model = parent::getModelInstance();
		return $model;
	}
	*/

    /*
    function getListModel() {
        $this->customize->conditions = "user_id = {$this->RDS->userId}";
        $this->filter = $this->buildFilter();
        $this->customize->conditions .= $this->filter->getResult(true, true);
        return parent::getListModel();
    }
    */

	public function getListModelToFormMap(DModelsCollection $model) {
		$map = parent::getListModelToFormMap($model);
        if (RDS::get()->is('admin'))
            $map['type_id'] = 'TypeView';
		return $map;
	}

	/*
	function getListForm($model) {
		$this->setView(VC_DTPL, 'list_template.php');
		return null;
		return parent::getListForm($model);
	}
	*/
	/*
	function getListModelToFormMap($model) {
		$map = parent::getListModelToFormMap($model);
		return $map;
	}
	*/

	function lists(){
        Page::setName('Список авторов');
        $this->getModelInstance()->getProxy()->setPager(new PagerHelperFull(10));

        // Администрирование
        if (RDS::get()->is('admin') && strpos($_REQUEST['action'], 'admin') !== FALSE) {
            $this->buildFilter();
            $this->customize->fields = 'id,login,email,name,type_id,extraroles,moder_for,frozen,no_tags,emailchecked,ip,comments';
            $this->customize->conditions = "true";
        } // API
        elseif (isset($_REQUEST['action']) && strpos($_REQUEST['action'], 'api/') !== FALSE) {
            $ids = filter_var($_REQUEST['authors'], FILTER_SANITIZE_MAGIC_QUOTES);
            $this->customize->fields = 'id,name,type_id';
            $this->customize->conditions = "id IN ($ids) ORDER BY id DESC";

            $data = parent::getListModel();
            $data = $data->getAsStdObjects();
            foreach ($data as $user)
                $user->avatar = (new File)->getPath($user->id, 0, 'avatar', TRUE);
            echo JSONserialize($data);
            exit;
        } // Всё остальное
        else {
            // Фильтрация списка
            $where = "";
            if (isset($_REQUEST['name']) && is_numeric($_REQUEST['name']))
                $where .= 'name LIKE "%'.$_REQUEST['name'].'%"';
            if (isset($_REQUEST['sel_extra']) && !empty($_REQUEST['sel_extra'])){
                if (!empty($where)) $where .= ' AND ';
                $where .= 'extraroles LIKE "%'.$_REQUEST['sel_extra'].'%"';
            }
            if (isset($_REQUEST['sel_status']) && !empty($_REQUEST['sel_status'])){
                if (!empty($where)) $where .= ' AND ';
                $where .= 'type_id = '.$_REQUEST['sel_status'];
            }
            if (isset($_REQUEST['country_id']) && is_numeric($_REQUEST['country_id'])){
                if (!empty($where)) $where .= ' AND ';
                $where .= 'country_id = '.$_REQUEST['country_id'];
            }
            if (isset($_REQUEST['style_id']) && is_numeric($_REQUEST['style_id'])){
                if (!empty($where)) $where .= ' AND ';
                $tracks = (new Track)->getListModel();
                $tracks->getModelInstance()->maskPropertyList('id,author_id');
                $tracks->load('style_id = '.$_REQUEST['style_id']);
                $tids = [];
                foreach ($tracks->getAsHash('author_id') as $track)
                    $tids[] = $track->id;
                if (!empty($tids)) {
                    $tids = array_unique($tids);
                    $where .= 'id IN('.implode(',', $tids).')';
                }
            }

            if (empty($where)) $where = "TRUE";
            $this->customize->conditions = $where;
        }
		$data = parent::lists();
		return $data;
	}

	function getShowModelToFormMap(DModel $model) {
		$map = parent::getShowModelToFormMap($model);
        if (RDS::get()->isLogged && isset($_REQUEST['edit'])) {

            $map['balance'] = $map['login'] = 'StaticInput';
            $map['emailchecked'] = $map['config'] = 'EmptyView';

            $map['hasAvatar'] = 'FileInput';
        }
		return $map;
	}

	/*
	function getShowModel($id) {
		return parent::getShowModel($id);
	}
	*/

	function getShowForm(DModel $model) {
        $RDS = RDS::get();
		$form = parent::getShowForm($model);
        $password = $email = '';

        foreach ($form as $element) {
            if ($element instanceof LabeledInput) {
                // Назначение меток
                $name = $element->getName();
                if ($name != 'linkToListPage')
                    $element->setLabel(isset($this->fieldLabels[$name]) ? $this->fieldLabels[$name] : $name);
                if ($name == 'password') {
                    $label = (!$RDS->isLogged) ? 'Пароль' : 'Новый пароль';
                    $element->setLabel($label)->setValue('');
                    $password = $element;
                }
                if ($name == 'email' && isset($model->emailchecked) && $model->emailchecked == 'false')
                    $email = $element;
                if ($name == 'emailchecked')
                    $element->setLabel('');
                if ($name == 'gtype') {
                    $options = $element->getOptions();
                    $options['male']   = 'Мужчина';
                    $options['female'] = 'Женщина';
                    $element->addOptions($options);
                }
                if ($name == 'hasAvatar')
                    $element->setValue('');

                // Удаление меток у пустых полей
                if (get_class($element) == 'EmptyView')
                    $element->setLabel('');
            }
        }

        // Смена пароля
        if (!$RDS->is('admin') && !empty($password) && $RDS->isLogged)
            $form->add(
                (new PasswordInput)->setLabel('Старый пароль')->setValue('')->setName('confirm'),
                $password   // вставить подтверждение после пароля
            );

        // Добавление для неподтверждённого адреса
        if (!empty($email)) {
            $form->add(
                (new Button)->setLabel('Выслать подтверждающий код ещё раз')
                    ->addAttribute('onclick="ajaxRequest(\'CjclubUser/sendActivationCode\', {data: \'userId='.$model->id.'\'})"')
                    ->setName('confirmEmail'),
                $email
            );
            $form->add(
                (new StaticInput)->setValue('<span class="red">Неподтвержденный адрес почты! Доступ к ресурсу ограничен</span>'),
                $email
            );
        }

        // Регистрация
        if (!$RDS->isLogged && empty($id)) {
            $form->add(
                (new Checkbox)->setLabel('Я ознакомлен с <a href="offer.html">Правилами проекта</a>')
                    ->setName('acceptrule')
            );
            $form->add(
                (new Checkbox)->setLabel('Я просто Слушатель')
                    ->setName('createproject')
                    ->setChecked(FALSE)
            );
        }
		return $form;
	}


	function show($id = null){
        if ((!RDS::get()->is('admin') || strpos($_REQUEST['action'], 'admin') === FALSE) && $id !== NULL) {
            if (!isset($_REQUEST['edit']) || !RDS::get()->isLogged  || RDS::get()->userId != $id) {
                $user = $this->getShowModel($id);
                $this->setView(VC_DTPL, 'authorView.php');
                Page::setName("Автор '{$user->name}'");
            } elseif (RDS::get()->isLogged && RDS::get()->userId == $id) {
                $this->customize->fields = 'id,hasAvatar,login,regdate,email,emailchecked,name,password,balance';
                $this->setView(VC_DTPL, 'cjclubUserEdit.php');
                Page::setName("Редактирование профиля");
            }
        } // API
        elseif (isset($_REQUEST['action']) && strpos($_REQUEST['action'], 'api/') !== FALSE) {
            $id = filter_var($_REQUEST['author_id'], FILTER_SANITIZE_MAGIC_QUOTES);
            $this->customize->fields = 'id,name,type_id,regdate';

            $data = parent::getShowModel($id);
            $data = $data->getAsStdObject();
            $data->avatar = (new File)->getPath($data->id, 0, 'avatar', TRUE);
            $projects     = (new DModelsCollection('ProjectModel'));
            $projects->getModelInstance()->maskPropertyList('id');
            $projects->load("creatorId = {$data->id}");
            $projects = $projects->getAsStdObjects();
            $ids = '';
            foreach ($projects as $pr)
                $ids .= $pr->id.',';
            $data->projects = substr($ids, 0, strlen($ids) - 1);
            echo JSONserialize($data);
            exit;
        }

        // Регистрация
        if (!RDS::get()->isLogged && empty($id)) {
            $this->customize->fields = 'id,login,password,email,name,gtype';
            Page::setName("Регистрация");
            $this->setView(VC_DTPL, 'cjclubUserEdit.php');
        }
        $data = parent::show($id);
		return $data;
	}

    function validate(DModel $model) {
        // Редактирование профиля
        if (!$this->justInserted) {
            if (!empty($_REQUEST['password']) && $this->getShowModel($model->id)->password !== md5($_REQUEST['confirm']))
                 throw new Exception('Текущий пароль указан неверно!');
            elseif (!isset($_REQUEST['cur_project_id'])) {
                $model->password = empty($_REQUEST['password']) ? md5($_REQUEST['confirm']) : md5($model->password);
                $model->getProxy()->setFieldsWrite('id,login,regdate,email,emailchecked,name,password');
            } else
                $model->getProxy()->setFieldsWrite('id,config');
        } // Регистрация
        else {
            if (empty($_REQUEST['acceptrule']))
                throw new Exception('Необходимо принять правила проекта');

            if (empty($model->name))
                throw new Exception('Введите авторский псевдоним');

            if (empty($model->email))
                throw new Exception('Введите email');
            if (!filter_var($model->email, FILTER_VALIDATE_EMAIL))
                throw new Exception('Введён некорректный email!');
            $user = (new CjclubUserModel)->load("email = '{$model->email}' LIMIT 0,1");
            if (isset($user->id))
                throw new Exception('Такой Email уже занят');

            if (empty($model->login))
                throw new Exception('Введите логин');
            $user = (new CjclubUserModel)->load("login = '{$model->login}'");
            if (isset($user->id))
                throw new Exception('Логин занят!');

            if ($_REQUEST['password'] !== $_REQUEST['confirm'])
                throw new Exception('Пароль и его подтверждение не совпадают');

            $model->password = md5($model->password);
            $model->type_id  = 2;
        }
    }

    function edit(){
        // Изменение активного проекта
        if (isset($_REQUEST['cur_project_id']) && is_numeric($_REQUEST['cur_project_id'])) {
            $_REQUEST['config'] = new stdClass();
            $_REQUEST['config']->curProjectId = $_REQUEST['cur_project_id'];
            RDS::get()->config->curProjectId  = $_REQUEST['cur_project_id'];
        }
        if (isset($_FILES['hasAvatar']) && !empty($_FILES['hasAvatar']->name)) {
            $_REQUEST['hasAvatar'] = 'true';
        }
        parent::edit();

        $user = $this->getShowModel($_REQUEST['id']);
        // Создание пустых проектов и с именем, как и у пользователя
        if (isset($_REQUEST['createproject']) && count($_REQUEST['createproject'])) {
            foreach ($_REQUEST['createproject'] as $type) {
                // Проверка на существование
                $model = (new ProjectModel)->load("creatorId = {$user->id} AND type = '$type'");
                if (isset($model->id) && empty($model->id))
                    continue;

                $fields = [
                    'id'         => 0,
                    'creatorId'  => $user->id,
                    'type'       => $type,
                    'name'       => $_REQUEST['name'],
                    'createDate' => date('Y-m-d H:i:s')
                ];
                $model = (new ProjectModel)->setProxy((new DModelProxyArray($fields)))->load();
                $model->setProxy((new DModelProxyDatabase('projects')));
                $model->create();
            }
        }

        // Регистрация
        if (!RDS::get()->isLogged) {
            $_REQUEST['noreload']    = 'true';
            $_REQUEST['return_user'] = 'true';
            $user = $this->doLogin();
            $res = new HandlingResult('Добро пожаловать');
            $res->setRedirect('/authors/'.$user->id.'?edit');
            return $res;
        } // Сохранение аватарки
        elseif (isset($_FILES['hasAvatar']) && !empty($_FILES['hasAvatar']->name)) {
            (new File)->upload('avatar');
            $res = new HandlingResult('Ok');
            $res->setRedirect('/authors/'.RDS::get()->userId.'?edit');
            return $res;
        }
    }
	/*
	function delete($id = null){
		//$this->customize->conditions = "user_id = {$this->RDS->userId}";
		return parent::delete($id);
	}
	*/

    /**
     * Проверка логина по базе
     * @return HandlingResult найдены совпадения или нет
     */
    function checkLogin() {
        $login = filter_var($_REQUEST['login'], FILTER_SANITIZE_MAGIC_QUOTES);
        $user = (new CjclubUserModel)->load("login = '$login'");

        if (isset($user->id) && !empty($user->id))
            return new HandlingResult(1);
        else
            return new Handlingilchecked;
    }

    /**
     * Получение записей на стене пользователя
     * @param $id идентификатор пользователя
     * @param string $limit ограничения выборки
     * @return DModelsCollection коллекция моделей записей на стене заданного пользователя
     */
    function getComments($id, $limit = '') {
        return (new Comment)->getListModel('object_id = '.$id.' AND object_type = "rds_user" ORDER BY datewritten DESC'.$limit);
    }

    /**
     * Выборка юзеров для главной страницы (блоки "Новые" и "Онлайн")
     * @param $params праметры выборки
     * @return DModelsCollection
     */
    function getForHomePage($params) {
        $this->customize->conditions = $params;
        return $this->getListModel();
    }

    /**
     * Получение моделей проектов конкретного пользователя
     * @param null $id идентификатор пользователя
     * @return DModelsCollection коллекция моделей проектов пользователя
     */
    function getProjects($id = null) {
        if (empty($id))
            $id = RDS::get()->userId;
        $data = new DModelsCollection('ProjectModel');
        $data->load("creatorId = $id");

        return $data;
    }

    /**
     * Определение предпочитаемого музыкального стиля
     * @param $id идентификатор пользователя
     * @return stdClass объект основного стиля
     */
    function getMainStyle($id) {
        $styles = $this->getStyles($id);
        $res = new stdClass();
        $res->id   = array_search(max($styles), $styles);
        $res->name = (new MusicStyleModel)->load('id = '.$res->id)->value;
        return $res;
    }

    /**
     * Получение музыкальных стилей пользователя
     * @param $id идентификатор пользователя
     * @return array массив стилей треков, загруженных пользователем
     */
    function getStyles($id) {
        $projects = $this->getProjects($id);
        $styles   = [];
        if ($projects->count() > 0)
            foreach ($projects as $project) {
                $tracks = (new Project)->getTracks($project->id);
                foreach ($tracks as $track)
                    if (!array_key_exists($track->style_id->id, $styles))
                        $styles[$track->style_id->id] = $track->style_id->value;
            }
        return $styles;
    }

    /**
     * Авторизация на сайте
     * @param $model модель для авторизации
     * @return HandlingResult сообщение об успешной и не очень авторизации
     */
    function doLogin($model = '') {
        $flag = !empty($model);
        $model = ($flag) ? $model : $this->getModelInstance();
	    $_REQUEST['login']    = (!empty($_REQUEST['login'])) ? $_REQUEST['login'] : $model->login;
	    $_REQUEST['password'] = (!empty($_REQUEST['password'])) ? $_REQUEST['password'] : $model->password;
        $model->maskPropertyList('id,type_id,login,password,name')
            ->setProxy(new CjclubUserProxyForLogin)
	        ->sets($_REQUEST);
        $user = $model->load();
        if (isset($_REQUEST['return_user']))
            return $user;

        setcookie('cjclubauth', md5($user->login.$user->password), time() + 43200, '/', '.cjclub.ru');
        $res = new HandlingResult('Добро пожаловать');

        if ($flag) {
            header('Location: '.CONFIG::$PATH_URL.'/migrate');
            exit;
        }
        return $res;
    }

    /**
     * Выход из учётной записи
     * @return mixed либо сообщение об успешном выходе, либо ничего
     */
    function doLogout() {
        $RDS = RDS::get();
        if (!$RDS->isLogged)
            return;
        if (CONFIG::$USE_COOKIE_FOR_AUTH)
            dbUpdate('rds_users', array('autologin' => new SQLvar('NULL')), "id = {$RDS->userId}");
        setcookie(session_name(), '', time()-3600);
        session_destroy();
        return (new HandlingResult)->setRedirect("./");
    }

    /**
     * Посылка письма для подтверждения email
     * @param null $user объект данных юзера
     * @return HandlingResult сообщение об успешной отправке
     */
    function sendActivationCode($user = null){
        if (empty($user)) {
            $userId = getPOSTfiltered('userId', 'n');
            $user = $this->getShowModel($userId);
            $user->maskPropertyList('id,login,email,name');
            $user = $user->getAsStdObject();
        }
        /**
         * подготовить активационный код
         */
        $user->key = defferedActions::insert('signup/activation', array($user->id, $user->email));
        (new CjclubUserModel)->getProperties();
        /**
         * Отправить активационный код
         */
        Mailer::push(new MailLetter($user, 'registration'), $user->email);
        return new HandlingResult('Код выслан на указанный адрес');
    }

    /**
     * Сохранение аватарки
     * @return bool успех выполнения функции
     * @throws Exception Загрузка файла с неправильным расширением
     */
    function savePicture() {
        if (!isset($_FILES['picture']) || ($_FILES['picture']['error'] != 0))
            return false;

        $type = $_FILES['picture']['type'];
        if (strpos($type, 'image/') !== 0)
            throw new Exception('Формат файла не поддерживается');

        $path  = $_FILES['picture']['tmp_name'];
        $width = $height = 213;
        $sizes = getimagesize($path);
        $destinationFile = CONFIG::$PATH_UserFiles.'/'.$this->RDS->userId.'/'.$this->RDS->userId;

        if (($sizes[0] == $width) && ($sizes[1] == $height)){
            rename($path, $destinationFile);
        }else{
            $image = new DImage( $path );
            $thumb = $image->createThumb($width, $height, true, true);
            $thumb->setJPEGQuality(100)->saveAs($destinationFile);
        }
        @unlink($_FILES['picture']['tmp_name']);

        $userModel = $this->getShowModel(RDS::get()->userId);
        $userModel->hasAvatar = true;
        $userModel->save();
        Events::raise('picture', $this->RDS->userId, array('username' => $this->RDS->userInfo->name) );
        return true;
    }

    /**
     * Поиск юзера для слияния аккаунтов
     * @return array результат поиска
     */
    function findForMigration() {
        $login    = filter_var($_REQUEST['login'], FILTER_SANITIZE_MAGIC_QUOTES);
        $password = filter_var($_REQUEST['password'], FILTER_SANITIZE_MAGIC_QUOTES);
        $user = (new CjclubUserModel)->load("login LIKE '$login' AND password LIKE md5('$password')");

        // Юзер не найден
        if (!isset($user->id))
            return ['info' => '<h3 class="gr">Пользователь не найден! Проверьте введённые данные</h3>'];

        // Поиск самого себя
        if (RDS::get()->isLogged)
            if ($user->id == RDS::get()->userId)
                return ['info' => '<h3 class="gr">Нельзя перенести себя в себя!</h3>'];
        $desc = (new ProjectModel)->load("creatorId={$user->id} ORDER BY createDate ASC LIMIT 0,1")->info;
        $desc = (mb_strlen($desc) > 100) ? mb_substr($desc, 0, 100).'...' : $desc;
        $ava = (new File)->getPath($user->id, 0, 'avatar', TRUE);
        return [
            'user' => "<div class='proj cf migrate_user_box'>
                <div class='proj_img'>
                    <img src='$ava' width='100' height='100'>
                    <div class='gb_menu gb_menu__aside'>
                        <a class='ico2 ico2_set migrate_set' href='javascript:void(0)'></a>
                        <a class='ico2 ico2_exit' href='javascript:void(0)'></a>
                        <a class='ico2 ico2_del migrate_del' href='javascript:void(0)'></a>
                    </div>
                    <div class='gb_letter'><div class='gb_letter_in'><i class='ico2 ico2_letter'></i> +0</div></div>
                </div>
                <div class='proj_data'>
                    <div class='proj_name'><a href='#' class='black_link'>{$user->name}</a></div>
                    <div class='proj_desc'>$desc</div>
                </div>
                <input type='hidden' name='migrate-projects[]' value='{$user->id}'>
            </div>"
        ];
    }

    /**
     * Операция слияния аккаунтов
     * @return array результат миграции
     */
    function migrate() {
        if (isset($_REQUEST['ids']) && is_array($_REQUEST['ids'])) {
            if (count($_REQUEST['ids']) > 4)
                return ['info' => 'Вы выбрали слишком много аккаунтов!'];
            $RDS = RDS::get();
            foreach ($_REQUEST['ids'] as $id) {
                // миграция проектов
                $model    = new DModelsCollection('ProjectModel');
                $projects = $model->load("creatorId = $id");
                if ($projects->count() > 0)
                    foreach ($projects as $project) {
                        $project->creatorId = $RDS->userId;
                        $project->save();
                    }

                // миграция комментариев
                $model    = new DModelsCollection('CommentModel');
                $comments = $model->load("authorId = $id");
                if ($comments->count() > 0)
                    foreach ($comments as $comment) {
                        $comment->authorId = $RDS->userId;
                        $comment->save();
                    }

                // удаление аккаунта, прошедшего слияние
                $user = $this->getShowModel($id);
                $user->delete();
            }
            return ['info' => 'Миграция прошла успешно.'];
        }
        else
            return ['info' => 'Ошибка входных данных.'];
    }

    /**
     * Массовое обновление рейтингов активности пользователей
     */
    function updateRating() {}
}
/**
 * Вводится новое отображение поля type_id
 * из-за преобразования значения поля при чтении из базы
 * в models/CjclubUserModel (метод getterConversions)
 */
class TypeView extends StaticInput {
    function getHtml() {
        if (isset($this->value))
            return $this->value->value;
        else return '';
    }
}
?>