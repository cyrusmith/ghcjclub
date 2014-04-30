<?php

/**
 * Created by PhpStorm.
 * User: Artist
 * Date: 24.04.14
 * Time: 15:37
 *
 * TODO:  В PHP Storm (7.1.3) есть баг - он работает только с PHPUnit 3.7
 */
abstract class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Выключаем выкидывание ексепшенов при ошибках
     */
    protected function setUp()
    {
        PHPUnit_Framework_Error_Deprecated::$enabled = false;
        PHPUnit_Framework_Error_Notice::$enabled = false;
        PHPUnit_Framework_Error_Warning::$enabled = false;
    }

}

abstract class BackendTest extends TestCase
{
}

class TestUtils
{

    private static $lastJson = null;

    /**
     * @param Object $lastJson
     */
    public static function setLastJson($lastJson)
    {
        self::$lastJson = $lastJson;
    }

    /**
     * Возвращает обьект - результат работы контроллера
     * @return Object
     */
    public static function getLastJson()
    {
        return self::$lastJson;
    }

    private static $lastRouteParamsKeys = array();

    //TODO Реализовать удобные методы
    public static function userLogin($userId)
    {

    }

    /**
     * @param string $method Переменная $_SERVER['REQUEST_METHOD']
     * @param string $route $_REQUEST[CONFIG::$ACTION_VAR_NAME]
     * @param array $params
     */
    public static function launchRoute($method = "GET", $route = '', $params = array())
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_REQUEST[CONFIG::$ACTION_VAR_NAME] = $route;
        foreach (self::$lastRouteParamsKeys as $lastParamKey) {
            unset ($_POST[$lastParamKey], $_GET[$lastParamKey], $_REQUEST[$lastParamKey]);
        }
        $_POST = array_merge($_POST, $params);
        $_REQUEST = array_merge($_REQUEST, $params);
        $_GET = array_merge($_GET, $params);
        self::$lastRouteParamsKeys = array_keys($params);
        require("../d-engine/kernel.php");
    }
}

require('../d-engine/config.php');
require('../config.php');
require('../d-engine/setup.php');
require('../funcs/_init.php');

/**
 * Повторное выкидвание исключения для тестирования кидаемых исключений
 */
Events::addListener("KernelException", function ($result, $e) {
    throw $e;
});

Events::addListener("ControllerReturn", function ($result) {
    TestUtils::setLastJson($result);
});