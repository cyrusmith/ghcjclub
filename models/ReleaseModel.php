<?/**
 * @property int $id 
 * @property int $label_id 
 * @property varchar $name 
 * @property enum $type 
 * @property text $descr 
 * @property datetime $publicdate 
 * @property enum $state 
 * @property text $tracklist 
 * @property set $options 
 * @property int $tracksToReview 
 * @property int $filesize 
 * @property int $timelength 
 */
class ReleaseModel extends DModelValidated {
	function __construct() {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('label_id', 'int', "11")
			->addProperty('name', 'varchar', "255")
			->addProperty('type', 'enum', "'cjclub','outsource','file'", 'cjclub')
			->addProperty('descr', 'text', "")
			->addProperty('publicdate', 'datetime', "")
			->addProperty('state', 'enum', "'draft','opened','sorting','released'", 'draft')
			->addProperty('tracklist', 'text', "", null)
			->addProperty('options', 'set', "'download','exclusive'")
			->addProperty('tracksToReview', 'int', "11", '0')
			->addProperty('filesize', 'int', "11", null)
			->addProperty('timelength', 'int', "11", null)
		;
		parent::__construct();
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		return new DModelProxyDatabase('releases');
	}
	/*
	protected function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'label_id':
				break;
			case 'name':
				break;
			case 'type':
				break;
			case 'descr':
				break;
			case 'publicdate':
				break;
			case 'state':
				break;
			case 'tracklist':
				break;
			case 'options':
				break;
			case 'tracksToReview':
				break;
			case 'filesize':
				break;
			case 'timelength':
				break;
		}
		return $value;
	}
	*/
	/*
	protected function setterConversions($field, $value) {
		switch ($field) {
			case 'label_id':
				break;
			case 'name':
				break;
			case 'type':
				break;
			case 'descr':
				break;
			case 'publicdate':
				break;
			case 'state':
				break;
			case 'tracklist':
				break;
			case 'options':
				break;
			case 'tracksToReview':
				break;
			case 'filesize':
				break;
			case 'timelength':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>