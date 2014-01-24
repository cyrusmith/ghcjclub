<?
$dir = dirname(__FILE__);
/*
 * регистрируем синглтоны
 */
ObjectsPool::put('DataBase', new DataBase(CONFIG::getDBCfg()));

$rds = new RDS;
ObjectsPool::put('RDS', $rds);
ObjectsPool::put('RDS_Interface', $rds);
ObjectsPool::put('Translator', new Translator);
/*
 * подключаем файлы
 */
require_once $dir.'/routes.php';
require_once $dir.'/setuser.php';
require_once $dir.'/base.php';
main();
setUserRules();

//require(CONFIG::$PATH_ABS.'/funcs/HybridAuth/Auth.php');
?>