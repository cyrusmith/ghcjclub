<?php
class AuthCtrl extends DController {
	private $rds;
	/**
	 * @var UserModel
	 */
	private $userModel;

	//function __construct($rds, $userModel) {
	function __construct() {
		//$this->rds = RDS::get();
		$this->userModel = new UserModel(new CjclubUserProxyForLogin);
		$this->userModel
			->maskPropertyList('id,type_id,login,password,name');
	}

	/**
	 * Авторизация на сайте
	 * @param $login
	 * @param $password
	 * @param bool $remember
	 * @internal param $model модель для авторизации
	 * @return HandlingResult сообщение об успешной и не очень авторизации
	 */
	function login($login, $password, $remember = false) {
		$this->userModel->login = $login;
		$this->userModel->password = $password;
		$this->userModel->load();
		if ($remember) {
			setcookie('cjclubauth', md5($login.$password), time() + 43200, '/', '.cjclub.ru');
		}
		return $this->userModel->getAsStdObject();
	}

	/**
	 * Выход из учётной записи
	 * @return mixed либо сообщение об успешном выходе, либо ничего
	 */
	function logout () {
//		if (CONFIG::$USE_COOKIE_FOR_AUTH)
//			dbUpdate('rds_users', array('autologin' => new SQLvar('NULL')), "id = {$RDS->userId}");
		setcookie(session_name(), '', time()-3600);
		session_destroy();
		return true;
	}
}
