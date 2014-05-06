<?php
class RadioCtrl extends DController {
	private $db;

	function __construct() {
		$this->db = ObjectsPool::get('DataBase');
	}

	function getInfo($channel) {
		$number = 1;
		$query = "select R.track_id from cjclub_radio R where played_time IS NOT NULL AND channel_id = $channel AND track_id != 0 ORDER BY R.id DESC LIMIT 0,$number";
		$results = $this->db->query($query, DB_SELECT_COL);
		if ($results == null) return [];
		$tracksCtrl = new TracksCtrl();
		return $tracksCtrl->listsFiltered(null, null, null, $results);
	}
}