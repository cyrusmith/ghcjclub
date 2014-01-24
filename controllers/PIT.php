<?
class PIT {
    /**
     * Добавление события увеличения пита
     * @param $trackId идентификатор трека
     * @param $value значение увеличения
     */
    static function add($trackId, $value){
		$existedValue = (new PiTModel)->load("track_id = $trackId AND DATE(date) = CURDATE()");

        // Если запись есть, то просто добавляем значение
        if(isset($existedValue->track_id)) {
            if (!$existedValue->pit) $existedValue->pit = 0;
            $existedValue->pit += $value;
            $existedValue->save();
        } else { // Иначе, создаём новую запись
            $existedValue->pit      =  $value;
            $existedValue->track_id = $trackId;
            $existedValue->create();
        }
	}

    /**
     * Увеличение PiT (раз в сутки)
     */
    static function capitalize(){
		dbQuery("LOCK TABLES cjclub_pit_inprogress WRITE, cjclub_tracks WRITE");

        // Формирование списка треков, PiT которых нужно обновить
        $pits = new DModelsCollection('PiTModel');
        $proxy = new DModelProxyDatabaseJoins('pit_inprogress');
        $proxy->addJoinedTable('tracks', 'tracks.id = pit_inprogress.track_id  AND DATE(pit_inprogress.date) = CURDATE()');
        $pits->getModelInstance()->setProxy($proxy);
        $pits->load();

        // Обновление PiT
        foreach ($pits as $pit) {
            $track = (new Track)->getShowModel($pit->track_id);
            $track += $pit->pit;
            $track->save();
        }

		dbQuery("UNLOCK TABLES");
	}
}
?>