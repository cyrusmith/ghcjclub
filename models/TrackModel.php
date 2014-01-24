<?/**
 * @property int $id
 * @property int $top_id
 * @property int $projectId
 * @property int $album_id
 * @property int $style_id 
 * @property varchar $name 
 * @property int $filesize 
 * @property int $timelength 
 * @property enum $format 
 * @property enum $type 
 * @property text $descr 
 * @property datetime $uploaddate 
 * @property date $public_date 
 * @property enum $published 
 * @property varchar $link 
 * @property varchar $link_lowfi 
 * @property int $forum_topic_id 
 * @property float $mark 
 * @property int $markscount 
 * @property int $placeintop 
 * @property int $topincrement 
 * @property int $placeoverall 
 * @property int $count_download 
 * @property int $count_listen 
 * @property int $bumper 
 * @property enum $refunded 
 * @property enum $label 
 * @property int $albumOrder 
 * @property enum $inrelease 
 * @property int $userOrder 
 * @property datetime $lastCommentDate 
 * @property float unsigned $pit 
 * @property enum $track_sharing 
 * @property enum $comments_blocked 
 * @property enum $license 
 * @property enum $ban_high_quality 
 * @property int $newVersionId 
 * @property int $points
 */
class TrackModel extends DModelValidated {
	function setup() {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('top_id', 'int', "11")
            ->addProperty('projectId', 'int', "11", '0')
			->addProperty('album_id', 'int', "11")
			->addProperty('style_id', 'int', "11")
			->addProperty('name', 'varchar', "255", null)
			->addProperty('permanentName', 'varchar', "255", null)
			->addProperty('filesize', 'int', "11", null)
			->addProperty('timelength', 'int', "11", null)
			->addProperty('format', 'enum', "'mp3','ogg','wma'", 'mp3')
			->addProperty('type', 'enum', "'intop','demo','outtop'", 'intop')
			->addProperty('descr', 'text', "", null)
			->addProperty('uploaddate', 'datetime', "")
			->addProperty('public_date', 'date', "", null)
			->addProperty('published', 'enum', "'false','true'", 'false')
			->addProperty('link', 'varchar', "255", null)
			->addProperty('link_lowfi', 'varchar', "255", null)
			->addProperty('forum_topic_id', 'int', "11", null)
			->addProperty('placeintop', 'int', "11", null)
			->addProperty('topincrement', 'int', "11", null)
			->addProperty('placeoverall', 'int', "11", null)
			->addProperty('count_download', 'int', "11", '0')
			->addProperty('count_listen', 'int', "11", '0')
			->addProperty('bumper', 'int', "11", '0')
			->addProperty('refunded', 'enum', "'false','true'", 'false')
			->addProperty('label', 'enum', "'false','true'", 'false')
			->addProperty('albumOrder', 'int', "11", '0')
			->addProperty('inrelease', 'enum', "'no','onreview','yes'", 'no')
			->addProperty('userOrder', 'int', "11")
			->addProperty('lastCommentDate', 'datetime', "")
			->addProperty('pit', 'float unsigned', "", '0')
			->addProperty('track_sharing', 'enum', "'false','true'", 'true')
			->addProperty('comments_blocked', 'enum', "'false','true'", 'false')
			->addProperty('license', 'enum', "'none','by','by-sa','by-nd','by-nc','by-nc-sa','by-nc-nd'", 'none')
			->addProperty('ban_high_quality', 'enum', "'false','true'", 'false')
			->addProperty('newVersionId', 'int', "11", null)
			->addProperty('points', 'int', "11", '0')
		;
		$this->proxy = new DModelProxyDatabase('tracks');
	}
	function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
            case 'top_id':
                break;
            /*
             * todo это индускодинг
            case 'projectId':
                if (!empty($value))
                    //$value = dbSelect('projects', 'id,name', "id = $value", DB_SELECT_OBJ);
					$value = 'project name';
                else {
                    $value = new stdClass();
                    $value->id   = '';
                    $value->name = '';
                }
                break;
			case 'album_id':
                if (!empty($value))
                    //$value = dbSelect('albums', 'id,name', 'id = '.$value, DB_SELECT_OBJ);
                else {
                    $value = new stdClass();
                    $value->id   = '';
                    $value->name = '';
                }
				break;
			case 'style_id':
                if (!empty($value) || $value === 0)
                    //$value = dbSelect('musicstyles', 'id,value', "id = $value", DB_SELECT_OBJ);
                else {
                    $value = new stdClass();
                    $value->id    = '';
                    $value->value = '';
                }
				break;
            */
			case 'name':
				break;
			case 'permanentName':
//                if (empty($value)) {
//                    $value = str_replace(" ", "_", $this->name);
//                    dbUpdate('tracks', array("permanentName" => $value), "id = {$this->id}");
//                }
				break;
			case 'filesize':
				break;
			case 'timelength':
				break;
			case 'format':
				break;
			case 'type':
				break;
			case 'descr':
                if (!isset($_REQUEST['edit']))
                    $value = (new WikiParser)->wikiedValue($value);
				break;
			case 'uploaddate':
				break;
			case 'public_date':
                if (empty($value))
                    $value = date('Y-m-d');
				break;
			case 'published':
				break;
			case 'link':
				break;
			case 'link_lowfi':
				break;
			case 'forum_topic_id':
				break;
			case 'placeintop':
				break;
			case 'topincrement':
				break;
			case 'placeoverall':
				break;
			case 'count_download':
				break;
			case 'count_listen':
				break;
			case 'bumper':
				break;
			case 'refunded':
				break;
			case 'label':
				break;
			case 'albumOrder':
				break;
			case 'inrelease':
				break;
			case 'userOrder':
				break;
			case 'lastCommentDate':
				break;
			case 'pit':
				break;
			case 'track_sharing':
				break;
			case 'comments_blocked':
				break;
			case 'license':
				break;
			case 'ban_high_quality':
				break;
			case 'newVersionId':
				break;
			case 'points':
				break;
		}
		return $value;
	}


	function setterConversions($field, $value) {
		switch ($field) {
			case 'top_id':
				break;
			case 'projectId':
				break;
			case 'album_id':
				break;
			case 'style_id':
				break;
			case 'name':
				break;
            case 'permanentName':
                break;
			case 'filesize':
				break;
			case 'timelength':
				break;
			case 'format':
				break;
			case 'type':
				break;
			case 'descr':
				break;
			case 'uploaddate':
				break;
			case 'public_date':
				break;
			case 'published':
                $value = isset($_REQUEST['published']) ? $_REQUEST['published'] : $value;
				break;
			case 'link':
				break;
			case 'link_lowfi':
				break;
			case 'forum_topic_id':
				break;
			case 'placeintop':
				break;
			case 'topincrement':
				break;
			case 'placeoverall':
				break;
			case 'count_download':
				break;
			case 'count_listen':
				break;
			case 'bumper':
				break;
			case 'refunded':
				break;
			case 'label':
				break;
			case 'albumOrder':
				break;
			case 'inrelease':
				break;
			case 'userOrder':
				break;
			case 'lastCommentDate':
				break;
			case 'pit':
				break;
			case 'track_sharing':
				break;
			case 'comments_blocked':
				break;
			case 'license':
				break;
			case 'ban_high_quality':
				break;
			case 'newVersionId':
				break;
			case 'points':
				break;
		}
		return parent::setterConversions($field, $value);
	}

    /**
     * Дополнительные действия по загрузке трека
     */
    function afterCreate() {
        $userId = RDS::get()->userId;
        (new File)->upload($this->id);
        (new Wave)->create($this->id, $userId);

        $path = (new File)->getPath($this->id, $userId, 'track');
        $info = exec($cmd = sprintf("perl %s/fileinfo.pl -length %s", CONFIG::$PATH_WaveGraph, $path));
        $this->timelength = $info;
        $this->filesize   = filesize($path);
        $this->save();

        $album = (new Album)->getShowModel($this->album_id->id);
        $album->tracks++;
        $album->save();
    }
}
?>