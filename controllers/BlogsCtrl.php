<?php
class BlogsCtrl extends DController {
	function lists() {
		$collection = new DModelsCollection('ArticleModel');
		$collection->load('1 limit 0,100');
		return $collection;
	}
	function show($id) {
		$model = DI::create('ArticleModel', ['loadWith' => $id]);
		return $model;
	}
}
