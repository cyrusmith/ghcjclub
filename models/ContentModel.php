<?/**
 * @property int $id 
 * @property text $content 
 * @property int $page_id 
 * @property varchar $label 
 */
class ContentModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('content', 'text', "")
			->addProperty('page_id', 'int', "11")
			->addProperty('label', 'varchar', "45", null)
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,content,page_id,label';
		return (new DModelProxyDatabase('content'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'content':
				break;
			case 'page_id':
				break;
			case 'label':
				break;
		}
		return $value;
	}
	*/
	/*
	public function setterConversions($field, $value) {
		switch ($field) {
			case 'content':
				break;
			case 'page_id':
				break;
			case 'label':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>