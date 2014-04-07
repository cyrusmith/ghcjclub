<?php
class TracksCtrl extends DController {
	function lists() {
		return $this->listsFiltered();
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
	function listsFiltered($projectId = null, $albumId = null, $topId = null, $id = null) {
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
		if ($t = $this->getSQLForParam('id', $id)) $sql[] = $t;
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
		//todo выдача промо-треков
		$collection = new DModelsCollection('TrackModel');
		$collection->load('1 limit 0,5');
		return $collection;
	}
	function listsBest() {
		//todo выдача лучших треков
		$collection = new DModelsCollection('TrackModel');
		$collection->load('1 order by pit desc limit 0,5');
		return $collection;
	}
	function listsLatest() {
		$collection = new DModelsCollection('TrackModel');
		$collection->load('1 order by public_date DESC limit 0,5');
		return $collection;
	}
	function show($id) {
		$model = DI::create('TrackModel', ['loadWith' => $id]);
		return $model;
	}
}
