<?/**
 * @property int $id 
 * @property int $country_id 
 * @property varchar $name 
 */
class CityModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "10", null)
			->addProperty('country_id', 'int', "10")
			->addProperty('name', 'varchar', "255", null)
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,country_id,name';
		return (new DModelProxyDatabase('cities'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'country_id':
				break;
			case 'name':
				break;
		}
		return $value;
	}
	*/
	/*
	public function setterConversions($field, $value) {
		switch ($field) {
			case 'country_id':
				break;
			case 'name':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>