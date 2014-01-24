<?/**
 * @property int $id 
 * @property varchar $name 
 * @property char $code 
 * @property text $template 
 * @property varchar $group 
 */
class EventTypeModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('name', 'varchar', "45")
			->addProperty('code', 'char', "20")
			->addProperty('template', 'text', "")
			->addProperty('group', 'varchar', "45")
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,name,code,template,group';
		return (new DModelProxyDatabase('eventTypes'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'name':
				break;
			case 'code':
				break;
			case 'template':
				break;
			case 'group':
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
			case 'code':
				break;
			case 'template':
				break;
			case 'group':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>