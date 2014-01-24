<?/**
 * @property int $id 
 * @property varchar $name 
 * @property varchar $subject 
 * @property int $relOrder
 * @property int $parentId
 */
class SectionModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "10", null)
			->addProperty('name', 'varchar', "60")
			->addProperty('subject', 'varchar', "255")
			->addProperty('type', 'enum', "'article','advert'")
			->addProperty('relOrder', 'int', "11", null)
			->addProperty('parentId', 'int', "11", null)
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,name,subject,type,relOrder,parentId';
		return (new DModelProxyDatabase('sections'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'name':
				break;
			case 'subject':
				break;
			case 'relOrder':
				break;
			case 'type':
				break;
			case 'parentId':
				break;
		}
		return $value;
	}
	*/

	public function setterConversions($field, $value) {
		switch ($field) {
			case 'name':
				break;
			case 'subject':
				break;
			case 'relOrder':
				break;
			case 'type':
				break;
			case 'parentId':
                if (empty($value))
                    $value = 0;
				break;
		}
		return parent::setterConversions($field, $value);
	}
}
?>