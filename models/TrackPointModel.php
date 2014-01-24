<?/**
 * @property int $userId 
 * @property int $trackId 
 * @property timestamp $date 
 */
class TrackPointModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'trackId';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('userId', 'int', "10")
			->addProperty('trackId', 'int', "11")
			->addProperty('date', 'timestamp', "", 'CURRENT_TIMESTAMP')
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'userId,trackId,date';
		return (new DModelProxyDatabase('track_has_points'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {

			case 'userId':
				break;

			case 'trackId':
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
			case 'userId':
				break;

			case 'trackId':
				break;

    		case 'date':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>