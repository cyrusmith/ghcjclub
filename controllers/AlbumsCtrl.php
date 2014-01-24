<?php
class AlbumsCtrl extends DController {
	function lists($projectId) {
		$collection = new DModelsCollection('AlbumModel');
		$collection->load("projectId = $projectId");
		return $collection;
	}
	function show($id) {
		return DI::create('AlbumModel', ['loadWith' => $id]);
	}
}