<?/**
 * @property int $track_id 
 * @property float $pit 
 */
class PiTModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'track_id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('track_id', 'int', "11", null)
			->addProperty('pit', 'float', "")
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'track_id,pit';
		return (new DModelProxyDatabase('pit_inprogress'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'pit':
				break;
		}
		return $value;
	}
	*/
	/*
	public function setterConversions($field, $value) {
		switch ($field) {
			case 'pit':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>