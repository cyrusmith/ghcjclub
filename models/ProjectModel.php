<?/**
 * @property int $id 
 * @property int $creatorId
 * @property varchar $name
 * @property varchar $email
 * @property enum $type
 * @property text $info
 * @property text $infoShort 
 * @property int $totalTracks 
 * @property int $totalNews
 * @property int $status
 * @property datetime $createDate
 * @property varchar $musicRating 
 * @property varchar $musicPlace 
 */
class ProjectModel extends DModelValidated {
	function setup() {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('creatorId', 'int', "11", null)
			->addProperty('name', 'varchar', "255")
			->addProperty('type', 'varchar', "255")
			->addProperty('email', 'varchar', "255")
            ->addProperty('type', 'set', "'clubber','listener','musician','poet','mc', 'promoter', 'photograph','vocalist','guitarist','keyboardist','drummer'")
			->addProperty('info', 'text', "")
			->addProperty('infoShort', 'text', "")
			->addProperty('totalTracks', 'int', "11", '0')
			->addProperty('totalNews', 'int', "11", '0')
			->addProperty('status', 'int', "11", '0')
			->addProperty('createDate', 'datetime', "")
			->addProperty('musicRating', 'varchar', "45", null)
			->addProperty('musicPlace', 'varchar', "45", null)
            ->addProperty('config', 'blob', "")
            ->addProperty('city_id', 'int', "11")
            ->addProperty('country_id', 'int', "11")
		;
		$this->createProxy();
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,creatorId,name,email,info,infoShort,totalTracks,totalNews,createDate,musicRating,musicPlace,config';
		$this->proxy = (new DModelProxyDatabase('projects'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}

	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			/*case 'creatorId':
                if (!empty($value))
                    $value = dbSelect('rds_users', 'id,name', "id = $value", DB_SELECT_OBJ);
                else {
                    $value = new stdClass();
                    $value->id   = '';
                    $value->name = '';
                }
				break;*/
			case 'name':
				break;
			case 'email':
				break;
			case 'type':
				break;
			case 'info':
                if (!isset($_REQUEST['edit']))
                    $value = (new WikiParser)->wikiedValue($value);
				break;
			case 'infoShort':
                if (!isset($_REQUEST['edit']))
                    $value = (new WikiParser)->wikiedValue($value);
				break;
			case 'totalTracks':
				break;
			case 'totalNews':
				break;
			case 'createDate':
				break;
			case 'status':
				break;
			case 'musicRating':
				break;
			case 'musicPlace':
				break;
			case 'config':
                if (empty($value)) {
                    $value = new stdClass();
                    $value->mainStyle = '0';

                    $value->phone = '';
                    $value->phoneVisible = FALSE;

                    $value->skype = '';
                    $value->skypeVisible = FALSE;

                    $value->icq = '';
                    $value->icqVisible = FALSE;

                    $value->site = '';
                    $value->siteVisible = FALSE;

                    $value->vkontakte = '';
                    $value->vkontakteVisible = FALSE;
                }
                if (!is_object($value))
                    $value = unserialize($value);
                if (!isset($value->mainStyle))
                    $value->mainStyle = 'N/A';
                else $value->mainStyle = (new MusicStyleModel)->load('id = '.$value->mainStyle)->value;
				break;
            /*case 'city_id':
                if (!empty($value))
                    $value = dbSelect('cities', 'id,name', "id = $value", DB_SELECT_OBJ);
                else {
                    $value = new stdClass();
                    $value->id   = '';
                    $value->name = '';
                }
                break;
            case 'country_id':
                if (!empty($value))
                    $value = dbSelect('countries', 'id,name', "id = $value", DB_SELECT_OBJ);
                else {
                    $value = new stdClass();
                    $value->id   = '';
                    $value->name = '';
                }
                break;
            */
		}
		return $value;
	}

	public function setterConversions($field, $value) {
		switch ($field) {
			case 'creatorId':
				break;
			case 'name':
				break;
			case 'email':
				break;
            case 'type':
                break;
			case 'info':
				break;
			case 'infoShort':
				break;
			case 'totalTracks':
				break;
			case 'totalNews':
				break;
			case 'status':
				break;
			case 'createDate':
				break;
			case 'musicRating':
				break;
			case 'musicPlace':
				break;
			case 'config':
                if (is_object($value))
                    $value = serialize($value);
				break;
            case 'city_id':
                break;
            case 'country_id':
                break;
		}
		return parent::setterConversions($field, $value);
	}
}
?>