<?/**
 * @property int $id 
 * @property varchar $name 
 * @property varchar $path 
 */
class JingleModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "10", null)
			->addProperty('name', 'varchar', "255")
			->addProperty('path', 'varchar', "255")
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,name,path';
		return (new DModelProxyDatabase('jingles'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'name':
				break;
			case 'path':
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
			case 'path':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>