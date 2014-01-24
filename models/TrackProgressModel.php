<?/**
 * @property int $id 
 * @property int $trackId 
 * @property int $userId 
 * @property varchar $ip 
 * @property enum $action 
 * @property timestamp $date 
 */
class TrackProgressModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('trackId', 'int', "11")
			->addProperty('userId', 'int', "11", '0')
			->addProperty('ip', 'varchar', "18", null)
			->addProperty('action', 'enum', "'download','listen'", null)
			->addProperty('date', 'timestamp', "", null)
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,trackId,userId,ip,action,date';
		return (new DModelProxyDatabase('tracks_progress'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'trackId':
				break;
			case 'userId':
				break;
			case 'ip':
				break;
			case 'action':
				break;
			case 'date':
				break;
		}
		return $value;
	}
	*/
	/*
	public function setterConversions($field, $value) {
		switch ($field) {
			case 'trackId':
				break;
			case 'userId':
				break;
			case 'ip':
				break;
			case 'action':
				break;
			case 'date':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>