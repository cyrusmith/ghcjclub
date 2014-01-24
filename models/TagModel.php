<?/**
 * @property int $id 
 * @property varchar $value 
 * @property varchar $code 
 * @property enum $hidden 
 * @property enum $system 
 */
class TagModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "10", null)
			->addProperty('value', 'varchar', "255", null)
			->addProperty('code', 'varchar', "30", null)
			->addProperty('hidden', 'enum', "'false','true'", 'false')
			->addProperty('system', 'enum', "'false','true'", 'false')
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,value,code,hidden,system';
		return (new DModelProxyDatabase('tags'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'value':
				break;
			case 'code':
				break;
			case 'hidden':
				break;
			case 'system':
				break;
		}
		return $value;
	}
	*/
	/*
	public function setterConversions($field, $value) {
		switch ($field) {
			case 'value':
				break;
			case 'code':
				break;
			case 'hidden':
				break;
			case 'system':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>