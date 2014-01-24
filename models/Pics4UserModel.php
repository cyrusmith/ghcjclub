<?/**
 * @property int $id 
 * @property varchar $name 
 * @property text $description 
 */
class Pics4UserModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "10", null)
			->addProperty('name', 'varchar', "50")
			->addProperty('description', 'text', "", null)
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,name,description';
		return (new DModelProxyDatabase('pics4users'))
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
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>