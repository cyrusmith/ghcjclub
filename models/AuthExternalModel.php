<?
class AuthExternalModel extends DModel {
	function __construct() {
		$this->addProperty('id', 'int');
        $this->addProperty('login', 'varchar', '255');
        $this->addProperty('type', 'varchar', '255');
		$this->addProperty('mail', 'varchar', '255', '');
		$this->addProperty('provider', 'varchar', '255');
	}
	function createProxy($array = array()) {
		return new AuthExternalProxy($array);
	}
}
class AuthExternalProxy extends DModelProxyArray {
	function create() {
		//dump('Creating authorization (called on login)');
        if (session_status() == 1)
            session_start();
	}
	function update($params = null) {
		//dump('Called to update auth state');
	}
	function read($params = null) {
        if (session_status() == 1)
            session_start();
        if (isset($_SESSION) && isset($_SESSION['id'])) {
            $data = [
                'id'       => $_SESSION['id'],
                'login'    => $_SESSION['login'],
                'mail'     => $_SESSION['mail'],
                'provider' => $_SESSION['provider'],
                'type'     => 'user'
            ];
            return $data;
        }
		//dump('Checking authorization (called everytime to check is an user is logged in)');
	}
	function delete($params = null) {
		//dump('Called on logout');
        session_destroy();
	}
}
?>