<?php
class ProjectsCtrl extends DController {
	function lists() {
		$collection = new DModelsCollection('ProjectModel');
		$collection->load('1 limit 0,100');
		return $collection;
	}
	function show($id) {
		$model = DI::create('ProjectModel', ['loadWith' => $id]);
		return $model;
	}
}