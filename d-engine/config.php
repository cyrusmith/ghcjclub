<?php
/**
 * Конфигурация соединения с БД.
 *
 * @package config
 * @author DroN
 * @copyright 04.2006
 */
class DBCFG {
	/**
	 * урл сервера БД
	 *
	 * @var string
	 */
	public $hostspec;
	/**
	 * Имя БД
	 *
	 * @var string
	 */
	public $database;
	/**
	 * Логин
	 *
	 * @var string
	 */
	public $username;
	/**
	 * Пароль
	 *
	 * @var string
	 */
	public $password;
	/**
	 * Префикс для таблиц в БД
	 *
	 * @var string
	 */
	public $prefix;
	public $options;
	/**
	 * Вернуть массив конфигурации
	 *
	 * @return array
	 */
	public function getCfgArray() {
		return array(
			'phptype'  => 'mysql',
			'username' => $this->username,
			'password' => $this->password,
			'hostspec' => $this->hostspec,
			'database' => $this->database,
		);
	}
	/**
	 * Задать параметры
	 * @param string $h
	 * урл сервера БД
	 * @param string $db
	 * Имя БД
	 * @param string $u
	 * Логин
	 * @param string $p
	 * Пароль
	 * @param $prefix
	 */
	public function setConfig($h, $db, $u, $p, $prefix) {
		$this->hostspec = $h;
		$this->database = $db;
		$this->username = $u;
		$this->password = $p;
		$this->prefix   = $prefix;
	}
}

/**
 * Конфигурационный файл проекта.
 *
 * @package config
 * @author DroN
 * @copyright 04.2006
 */
class ConfigKernel {
	/**
	 * Версия движка
	 *
	 * @var Major.Minor.Revision
	 */
	static public $CMS_VERSION = '2.0';
	/**
	 * Имя поля запроса, содержащее путь (в .htaccess без RewriteBase)
	 * @var string
	 */
	static public $ACTION_VAR_NAME = 'action';
	/**
	 * Класс конфигурации БД
	 *
	 * @var DBCFG
	 */
	static public $DB;
	/**
	 * вернуть объект с настройками БД
	 *
	 * @return DBCFG
	 */
	static public function getDBCfg() {
		return self::$DB;
	}
	/**
	 * зарегистрированные модули системы
	 *
	 * @var PackagesManager
	 */
	static public $PACKAGES;
	/**
	 * Вернуть объект PACKAGES
	 *
	 * @return PackagesManager
	 */
	static public function getPackages() {
		return self::$PACKAGES;
	}
	/**
	 * УРЛ проекта
	 *
	 * @var string
	 */
	static public $PATH_URL;
	/**
	 * Абсолютный путь к папке проекта
	 *
	 * @var string
	 */
	static public $PATH_ABS;
	static public $PATH_THEME = 'views';
	/**
	 * Массив путей к темам, в которых будет произведен поиск шаблонов при отсутствии по основному пути
	 */
	static public $PATH_THEMENS_DEPENDED_ON = null;
	/**
	 * папка с движком, лежащяя всегда на первом уровне от корня сайта
	 *
	 * @var string
	 */
	static public $KERNEL_FOLDER = 'd-engine';
	/**
	 * автоопределяемые пути к движку
	 *
	 * @var string
	 */
	static public $PATH_KERNEL_ABS;
	static public $PATH_KERNEL_URL;
	/**
	 * Относительный путь к папке закачиваемых файлов
	 *
	 * @var string
	 */
	static public $PATH_UPLOADFILES = 'cgi/files';
	/**
	 * Разделитель путей в include_path
	 *
	 * @var char
	 */
	static public $INCPATH_SEPARATOR = ':';
	/**
	 * локаль
	 */
	static public $LOCALE;
	static public $TIMEZONE = 'UTC';
	/**
	 * Производить ли загрузку файла classname.ini из папки lang/codepage при создании класса
	 *
	 * @var bool
	 */
	static public $AUTOLOAD_CONTROLLERS_LANGPACK = false;
	static public $USE_COOKIE_FOR_AUTH           = false;

	static public $SESSIONVAR    = 'sid';
	static public $ITEMPERPAGE   = 10;
	static public $DEVELOPING    = false;
	static public $PASSWORD_SALT = '';
	/*
	 * ERROR LEVEL
	 */
	/**
	 * УРОВЕНЬ ОТОБРАЖАЕМЫХ ОШИБОК
	 * @var unknown_type
	 */
	static public $ERROR_LEVEL = E_ALL;
	/**
	 * Показывать ли ошибки
	 *
	 * @var bool
	 */
	static public $ERROR_DISPLAY = true;
	static public $PAGERCLASS    = 'PagerHelperFull';
	/*
	 * DEBUG
	 */
	/**
	 * Консоль отладки
	 *
	 * @var bool
	 */
	static public $DEBUG = false;
	/**
	 * Писать в консоль предупреждения
	 *
	 * @var bool
	 */
	static public $DEBUG_WARNINGS;
	/**
	 * Уровень стека для трассировки сообщений в консоли
	 *
	 * @var int
	 */
	static public $DEBUG_TRACELEVEL = 5;
	/**
	 * список секций для отображения в консоли, если null - то выводить все
	 * если секцию надо заблокировать, то она указывается с префиксом "!"
	 * @var string
	 */
	static public $DEBUG_SECTIONS = null;
	/**
	 * Записывать информацию в файл
	 * @var string
	 * путь к файлу
	 */
	static public $DEBUG_TO_FILE = false;
	/**
	 * Замер параметров генерации
	 * @var bool
	 */
	static public $BENCHMARK = true;
	/*
	 * GENERATED CONTENT'S OPTIONS
	 */
	/**
	 * Сжимать контент перед выводов в браузер
	 *
	 * @var bool
	 */
	static public $GZIP = true;
	/**
	 * Удалять дублирующие пробелы в контенте
	 *
	 * @var bool
	 */
	static public $CLEAR_SPACE_IN_OUTPUT = false;
	/**
	 * Пути для include_path
	 *
	 * @var array
	 */
	static public $INCPATH = array();
	static public function pre() {
		self::$DB       = new DBCFG();
		self::$PACKAGES = new PackagesManager();
		self::$PACKAGES->register('DataBase', 'CMS/DB/mysql');
		self::$PACKAGES->register('Registry', 'CMS/registry');
		self::$PACKAGES->register('CRUD', 'CMS/CRUD/CRUD');
		self::$PACKAGES->register('CRUDtemplates', 'CMS/CRUD/CRUD_helpers');
		
		self::$PACKAGES->register('DModelValidated', 'CMS/DModel/DModelValidated');
		self::$PACKAGES->register('DModelDynamic', 'CMS/DModel/DModelDynamic');
		self::$PACKAGES->register('DModelAutosave', 'CMS/DModel/DModelAutosave');
		
		self::$PACKAGES->register('FormElement', 'CMS/DForm/inputs');
		self::$PACKAGES->register('FormElements', 'CMS/DForm/inputs');
		self::$PACKAGES->register('UsualInput', 'CMS/DForm/rootinputs');
		self::$PACKAGES->register('LabeledInput', 'CMS/DForm/rootinputs');
		self::$PACKAGES->register('PreprocessedInput', 'CMS/DForm/rootinputs');

		self::$PACKAGES->register('DImage', 'CMS/DImage');
		self::$PACKAGES->register('ModelToSQL', 'CMS/ModelToSQL');
		self::$PACKAGES->register('MailAttachment', 'controllers/Mailer');
		self::$PACKAGES->register('PagerHelperFull', 'CMS/pagerhelper');
		self::$PACKAGES->register('View', 'CMS/views/View');
		self::$PACKAGES->register('UserDefaultModel', 'models/UserDefault');
	}
	static public function init() {
		if (empty(self::$PATH_ABS)) {
			trigger_error('CONFIG::$PATH_ABS is not specified, use dirname of the file defining config file', E_USER_NOTICE);
			self::$PATH_ABS = dirname(__FILE__);
			self::$PATH_KERNEL_ABS = dirname(__FILE__);
		}
		if (empty(self::$PATH_KERNEL_ABS))
			self::$PATH_KERNEL_ABS = self::$PATH_ABS.'/'.self::$KERNEL_FOLDER;
		if (empty(self::$PATH_KERNEL_URL))
			self::$PATH_KERNEL_URL = self::$PATH_URL.'/'.self::$KERNEL_FOLDER;

		$incpaths = array(
			self::$PATH_ABS,
			self::$PATH_KERNEL_ABS,
			self::$PATH_ABS.'/controllers',
			self::$PATH_ABS.'/models',
			self::$PATH_KERNEL_ABS.'/controllers',
			self::$PATH_KERNEL_ABS.'/CMS',
			self::$PATH_KERNEL_ABS.'/models'
		);
		self::$INCPATH = array_merge($incpaths, self::$INCPATH);
	}
	static public function absPath($path) {
		if (strpos($path, '/') === 0) return $path; // if unixlike abs path already given
		if (strpos($path, ':') === 1) return $path; // if windowslike abs path already given
		return self::$PATH_ABS.'/'.$path;
	}
	static public function urlPath($path) {
		if (strpos($path, 'http://') === 0)
			return $path;
		if (strpos($path, 'https://') === 0)
			return $path;
		if (strpos($path, '/') !== 0)
			$path = '/'.$path;
		return self::$PATH_URL.$path;
	}
	static public function abs2url($path) {
		$relative = str_replace(CONFIG::$PATH_ABS, '', $path);
		return self::urlPath($relative);
	}
}
/**
 * Используемые модули системы.
 */
class PackagesManager {
	/**
	 * Реестр зарегистрированных компонент
	 *
	 * @var array
	 */
	public $reg = array();
	/**
	 * Зарегистрировать модуль
	 * @param путь к файлу $dir
	 * @param string $dir
	 */
	public function register($classname, $dir = '') {
		$this->reg[$classname] = $dir;
	}
	/**
	 * Узнать зарегистрирован ли модуль
	 *
	 * @param string $name
	 * @return bool
	 */
	public function available($name) {
		return (array_key_exists($name, $this->reg));
	}
	/**
	 * Проверка регистрации с выдачей исключения
	 *
	 * @param string $name
	 */
	public function check($name) {
		if (!$this->available($name))
			trigger_error("Package <b>$name</b> is not available", E_USER_ERROR);
	}
	/**
	 * вернуть директорию с классом либо false
	 *
	 * @param string $name
	 * @return string/bool
	 */
	public function get($name) {
		return ($this->available($name)) ? $this->reg[$name] : false;
	}
	/**
	 * Загрузить пакет
	 * @param unknown_type $name
	 */
	public function load($name) {
		if (class_exists($name, false)) return;
		if (($dir = $this->get($name)) === false) {
			trigger_error("Package $name is not installed (dir $dir)", E_USER_ERROR);
		}
		$t = CMS::$autoLoad;
		CMS::$autoLoad = false;
		if ($dir == '') $dir = $name.'/'.$name;
		$fileWithPackage = $dir.'.php';
		$res = include_once($fileWithPackage);
		CMS::$autoLoad = $t;
		if (!$res)
			trigger_error("Package <b>$name</b> loading from $fileWithPackage failed", E_USER_ERROR);
		Debug::message('loaded', $name, 'PACKAGES', false);
	}
}
ConfigKernel::pre();
?>