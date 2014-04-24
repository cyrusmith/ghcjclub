<?php
$result = 'Empty result';
$handlerObject = null;
try {
//	if (!defined('KERNEL_SETTED_UP'))
//		require_once dirname(__FILE__).'/setup.php';
    Events::raise('beforeRouter');
    $route = Router::get()->getRoute($_SERVER['REQUEST_METHOD'], isset($_REQUEST[CONFIG::$ACTION_VAR_NAME]) ? $_REQUEST[CONFIG::$ACTION_VAR_NAME] : '');
    /*
     * проверить доступ
     */
    if ($route->class && $route->method) {
        ObjectsPool::get('RDS')->check(sprintf('access.%s.%s', $route->class, $route->method));
    }
    Events::raise('beforeController');
    $result = Router::get()->process($route);
    Events::raise('ControllerReturn', $result); // Для тестов
    if (!is_scalar($result)) {
        if ($result instanceof DModel) {
            $result = $result->getAsStdObject();
        } else if ($result instanceof DModelsCollection) {
            $result = $result->getAsStdObjects();
        }
        if (!function_exists('JSONserialize'))
            require_once 'funcs/jsonSerializer.php';
        $result = JSONserialize($result);
        header('Content-Type: application/json');
    }
} catch (Exception $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden', true, 403);
    $handled = Events::raise('MainException', $result, $e);
    if (!$handled) {
        $result = $e; //->getMessage();
    }
}
/*
 * уничтожаем созданный объект контроллера для корректного порядка уничтожения объектов
 * (если этого не сделать, то handlerObject будет уничтожен после отработки скрипта,
 * и содержащиеся в нем ссылки на RDS не дадут вызваться деструктору RDS...)
 */
unset($handlerObject);
Events::raise('beforeOut', $result);
echo $result;
//Иначе тесты не сработают
//Фреймворк небыл подготовлен к тестам
if (!class_exists("PHPUnit_Framework_TestCase", false)) {
    exit();
    Debug::printInfo();
}