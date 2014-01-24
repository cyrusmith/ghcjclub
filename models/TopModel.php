<?/**
 * @property int $id 
 * @property varchar $name 
 * @property varchar $href 
 */
class TopModel extends DModelValidated {
	function __construct() {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('name', 'varchar', "255", null)
			->addProperty('href', 'varchar', "255", null)
		;
		parent::__construct();
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		return new DModelProxyDatabase('tops');
	}
	/*
	protected function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'name':
				break;
			case 'href':
				break;
		}
		return $value;
	}
	*/
	/*
	protected function setterConversions($field, $value) {
		switch ($field) {
			case 'name':
				break;
			case 'href':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>