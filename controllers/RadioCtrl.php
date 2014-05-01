<?php
class RadioCtrl extends DController {
	private $db;

	function __construct() {
		$this->db = ObjectsPool::get('DataBase');
	}

	function getInfo($channel) {
		$number = 1;
		$query = "select T.* from cjclub_radio R, cjclub_tracks T  where played_time IS NOT NULL AND channel_id = $channel AND track_id != 0 AND R.track_id = T.id ORDER BY R.id DESC LIMIT 0,$number";
		$results = $this->db->query($query, DB_SELECT_OBJS);
		if ($results == null) return [];
		return array_shift($results);
	}
}