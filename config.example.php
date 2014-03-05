<?
class CONFIG extends ConfigKernel {
	static $PATH_AVATARS = '_files/avatars';
	static $PATH_TrackPics = '_files/trackpics';
	static $PATH_Albums  = '_files/albums';
	static $FORUM_ENABLED = false;
	static $PATH_UserFiles;
	static $PATH_ListenUrl;
	static $PATH_DownloadUrl;
}
	CONFIG::$LOCALE        = 'ru_RU.UTF-8';
	CONFIG::$PASSWORD_SALT = 'something_random';
	CONFIG::$GZIP          = false;
	CONFIG::$DB->setConfig("127.0.0.1", "database", "username", "password", "cjclub_");
	/*
	 * PATHS
	 */
	CONFIG::$PATH_ABS        = '/path/to/cjclub';
	CONFIG::$PATH_KERNEL_ABS = '/path/to/cjclub/d-engine';
	CONFIG::$PATH_URL        = 'http://localhost/cjclub';
	CONFIG::$PATH_THEME      = 'views/main';
	CONFIG::$PATH_UserFiles  = '';
	CONFIG::$USE_COOKIE_FOR_AUTH = true;
	CONFIG::$ITEMPERPAGE       = 50;
	CONFIG::$INCPATH_SEPARATOR = ':';
	/*
	 * ERRORS DEBUGS
	 */
	CONFIG::$ERROR_DISPLAY  = true;
	CONFIG::$DEVELOPING     = true;
	CONFIG::$DEBUG          = false;
//	CONFIG::$DEBUG_SECTIONS = 'packages,lang,php,callback,rds,other,db,views';
//	CONFIG::$DEBUG_TO_FILE  = '/Users/dron/tmp/log';
	/*
	 * PACKAGES
	 */
	CONFIG::$PACKAGES->register('Translator', 'CMS/translator');
	CONFIG::$PACKAGES->register('DataBase', 'CMS/DB/mysql');
	CONFIG::$PACKAGES->register('DForm', 'CMS/DForm/inputs');
	CONFIG::$PACKAGES->register('Selector', 'CMS/DForm/inputs');
	CONFIG::$PACKAGES->register('StaticInput', 'CMS/DForm/inputs');
	CONFIG::$PACKAGES->register('CheckboxesGroup', 'CMS/DForm/inputs');
	CONFIG::$PACKAGES->register('DateInput', 'CMS/DForm/inputs');
	CONFIG::$PACKAGES->register('FormElements', 'CMS/DForm/DForm');
	CONFIG::$PACKAGES->register('DBSelect', 'CMS/DBA/dba');
	CONFIG::$PACKAGES->register('MailLetter', 'controllers/Mailer');
	CONFIG::$PACKAGES->register('JSManager', 'CMS/includemanager');
	CONFIG::$PACKAGES->register('CSSManager', 'CMS/includemanager');
	CONFIG::$PACKAGES->register('CacheSlot', 'CMS/Cache');
	CONFIG::$PACKAGES->register('AuthorIdView', 'controllers/View');
	CONFIG::$PACKAGES->register('CityView', 'controllers/View');
	CONFIG::$PACKAGES->register('CountryView', 'controllers/View');

CONFIG::$PACKAGES->register('EmptyView', 'controllers/View');
CONFIG::$PACKAGES->register('ProjectSelector', 'controllers/View');
CONFIG::$PACKAGES->register('MusicStylesSelector', 'controllers/View');
?>
