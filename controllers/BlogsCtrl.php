<?php
class BlogsCtrl extends DController {
	/**
	 * @var ArticleModel
	 */
	private $model;
	function __construct() {
		$this->model = new ArticleModel();
	}

	function lists() {
		$collection = new DModelsCollection($this->model);
		$collection->load('1 limit 0,100');
		return $collection;
	}
	function show($id) {
		return $this->model->load($id);
	}
}
