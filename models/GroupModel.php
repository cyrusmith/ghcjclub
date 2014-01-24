<?/**
 * @property int $id 
 * @property varchar $title 
 * @property tinyint $public 
 * @property tinyint $hide 
 * @property datetime $creation_date 
 * @property int $author_id 
 * @property text $about 
 * @property varchar $short_about 
 * @property text $news 
 * @property int $dr 
 */
class GroupModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('title', 'varchar', "256")
			->addProperty('public', 'tinyint', "4")
			->addProperty('hide', 'tinyint', "4")
			->addProperty('creation_date', 'datetime', "")
			->addProperty('author_id', 'int', "11")
			->addProperty('about', 'text', "")
			->addProperty('short_about', 'varchar', "1024")
			->addProperty('news', 'text', "")
			->addProperty('dr', 'int', "6")
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,title,public,hide,creation_date,author_id,about,short_about,news,dr';
		return (new DModelProxyDatabase('groups'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'title':
				break;
			case 'public':
				break;
			case 'hide':
				break;
			case 'creation_date':
				break;
			case 'author_id':
				break;
			case 'about':
				break;
			case 'short_about':
				break;
			case 'news':
				break;
			case 'dr':
				break;
		}
		return $value;
	}
	*/
	/*
	public function setterConversions($field, $value) {
		switch ($field) {
			case 'title':
				break;
			case 'public':
				break;
			case 'hide':
				break;
			case 'creation_date':
				break;
			case 'author_id':
				break;
			case 'about':
				break;
			case 'short_about':
				break;
			case 'news':
				break;
			case 'dr':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>