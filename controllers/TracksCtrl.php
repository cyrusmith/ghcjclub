<?php
class TracksCtrl extends DController {
	function lists() {
		$collection = new DModelsCollection('TrackModel');
		$collection->load('1 limit 0,100');
		return $collection;
	}
	private function getSQLForParam($name, $values) {
		if ($values !== null) {
			$values = is_array($values) ? implode(',', $values) : $values;
			return "$name IN ($values)";
		}
	}
	function listsFiltered($projectId = null, $albumId = null, $topId = null) {
		$collection = new DModelsCollection('TrackModel');
		/*
		 * сформировать SQL запрос на основании переданных условий фильтра
		 */
		$sql = ['true'];
		/*
		 * проект
		 */
		if ($t = $this->getSQLForParam('projectId', $projectId)) $sql[] = $t;
		if ($t = $this->getSQLForParam('album_id', $albumId)) $sql[] = $t;
		if ($t = $this->getSQLForParam('top_id', $topId)) $sql[] = $t;

		$sql = implode(' AND ', $sql);
		/*
		 * order by
		 */
		$sql .= ' order by name';
		/*
		 * limit
		 */
		$sql .= ' limit 0,100';
		$collection->load($sql);
		return $collection;
	}
	function listsPromo() {
		$collection = new DModelsCollection('TrackModel');
		$collection->load('1 limit 0,5');
		return $collection;
	}
	function show($id) {
		$model = DI::create('TrackModel', ['loadWith' => $id]);
		return $model;
	}
}
