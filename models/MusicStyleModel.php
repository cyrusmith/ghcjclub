<?/**
 * @property int $id 
 * @property int $parent_id 
 * @property varchar $value 
 * @property int $tracks 
 */
class MusicStyleModel extends DModelValidated {
	function __construct() {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "10", null)
			->addProperty('parent_id', 'int', "10")
			->addProperty('value', 'varchar', "255", null)
			->addProperty('tracks', 'int', "11", '0')
		;
		parent::__construct();
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,parent_id,value,tracks';
		return (new DModelProxyDatabase('musicstyles'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'parent_id':
				break;
			case 'value':
				break;
			case 'tracks':
				break;
		}
		return $value;
	}
	*/
	/*
	public function setterConversions($field, $value) {
		switch ($field) {
			case 'parent_id':
				break;
			case 'value':
				break;
			case 'tracks':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>