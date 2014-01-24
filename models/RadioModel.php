<?/**
 * @property int $radio_id 
 * @property int $id 
 * @property int $track_id 
 * @property int $channel_id 
 * @property int $list_id 
 * @property datetime $played_time 
 * @property varchar $name 
 * @property varchar $filepath 
 */
class RadioModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'radio_id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('radio_id', 'int', "11", null)
			->addProperty('id', 'int', "10")
			->addProperty('track_id', 'int', "10")
			->addProperty('channel_id', 'int', "10", '0')
			->addProperty('list_id', 'int', "10")
			->addProperty('played_time', 'datetime', "", null)
			->addProperty('name', 'varchar', "255", null)
			->addProperty('filepath', 'varchar', "255", null)
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'radio_id,id,track_id,channel_id,list_id,played_time,name,filepath';
		return (new DModelProxyDatabase('radio'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'id':
				break;
			case 'track_id':
				break;
			case 'channel_id':
				break;
			case 'list_id':
				break;
			case 'played_time':
				break;
			case 'name':
				break;
			case 'filepath':
				break;
		}
		return $value;
	}
	*/
	/*
	public function setterConversions($field, $value) {
		switch ($field) {
			case 'id':
				break;
			case 'track_id':
				break;
			case 'channel_id':
				break;
			case 'list_id':
				break;
			case 'played_time':
				break;
			case 'name':
				break;
			case 'filepath':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>