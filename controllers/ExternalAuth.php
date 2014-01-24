<?
class ExternalAuth extends DController {
    /**
     * Авторизация
     * @return HandlingResult
     */
    function login() {
        $provider  = filter_var($_REQUEST['provider'], FILTER_SANITIZE_MAGIC_QUOTES);

        if (!empty($provider)) {
            $hybridauth = $this->connect($provider);
            if (session_status() == 1)
                session_start();
            $_SESSION['provider'] = $provider;
            $params = array();
            if( $provider == "OpenID" ){
                $params["openid_identifier"] = @ $_REQUEST["openid_identifier"];
            }

            $in = $hybridauth->isConnectedWith($provider);
            if ($in) { // Случай повторного захода
                $adapter = $hybridauth->authenticate( $provider, $params );
                $user_profile = $adapter->getUserProfile();
            } else {
                $adapter = Hybrid_Auth::setup( $provider, $params );
                $adapter->login();
                $user_profile = $adapter->getUserProfile();
            }
            $this->saveProfile($provider, $user_profile);
        }
		$handlingResult = new HandlingResult();
		return $handlingResult->setRedirect('::reload');
	}

	/**
	 * Деавторизация.
	 * Чтобы переопределить результат действия (HandlingResult),
	 * надо повесить обработчик на onLogout
	 */
	function logout() {
        $model = new AuthExternalModel();
        $provider = $model->get('provider');
        $params   = array();
        if ($provider == 'OpenID')
            $params["openid_identifier"] = $model->get('login');
        $hybridauth = self::connect($provider);
        $in = Hybrid_Auth::isConnectedWith($provider);
        if ($in) {
            $adapter = Hybrid_Auth::setup( $provider, $params );
            $adapter->logout();
        }
        $model->delete();
        $handlingResult = new HandlingResult();
        return $handlingResult->setRedirect('::reload');
	}

    /**
     * Получение объекта соединения с сервисом
     * @param string $provider имя сервиса согласно CONFIG::$HYBRIDAUTH_VARS
     * @return object объект соединения с сервисом
     */
    static function connect($provider) {
        try {
            $hybridauth = new Hybrid_Auth( CONFIG::$HYBRIDAUTH_VARS );
        } // if sometin bad happen
        catch( Exception $e ) {
            $message = "";

            switch( $e->getCode() ){
                case 0 : $message = "Unspecified error."; break;
                case 1 : $message = "Hybriauth configuration error."; break;
                case 2 : $message = "Provider not properly configured."; break;
                case 3 : $message = "Unknown or disabled provider."; break;
                case 4 : $message = "Missing provider application credentials."; break;
                case 5 : $message = "Authentification failed. The user has canceled the authentication or the provider refused the connection."; break;

                default: $message = "Unspecified error!";
            }
        }

        return $hybridauth;
    }

    /**
     * Обработчик данных, полученных от провайдера
     * (сюда через роутер перенаправляются пакеты от провайдера)
     */
    function reload() {

        if (session_status() == 1)
            session_start();

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ExternalAuth/login') {
            Hybrid_Auth::initialize(CONFIG::$HYBRIDAUTH_VARS);
            $provider = $_SESSION['provider'];

            $hybridauth   = $this->connect($provider);
            $adapter      = $hybridauth->authenticate($provider);
            $user_profile = $adapter->getUserProfile();

            $this->saveProfile($provider, $user_profile);
        }
        header('Location: '.CONFIG::$PATH_URL);
    }

    /**
     * Сохранение полученного профиля с последующей регистрацией на сайте
     * @param string $provider идентификатор провайдера
     * @param object $profile  профиль пользователя, полученный от провайдера
     */
    function saveProfile($provider, $profile) {
        $model = (new CjclubUserModel)->maskPropertyList('id,name,email,emailchecked,login,password,provider,type_id');
        $model->login    = (!empty($profile->displayName)) ? $profile->displayName : $profile->identifier;
        $model->name     = (!empty($profile->firstName)) ? $profile->firstName : $model->login;
        $model->email    = $profile->email;
        $model->provider = $provider;
        $model->type_id  = 2;
        $model->password = md5('');
        $model->emailchecked = 'true';
        $model->create();
        $model->password = '';
        (new CjclubUser)->doLogin($model);
    }
}
?>
