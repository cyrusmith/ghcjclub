<?
/**
 * Class DemoCtrl
 * Демонстрационный контроллер.
 *
 *
 * Связь URL и HTTP-метода запроса с контроллером осуществляется в роутере.
 * funcs/routers.php
 *
 * Router::get()->connect('HTTP-метод', 'регулярное выражение для URL', 'Класс контроллера/метод контроллера');
 * HTTP-метод может быть:
 *
 * Router::$GET
 * Router::$POST
 * Router::$PUT
 * Router::$DELETE
 *
 * регулярное выражение для URL может содержать именованные группы, тогда значение группы будет передано в метод контроллера с таким же именем:
 * Router::get()->connect(Router::$PUT, '^projects/(?<projectId>\d+)?$', 'DemoCtrl/doAction');
 *
 * значение projectId будет подставлено, как аргумент $projectId метода doAction
 *
 *
 * На уровне ядра прописана проверка уровня доступа "access.{Класс контроллера}.{метод контроллера}"
 */
class DemoCtrl extends DController {
	/**
	 * В дальнейшем перейдем на использование Dependency Injection,
	 * поэтому уже сейчас надо в конструкторе инициализировать все свойства,
	 * чтобы затем переписать это на присвоение из аргументов конструктора
	 */

	/**
	 * @var DataBase
	 */
	private $db;
	/**
	 * @var RDS
	 */
	private $rds;
	/**
	 * @var DemoModel
	 */
	private $model;
	function __construct() {
		$this->db  = ObjectsPool::get('DataBase');
		$this->rds = ObjectsPool::get('RDS');
		/*
		 * если контроллер использует модель, то создавать модель также в конструкторе
		 */
		$this->model = new DemoModel();
		parent::__construct(); // TODO: Change the autogenerated stub
	}
	/**
	 * Работа с базой данных
	 */
	function databaseWork() {
		/*
		 * выборка из БД
		 *
		 * query_type:
		 * DB_SELECT_ONE - возвращает единственное значение. Если в списке полей было несколько, то значение первого поля
		 * DB_SELECT_OBJ - возвращает первую строку результата, как объект
		 * DB_SELECT_OBJS - возвращает все строки результата, как ассоциативный массив объектов с ключом по первому полю из списка полей
		 */
		$this->db->select('tablename', 'fields,separated,by,comma', 'sql after where', 'query_type');
		$this->db->insert('tablename', 'array of values');
		$this->db->replace('tablename', 'array of values');
		$this->db->delete('tablename', 'sql after where');
		$this->db->update('tablename', 'array of values', 'sql after where');
		/*
		 * возвращает количество затронутых строк в последней операции
		 */
		$this->db->affectedRows();
	}

	/**
	 * Работа с проверкой уровня доступа.
	 *
	 * Доступы описаны в файле funcs/setuser.php
	 *
	 */
	function userPrivilegesWork() {
		/*
		 * проверить доступ пользователя к правилу access_rule,
		 * в случае отсутствия будет выброшено исключение
		 */
		$this->rds->check('access_rule');
		/*
		 * проверить доступ и вернуть результат проверки, как bool
		 */
		$doesUserHaveAccess = $this->rds->is('access_rule');
		/*
		 * если пользовтаель авторизован...
		 */
		if ($this->rds->isLogged) {
			/*
			 * получение ID пользователя
			 */
			$userId = $this->rds->userId;
			/*
			 * получение модели пользователя
			 */
			$signedInUserModel = $this->rds->userInfo;
		}
	}
	/**
	 * Работа с моделями и коллекциями
	 */
	function modelsAndCollectionsWork() {
		/*
		 * загрузить модель данными из БД
		 */
		$this->model->load('id = 12');
		if (!$this->model->id) {
			throw new Exception('No data found!');
		}
		/*
		 * установка нового значения свойства
		 */
		$this->model->name = 'new name';
		/*
		 * установка нескольких свойств через массив (также можно через объект)
		 */
		$this->model->sets([
			'name' => 'new name',
			'type' => 'yes'
		]);
		/*
		 * сохраняем модель
		 */
		$this->model->save();
		/*
		 * вернуть значение модели
		 */
		return $this->model->getAsStdObject();
		/*
		 * загрузить массив моделей
		 */
		$listOfModels = new DModelsCollection($this->model);
		$listOfModels->load('id > 1 order by id desc limit 0,50');
		foreach ($listOfModels as $model) {
			/*
			 * коллекция имплементирует Iterator, возвращая в $model оъект класса Модели
			 */
		}
		/*
		 * вернуть массив объектов
		 */
		return $listOfModels->getAsStdObjects();
	}

	/**
	 * Пример обработчика действия
	 * @param $projectId
	 * @param string $title
	 * @throws Exception
	 * @return object
	 */
	function doAction($projectId, $title = '') {
		if (!is_numeric($projectId)) {
			throw new Exception('Неверный параметр');
		}
		if (empty($title)) {
			throw new Exception('Укажите название');
		}
		$this->model->load($projectId); // загрузка модели по ключу
		$this->model->name = $title;
		$this->model->save();
		return $this->model->getAsStdObject();
	}

}
?>