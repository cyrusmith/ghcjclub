<?
class AuthHelper {
	function onLogin(&$handlingResult, $userInfo) {
	}
	function onLogout(&$handlingResult, $userInfo) {
		$handlingResult->setRedirect(CONFIG::$PATH_URL);
	}
}
class CjclubUserSettings {}
function main(){
	Events::addListener('RDSsetup', 'setUserRules');
//	Events::addListener('onLogin', 'AuthHelper/onLogin');
//	Events::addListener('onLogout', 'AuthHelper/onLogout');
	Events::addListener('beforeRouter', 'setRoutes');
	Events::addListener('beforeController', 'initMainTemplate');
	Events::addListener('MainException', 'MainExceptionCallback');
	Events::addListener('compileMailerBody', 'compileMailerBody');
	Events::addListener('setupTinyMCE', 'setupTinyMCE');
	/* enable js and css compilation
	if (CONFIG::getPackages()->available('CSSManager'))
		CSSManager::$compileToFolder = 'compiled';
	if (CONFIG::getPackages()->available('JSManager'))
		JSManager::$compileToFolder  = 'compiled';
	*/
	/*
	 * load default language pack
	 */
	ObjectsPool::get('Translator')->load(CONFIG::$PATH_ABS.'/views/lang.ini');
}
function initMainTemplate(){
	if (strpos(Page::getUrl(), 'admin') !== false) {
		CONFIG::$PATH_THEME = 'views/admin';
	}
}
function accessDenied(){
	throw new ExceptionTranslated('accessdenied');
}
function garbageCollecting(){
}
/**
 * ERROR HANDLERS
 */
function MainExceptionCallback($out, $exception) {
	$tpl = 'errorpage.html';
	$out = $exception->getMessage();
	switch (get_class($exception)) {
		case 'Exception404':
			header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
			Page::setName('Страница не найдена');
			$out = 'Not found.html';
			break;
		case 'ExceptionDataValidation':
			header('Content-Type: application/json');
			break;
		case 'ExceptionCMS':
			$out = (CONFIG::$DEVELOPING) ? $exception->getMessage() : 'Ошибка системы';
			break;
		default:
			//$errors = $exception->getMessage();
			break;
	}
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		//$out = View::factory(VC_DTPL, CONFIG::$PATH_THEME.'/'.$tpl)->assign($out)->get();
	}
	return $out;
}
function compileMailerBody($body){
	$defLang = Versions::getDefault()->id;
	$curLang = Versions::getCurrent()->id;
	$templates['HEADER'] = dbSelect('mailtemplates', 'message', "code = 'HEADER' AND (lang_id ={$curLang} OR lang_id = {$defLang}) ORDER BY IF(lang_id={$defLang},1,0) LIMIT 0,1", DB_SELECT_ONE);
	$templates['FOOTER'] = dbSelect('mailtemplates', 'message', "code = 'FOOTER' AND (lang_id ={$curLang} OR lang_id = {$defLang}) ORDER BY IF(lang_id={$defLang},1,0) LIMIT 0,1", DB_SELECT_ONE);
	$body = str_replace(array('{header}', '{footer}'), array($templates['HEADER'], $templates['FOOTER']), $body);
}
function setupTinyMCE($object){
	$object->config->theme	= 'advanced';
	$object->config->theme_advanced_resizing = false;
	$object->config->theme_advanced_statusbar_location = "bottom";
	$object->config->theme_advanced_toolbar_align = "left";
	$object->config->relative_urls = true;
	$object->config->document_base_url = CONFIG::$PATH_URL.'/';
	$object->config->plugins = 'safari,pagebreak,table,advhr,advlink,advimage,inlinepopups,contextmenu,nonbreaking,xhtmlxtras,paste,fullscreen,images';
	$object->config->content_css = "./skins/general/text.css";
	$object->config->body_class = "text";
	$object->config->theme_advanced_buttons3_add = "pastetext,pasteword,fullscreen,images";
	$object->config->paste_create_paragraphs = false;
	$object->config->paste_create_linebreaks = false;
	$object->config->paste_use_dialog = true;
	$object->config->paste_auto_cleanup_on_paste = true;
	$object->config->paste_convert_middot_lists = false;
	$object->config->paste_unindented_list_class = "unindentedList";
	$object->config->paste_convert_headers_to_strong = true;
//	$object->config->elements = 'table,save,advhr,advlink,advimage,doclink,insertdatetime,preview,searchreplace,print,contextmenu,paste';
	$path = CONFIG::$PATH_URL;
	$dir = CONFIG::$PATH_ABS;
//	;
	// 'safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template',
}


/*function scandir($dir) {
	$dh  = opendir($dir);
	while (false !== ($filename = readdir($dh))) {
		$files[] = $filename;
	}

	sort($files);
	return $files;
}*/
?>