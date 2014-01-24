<?
class TopsCtrl extends DController {
	function lists(){
//        $limit = Registry::get('topdeep');
//        $this->customize->conditions = "1 LIMIT 0,$limit";
		return (new DModelsCollection('TopModel'))->load('true');
	}

	/*
	function getShowModelToFormMap(DModel $model) {
		$map = parent::getShowModelToFormMap($model);
		//$map['currency_id'] = 'Selector';
		return $map;
	}
	*/
	/*
	function getShowModel($id) {
		return parent::getShowModel($id);
	}
	*/
	/*
	function getShowForm($model) {
		$form = parent::getShowForm($model);
		$this->setView(VC_DTPL, 'show_template.php');
		return $form;
	}
	*/
	/*
	function show($id = null){
 		//$this->customize->fields = 'id,name,href';
		//$this->customize->conditions = "user_id = {$this->RDS->userId}";
		$data = parent::show($id);
		return $data;
	}
	*/
	/*
	function delete($id = null){
		//$this->customize->conditions = "user_id = {$this->RDS->userId}";
		return parent::delete($id);
	}
	*/
    /**
     * Получение списка треков для заданного топа
     * @param $topId идентификатор топа
     * @param $all выбрать треки по всем топам, вне зависимости от наличия места в топе
     * @param $limit количество треков (либо число, либо строка типа "LIMIT a,b", где a и b - натуральные числа)
     * @return DModelsCollection получение коллекции треков заданного топа
     */
    function getTracks($topId = 0, $all = FALSE, $limit = 0) {
        $tracks = new DModelsCollection('TrackModel');
        if (!$all) $where = 'AND placeintop > 0';
        else $where = 'AND placeintop >= 0';

        if (is_numeric($limit))
            $limit = 'LIMIT 0,'.Registry::get('topdeep');
        elseif (empty($limit))
            $limit = '';

        // для api
        if (isset($_REQUEST['action']) && strpos($_REQUEST['action'], 'api/') !== FALSE) {
            $name    = filter_var($_REQUEST['style'], FILTER_SANITIZE_MAGIC_QUOTES);
            $offset  = isset($_REQUEST['offset']) ? filter_var($_REQUEST['offset'], FILTER_SANITIZE_NUMBER_INT) : 0;
            $topId   = (new TopModel)->load("name LIKE '$name'")->id;
            $tracks->getModelInstance()->maskPropertyList('id,placeintop');
            $tracks->load("top_id = $topId AND type = 'intop' AND  published='true' AND placeintop >= $offset ORDER BY placeintop, points DESC");
            $out = $tracks->getAsStdObjects();
            foreach ($out as $track) {
                $uid = (new ProjectModel)->load('id = '.(new TrackModel)->load("id = {$track->id}")->projectId->id)->creatorId->id;
                $track->avatar = (new File)->getPath($track->id, $uid, 'trackPic', TRUE);
                $track->listen = (new File)->getListenLink($track->id);
            }
            echo JSONserialize($out);
            exit();
        } else // остальное
            $tracks->load("top_id = $topId AND type = 'intop' $where AND  published='true' ORDER BY placeintop, points DESC $limit");

        return $tracks;
    }

    /**
     * Обновление топов
     */
    function updatePositions() {
        $tops = $this->getListModel();

        // Пробег по топам
        foreach ($tops as $top) {
            $tracks = $this->getTracks($top->id, TRUE);
            $places = [];

            // Пробег по трекам текущего топа
            foreach ($tracks as $track)
                $places[$track->id] = (new Track)->getPiT($track->id);
            arsort($places);

            // Назначение мест
            $place = 1;
            foreach ($places as $trackId => $unit) {
                $places[$trackId] = $place;
                $place++;
            }

            // Обновление мест
            foreach ($tracks as $track) {
                $track->placeintop = $places[$track->id];
                $track->save();
            }
        }
    }
}
?>