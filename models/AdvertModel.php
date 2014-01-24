<?/**
 * @property int $id 
 * @property varchar $name 
 * @property text $text 
 * @property timestamp $date 
 * @property int $authorId 
 * @property int $sectionId 
 */
class AdvertModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('name', 'varchar', "255")
			->addProperty('text', 'text', "")
			->addProperty('date', 'timestamp', "", 'CURRENT_TIMESTAMP')
			->addProperty('authorId', 'int', "11")
			->addProperty('sectionId', 'int', "11")
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,name,text,date,authorId,sectionId';
		return (new DModelProxyDatabase('adverts'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'name':
				break;
			case 'text':
				break;
			case 'date':
				break;
			case 'authorId':
				break;
			case 'sectionId':
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
			case 'text':
				break;
			case 'date':
				break;
			case 'authorId':
				break;
			case 'sectionId':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>