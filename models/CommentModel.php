<?/**
 * @property int $id 
 * @property int $authorId
 * @property datetime $datewritten 
 * @property text $message 
 * @property int $object_id 
 * @property enum $object_type 
 * @property int $rating 
 * @property enum $track_sharing 
 * @property text $complaint 
 * @property int $com_to_com 
 * @property int $complaint_author_id 
 * @property datetime $complaint_date 
 * @property enum $status 
 */
class CommentModel extends DModelValidated {
	private $db;
	function __construct() {
		$this->db = ObjectsPool::get('DataBase');
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('authorId', 'int', "11")
			->addProperty('datewritten', 'datetime', "")
			->addProperty('message', 'text', "")
			->addProperty('object_id', 'int', "11")
			->addProperty('object_type', 'enum', "'track','article','rds_user','group','photoalbumfile'")
			->addProperty('rating', 'int', "11")
			->addProperty('track_sharing', 'enum', "'false','true'", 'false')
			->addProperty('complaint', 'text', "", null)
			->addProperty('com_to_com', 'int', "10", '0')
			->addProperty('complaint_author_id', 'int', "10", null)
			->addProperty('complaint_date', 'datetime', "", null)
			->addProperty('status', 'enum', "'active','deleted','recovered'", 'active')
		;
		parent::__construct();
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,authorId,datewritten,message,object_id,object_type,rating,track_sharing,complaint,com_to_com,complaint_author_id,complaint_date,status';
		return (new DModelProxyDatabase('comments'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}

	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'authorId':
                if (!empty($value))
                    $value = $this->db->select('rds_users', 'id,name', "id = $value", DB_SELECT_OBJ);
                if (empty($value)) {
                    $value = new stdClass();
                    $value->id   = '';
                    $value->name = '';
                }
				break;
			case 'datewritten':
				break;
			case 'message':
				break;
			case 'object_id':
				break;
			case 'object_type':
				break;
			case 'rating':
				break;
			case 'track_sharing':
				break;
			case 'complaint':
				break;
			case 'com_to_com':
				break;
			case 'complaint_author_id':
				break;
			case 'complaint_date':
				break;
			case 'status':
				break;
		}
		return $value;
	}


	public function setterConversions($field, $value) {
		switch ($field) {
			case 'authorId':
				break;
			case 'datewritten':
                if (empty($value))
                    $value = date('Y-m-d');
				break;
			case 'message':
				break;
			case 'object_id':
				break;
			case 'object_type':
				break;
			case 'rating':
				break;
			case 'track_sharing':
				break;
			case 'complaint':
				break;
			case 'com_to_com':
				break;
			case 'complaint_author_id':
				break;
			case 'complaint_date':
				break;
			case 'status':
				break;
		}
		return parent::setterConversions($field, $value);
	}
}
?>