<?/**
 * @property int $id 
 * @property varchar $name 
 * @property varchar $description 
 * @property float $value 
 */
class RatingModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('name', 'varchar', "255")
			->addProperty('description', 'varchar', "255")
			->addProperty('value', 'float', "", null)
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,name,description,value';
		return (new DModelProxyDatabase('rating_options'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'name':
				break;
			case 'description':
				break;
			case 'value':
				break;
		}
		return $value;
	}
	*/
	/*
	public function setterConversions($field, $value) {
		switch ($field) {
			case 'name':
				break;
			case 'description':
				break;
			case 'value':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>