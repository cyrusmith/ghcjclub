<?/**
 * @property int $tag_id 
 * @property int $article_id 
 * @property int $user_id 
 * @property datetime $taggeddate 
 */
class ArticlesHasTagsModel extends DModelValidated {
	function setup() {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('tag_id', 'int', "11", null)
			->addProperty('article_id', 'int', "11")
			->addProperty('user_id', 'int', "", null)
			->addProperty('taggeddate', 'datetime', "", null)
		;
		$this->proxy = new DModelProxyDatabase('articles_has_tags');
	}

	function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		return $value;
	}


}
?>