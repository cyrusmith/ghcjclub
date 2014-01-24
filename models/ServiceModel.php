<?/**
 * @property int $id
 * @property varchar $name
 * @property int $price
 * @property enum $type
 * @property varchar $link
 */
class ServiceModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "10", null)
			->addProperty('name', 'string required')
			->addProperty('price', 'int', "11")
			->addProperty('type', 'enum', 'once,month', 'once')
		;
		$this->addRule('name', 'ne', 'Укажите название услуги', true);
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,name,price,type,link';
		return (new DModelProxyDatabase('services'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'name':
				break;
			case 'price':
				break;
			case 'type':
				break;
			case 'link':
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
			case 'price':
				break;
			case 'type':
				break;
			case 'link':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>