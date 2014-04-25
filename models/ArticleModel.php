<?/**
 * @property int $id 
 * @property int $projectId
 * @property datetime $public_date 
 * @property text $subject 
 * @property text $shorttext 
 * @property longtext $maintext
 * @property int $sectionId
 * @property int $forum_topic_id 
 * @property int $points 
 * @property int $pointspaid 
 * @property enum $nopoints 
 * @property int $points_minus
 * @property enum $draft_checkbox
 * @property enum $main_blog_checkbox 
 * @property datetime $lastCommentDate 
 * @property enum $comments_blocked 
 * @property text $tags_cached 
 * @property int $authorId 
 */
class ArticleModel extends DModelValidated {

	public function setup()
	{
		
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "10", null)
			->addProperty('projectId', 'int', "11", '0')
			->addProperty('public_date', 'datetime', "", null)
			->addProperty('subject', 'text', "", null)
			->addProperty('shorttext', 'text', "", null)
			->addProperty('maintext', 'longtext', "", null)
            ->addProperty('sectionId', 'int', "11", '0')
			->addProperty('forum_topic_id', 'int', "11", '0')
			->addProperty('points', 'int', "11", '0')
			->addProperty('pointspaid', 'int', "11", '0')
			->addProperty('points_minus', 'int', "11", '0')
			->addProperty('nopoints', 'enum', "'false','true'", 'false')
			->addProperty('draft_checkbox', 'enum', "'false','true'", 'false')
			->addProperty('main_blog_checkbox', 'enum', "'false','true'", 'false')
			->addProperty('lastCommentDate', 'datetime', "")
			->addProperty('comments_blocked', 'enum', "'false','true'", 'false')
			->addProperty('tags_cached', 'text', "")
			->addProperty('authorId', 'int', "11", '0')
		;

	}

	public function __construct($proxy = null)
	{
		$this->setup();
		$this->proxy = $proxy ? $proxy : new DModelProxyDatabase('articles');
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		return new DModelProxyDatabase('articles');
	}

	function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'projectId':
//                if (!empty($value))
//                    $value = dbSelect('projects', 'id,name', "id = $value", DB_SELECT_OBJ);
//                else {
//                    $value = new stdClass();
//                    $value->id   = '';
//                    $value->name = '';
//                }
				break;
			case 'public_date':
				break;
			case 'subject':
				break;
			case 'shorttext':
                if (!isset($_REQUEST['edit']))
//                    if (!RDS::get()->is('admin') || !isset($_REQUEST['action']) || strpos($_REQUEST['action'], 'admin') === FALSE) {
                        $value = (new WikiParser)->trunkSimple($value);
                        $value = (new WikiParser)->wikiedValue($value);
                        $value = preg_replace('/<br ?\/?>/', '&nbsp;', $value, 1);
//                    }
				break;
			case 'maintext':
                if (!isset($_REQUEST['edit']))
                    $value = (new WikiParser)->wikiedValue($value);
				break;
			case 'sectionId':
//                if (!empty($value))
//                    $value = dbSelect('sections', 'id,name', "id = $value", DB_SELECT_OBJ);
//                else {
//                    $value = new stdClass();
//                    $value->id   = '';
//                    $value->name = '';
//                }
				break;
			case 'forum_topic_id':
				break;
			case 'points':
				break;
			case 'pointspaid':
				break;
			case 'nopoints':
				break;
			case 'points_minus':
				break;
			case 'draft_checkbox':
				break;
			case 'main_blog_checkbox':
				break;
			case 'lastCommentDate':
				break;
			case 'comments_blocked':
				break;
			case 'tags_cached':
				break;
		}
		return $value;
	}

	/*
	function setterConversions($field, $value) {
		switch ($field) {
			case 'projectId':
				break;
			case 'public_date':
				break;
			case 'subject':
				break;
			case 'shorttext':
				break;
			case 'maintext':
				break;
			case 'sectionId':
				break;
			case 'forum_topic_id':
				break;
			case 'points':
				break;
			case 'pointspaid':
				break;
			case 'nopoints':
				break;
			case 'points_minus':
				break;
			case 'draft_checkbox':
				break;
			case 'main_blog_checkbox':
				break;
			case 'lastCommentDate':
				break;
			case 'comments_blocked':
				break;
			case 'tags_cached':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>