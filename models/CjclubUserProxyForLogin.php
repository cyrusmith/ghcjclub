<?
/**
 * Прокси для авторизации
 */
class CjclubUserProxyForLogin extends DModelProxyHTTPRequest {
	private $db;

	function __construct() {
		$this->db = ObjectsPool::get('DataBase');
	}

	function create() {
        if (session_status() == 1)
            session_start();
    }
    function read($params = null) {
        if (session_status() == 1)
            session_start();
        $login = filter_var($this->model->login, FILTER_SANITIZE_MAGIC_QUOTES);
        $pswd  = filter_var($this->model->password, FILTER_SANITIZE_MAGIC_QUOTES);
        $user = $this->db->select('rds_users', 'id,type_id,login,password,name', "login = '$login' AND password = MD5('$pswd')", DB_SELECT_OBJ);
        if (empty($user))
            throw new Exception('Неправильный логин и/или пароль!');
        $_SESSION = [
            'user_id'    => $user->id,
            'session_id' => session_id(),
            'ip'         => getIP()
        ];
		$this->db->update('rds_users', array('isOnline' => 'true', 'previouslogindate' => new SQLvar('lastdateuse'), 'lastdateuse' => new SQLvar('NOW()')), "id = {$user->id}");
        return $user;
    }
    function delete($params = null) {
        session_destroy();
    }
}
?>