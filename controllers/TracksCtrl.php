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
	function listsFiltered($projectId = null, $albumId = null, $topId = null, $id = null, $sort = null, $date = null, $style = null) {
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
		if ($t = $this->getSQLForParam('style_id', $style)) $sql[] = $t;
		$sql = implode(' AND ', $sql);

		if($date == "2week")
			$sql .= " and public_date between '" . date('Y-m-d', time() - 86400*14) . "' and '" . date('Y-m-d') . "'";

		/*
		 * order by
		 */
		switch ($sort)
		{
			case 'date':
				$sql .= ' order by public_date desc';	
				break;

			case 'pit':
				$sql .= ' order by pit desc';
				break;
			
			default:
				$sql .= ' order by name';
				break;
		}

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
