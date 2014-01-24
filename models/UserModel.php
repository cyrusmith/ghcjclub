<?/**
 * @property int $id 
 * @property int $city_id 
 * @property int $country_id
 * @property int $type_id
 * @property set $extraroles 
 * @property varchar $login 
 * @property varchar $password 
 * @property enum $frozen 
 * @property char $autologin 
 * @property varchar $name 
 * @property enum $hiderealname 
 * @property varchar $email 
 * @property enum $emailchecked
 * @property varchar $provider
 * @property float $balance 
 * @property float $moneyturnover 
 * @property datetime $regdate
 * @property int $forum_user_id 
 * @property datetime $lastdateuse 
 * @property datetime $previouslogindate
 * @property text $comments
 * @property int $skin_id 
 * @property char $ip 
 * @property enum $critic
 * @property float $rating 
 * @property enum $hasAvatar
 * @property text $moder_for 
 * @property int $adv_newuser 
 * @property int $adv_global 
 * @property int $adv_required 
 * @property int $rating_place
 * @property int $rating_place_prev 
 * @property int $sending_num 
 * @property enum $rssMail 
 * @property int $unreadMessages 
 * @property enum $isOnline 
 * @property enum $track_sharing 
 * @property int $track_sharing_balance 
 * @property enum $quality_play 
 * @property enum $comments_blocked 
 * @property enum $goExternalUrl 
 * @property enum $no_tags 
 * @property blob $config 
 * @property enum $gtype
 */
class UserModel extends DModelValidated {
	protected $rds;
	protected $db;
	function setup() {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('type_id', 'int', "11")
			->addProperty('extraroles', 'set', "'vip','labeltracks','moderator','uploader'")
			->addProperty('login', 'varchar', "45")
			->addProperty('password', 'varchar', "45")
			->addProperty('frozen', 'enum', "'yes','no'", 'no')
			->addProperty('autologin', 'char', "32", null)
			->addProperty('name', 'varchar', "255")
			->addProperty('hiderealname', 'enum', "'false','true'", 'false')
			->addProperty('email', 'varchar', "255")
			->addProperty('emailchecked', 'enum', "'false','true'", 'false')
            ->addProperty('provider', 'varchar', "60", '')
			->addProperty('balance', 'float', "")
			->addProperty('moneyturnover', 'float', "")
			->addProperty('regdate', 'datetime', "")
			->addProperty('forum_user_id', 'int', "11", null)
			->addProperty('lastdateuse', 'datetime', "", null)
			->addProperty('previouslogindate', 'datetime', "", null)
			->addProperty('comments', 'text', "", null)
			->addProperty('skin_id', 'int', "11")
			->addProperty('ip', 'char', "15")
			->addProperty('critic', 'enum', "'false','true'", 'false')
			->addProperty('rating', 'float', "")
			->addProperty('hasAvatar', 'enum', "'false','true'", 'false')
			->addProperty('moder_for', 'text', "")
			->addProperty('adv_newuser', 'int', "11")
			->addProperty('adv_global', 'int', "11")
			->addProperty('adv_required', 'int', "11")
			->addProperty('rating_place', 'int', "11")
			->addProperty('rating_place_prev', 'int', "11")
			->addProperty('sending_num', 'int', "11")
			->addProperty('rssMail', 'enum', "'true','false'", 'true')
			->addProperty('unreadMessages', 'int', "10", '0')
			->addProperty('isOnline', 'enum', "'false','true'", 'false')
			->addProperty('track_sharing', 'enum', "'false','true'", null)
			->addProperty('track_sharing_balance', 'int', "10", '0')
			->addProperty('quality_play', 'enum', "'false','true'", 'true')
			->addProperty('comments_blocked', 'enum', "'false','true'", 'false')
			->addProperty('goExternalUrl', 'enum', "'0','1'", '0')
			->addProperty('no_tags', 'enum', "'yes','no'", 'no')
			->addProperty('config', 'blob', "")
			->addProperty('gtype', 'enum', "'male','female'", 'male')
		;
		$this->proxy = $this->createProxy();
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		return new DModelProxyDatabase('rds_users');
	}

	function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'type_id':
                if (!empty($value))
                    //$value = $this->db->select('usertypes', '*', "id = $value", DB_SELECT_OBJ);
				break;
			case 'extraroles':
				break;
			case 'login':
				break;
			case 'password':
				break;
			case 'frozen':
				break;
			case 'autologin':
				break;
			case 'name':
				break;
			case 'hiderealname':
				break;
			case 'email':
				break;
			case 'emailchecked':
				break;
			case 'provider':
				break;
			case 'balance':
				break;
			case 'moneyturnover':
				break;
			case 'regdate':
				break;
			case 'forum_user_id':
				break;
			case 'lastdateuse':
				break;
			case 'previouslogindate':
				break;
			case 'comments':
				break;
			case 'skin_id':
				break;
			case 'ip':
				break;
			case 'critic':
				break;
			case 'rating':
				break;
			case 'hasAvatar':
				break;
			case 'moder_for':
				break;
			case 'adv_newuser':
				break;
			case 'adv_global':
				break;
			case 'adv_required':
				break;
			case 'rating_place':
				break;
			case 'rating_place_prev':
				break;
			case 'sending_num':
				break;
			case 'rssMail':
				break;
			case 'unreadMessages':
				break;
			case 'isOnline':
				break;
			case 'track_sharing':
				break;
			case 'track_sharing_balance':
				break;
			case 'quality_play':
				break;
			case 'comments_blocked':
				break;
			case 'goExternalUrl':
				break;
			case 'no_tags':
				break;
			case 'config':
                if (!is_object($value)) {
                    $value = unserialize($value);
                    if (empty($value))
                        $value = new stdClass();
//                    if (!isset($value->curProjectId) && $this->rds->isLogged)
//                        $value->curProjectId = (new ProjectModel)->load('creatorId = '.$this->rds->userId.' ORDER BY creatorId DESC LIMIT 0,1')->id;
                }
				break;
			case 'gtype':
				break;
		}
		return $value;
	}

	function setterConversions($field, $value) {
		switch ($field) {
			case 'type_id':
				break;
			case 'extraroles':
				break;
			case 'login':
				break;
			case 'password':
				break;
			case 'frozen':
				break;
			case 'autologin':
				break;
			case 'name':
				break;
			case 'hiderealname':
				break;
			case 'email':
				break;
			case 'emailchecked':
				break;
			case 'balance':
				break;
			case 'moneyturnover':
				break;
			case 'regdate':
				break;
			case 'forum_user_id':
				break;
			case 'lastdateuse':
				break;
			case 'previouslogindate':
				break;
			case 'comments':
				break;
			case 'skin_id':
				break;
			case 'ip':
				break;
			case 'critic':
				break;
			case 'rating':
				break;
			case 'hasAvatar':
				break;
			case 'moder_for':
				break;
			case 'adv_newuser':
				break;
			case 'adv_global':
				break;
			case 'adv_required':
				break;
			case 'rating_place':
				break;
			case 'rating_place_prev':
				break;
			case 'sending_num':
				break;
			case 'rssMail':
				break;
			case 'unreadMessages':
				break;
			case 'isOnline':
				break;
			case 'track_sharing':
				break;
			case 'track_sharing_balance':
				break;
			case 'quality_play':
				break;
			case 'comments_blocked':
				break;
			case 'goExternalUrl':
				break;
			case 'no_tags':
				break;
			case 'config':
                if (is_object($value))
                    $value = serialize($value);
				break;
			case 'gtype':
				break;
		}
		return parent::setterConversions($field, $value);
	}
}
?>