<?/**
 * @property int $id 
 * @property int $projectId 
 * @property datetime $date 
 * @property varchar $name 
 * @property text $descr 
 * @property int $tracks 
 * @property int $userOrder 
 */
class AlbumModel extends DModelValidated {
	function setup() {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('projectId', 'int', "11")
			->addProperty('date', 'datetime', "", null)
			->addProperty('name', 'varchar', "255", null)
			->addProperty('descr', 'text', "", null)
			->addProperty('tracks', 'int', "11", '0')
			->addProperty('userOrder', 'int', "11")
		;
		$this->proxy = new DModelProxyDatabase('albums');
	}
	function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			/*case 'projectId':
                if (!empty($value))
                    $value = dbSelect('projects', 'id,name', "id = $value", DB_SELECT_OBJ);
                else {
                    $value = new stdClass();
                    $value->id   = '';
                    $value->name = '';
                }
				break;
			*/
			case 'date':
                if (empty($value))
                    $value = date('Y-m-d H:i:s');
				break;
			case 'name':
				break;
			case 'descr':
                if (!isset($_REQUEST['edit']))
                    $value = (new WikiParser)->wikiedValue($value);
				break;
			case 'tracks':
				break;
			case 'userOrder':
				break;
		}
		return $value;
	}


	function setterConversions($field, $value) {
		switch ($field) {
			case 'projectId':
				break;
			case 'date':
				break;
			case 'name':
				break;
			case 'descr':
				break;
			case 'tracks':
				break;
			case 'userOrder':
				break;
		}
		return parent::setterConversions($field, $value);
	}
}
?>