<?php
class BlogsCtrl extends DController {
	/**
	 * @var ArticleModel
	 */
	private $model;
	private $rds;

	function __construct() {
		$this->model = new ArticleModel();
		$this->rds = new RDS();
	}

	private function getSQLForParam($name, $values) {
		if ($values !== null) {
			$values = is_array($values) ? implode(',', $values) : $values;
			return "$name IN ($values)";
		}
	}

	public function lists($authorId = null, $tags_cached = null, $tags_admin = null)
	{
		$sql = ['true'];

		if ($t = $this->getSQLForParam('tags_cached', $tags_cached)) $sql[] = $t;
		if ($t = $this->getSQLForParam('authorId', $authorId)) $sql[] = $t;

		$sql = implode(' AND ', $sql);

		$sql .= ' order by id';
		$sql .= ' limit 0,10';

		if($tags_admin !== null)
		{
			$proxy = new DModelProxyDatabaseJoins('articles');
			$t = $this->getSQLForParam(CONFIG::$DB->prefix . 'articles_has_tags.tag_id', $tags_admin);
			$proxy->addJoinedTable('articles_has_tags', $t, 'right');
			$this->model = new ArticleModel($proxy);

		} 
		else
		{
			$this->model = new ArticleModel();
		}

		$collection = new DModelsCollection($this->model);
		$collection->load($sql);
		
		return $collection;
	}

	public function addPost($data)
	{
		$className = get_class($this);

		if($this->rds->is('access.' . $className . '.addPost'))
		{
			try
			{
				$model = (new ArticleModel)->sets($data)
				$_m = $model->create();
			}
			catch(Exception $e)
			{
				echo "ArticleModel don't create. Error code " . $e->getCode();
				return false;
			}

			if($this->rds->is('access.' . $className . '.addTags'))
			{
				// массив с id тегами для присвоения к разделам
				if(is_array($data['has_tags']))
				{
					foreach ($data['has_tags'] as $key => $value)
					{
						$this->addTags($value, $_m->id);
					}
				}
			}
		}
		else
            throw new Exception('you do not have sufficient privileges');
	}

	private function addTags($tag_id, $article_id)
	{
		try
		{
			$ArticlesHasTagsModel = new ArticlesHasTagsModel();
			$ArticlesHasTagsModel->tag_id = $tag_id;
			$ArticlesHasTagsModel->article_id = $article_id;
			$ArticlesHasTagsModel->user_id = $this->rds->userId;
			$ArticlesHasTagsModel->taggeddate = date("Y-m-d H:i:s");
			$ArticlesHasTagsModel->create();
		}
		catch(Exception $e)
		{
			echo "ArticlesHasTagsModel don't create. Error code " . $e->getCode();
			return false;
		}
	}

	/*
	function lists() {
		$collection = new DModelsCollection($this->model);
		$collection->load('1 limit 0,100');
		return $collection;
	}
	*/

	function show($id) {
		return $this->model->load($id);
	}
}

