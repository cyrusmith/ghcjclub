<?
class File extends DController {
    /**
     * Загрузка файла на сервер
     * @param $id идентификатор сущности файла в БД
     * @throws Exception ошибки загрузки
     */
    function upload($id) {
        $RDS = RDS::get();
        if (!$RDS->isLogged)
            throw new Exception('Ошибка доступа');

        // Директория файлов пользователя
        $dirPath = CONFIG::$PATH_ABS.'/'.CONFIG::$PATH_UserFiles.'/'.$RDS->userId;
        if (!file_exists($dirPath))
            mkdir($dirPath);

        if (count($_FILES) > 0)
            foreach ($_FILES as $key => $file)
               switch ($key) {
                   case 'trackfile':
                       $dirPath = CONFIG::$PATH_ABS.'/'.CONFIG::$PATH_UserFiles.'/'.$RDS->userId.'/tracks/';
                       if (!file_exists($dirPath))
                           mkdir($dirPath);
                       $file->name = $id;
                       $file->moveTo($dirPath);
                       break;

                   case 'trackpic':
                       $dirPath = CONFIG::$PATH_ABS.'/'.CONFIG::$PATH_UserFiles.'/'.$RDS->userId.'/trackPics/';
                       if (!file_exists($dirPath))
                           mkdir($dirPath);
                       $file->name = $id;
                       $file->moveTo($dirPath);
                       break;

                   case 'hasAvatar':
                       $dirPath = CONFIG::$PATH_ABS.'/'.CONFIG::$PATH_UserFiles.'/'.$RDS->userId;
                       $file->name = 'avatar';
                       $file->moveTo($dirPath);
                       break;
               }
    }

    /**
     * Получение пути заданного файла
     * @param $type тип файла
     * @param $id идентификатор файла
     * @param $userId идентификатор пользователя
     * @param bool $url вернуть ссылку вместо пути
     * @return string путь или ссылка на файл
     * @throws Exception ошибки входных параметров
     */
    function getPath($id, $userId, $type, $url = FALSE) {
        if (empty($type))
            throw new Exception('Не указан тип файла');

        $path = '';
        $abs  = CONFIG::$PATH_ABS.'/'.CONFIG::$PATH_UserFiles.'/'.$userId;
        switch ($type) {
            case 'track':
                if ($url)
                    $path = CONFIG::$PATH_ListenUrl.'/';
                else
                    $path = CONFIG::$PATH_ABS.'/';

                if (file_exists($abs.'/tracks/'.$id))
                    $path .= CONFIG::$PATH_UserFiles.'/'.$userId.'/tracks/'.$id;
                else
                    $path .= $id;
                break;

            case 'trackPic':
                if ($url)
                    $path = CONFIG::$PATH_URL.'/';
                else
                    $path = CONFIG::$PATH_ABS.'/';

                if (!file_exists($abs.'/trackPics/'.$id))
                    $path .= '_files/trackpic.png';
                else
                    $path .= CONFIG::$PATH_UserFiles.'/'.$userId.'/trackPics/'.$id;
                break;

            case 'albumPic':
                if ($url)
                    $path = CONFIG::$PATH_URL.'/';
                else
                    $path = CONFIG::$PATH_ABS.'/';

                if (!file_exists($abs.'/albumPics/'.$id))
                    $path .= '_files/albumpic.png';
                else
                    $path .= CONFIG::$PATH_UserFiles.'/'.$userId.'/albumPics/'.$id;
                break;

            case 'avatar':
                if ($url)
                    $path = CONFIG::$PATH_URL.'/';
                else
                    $path = CONFIG::$PATH_ABS.'/';

                if (!file_exists($abs.'/avatar'))
                    $path .= '_files/avatar.jpg';
                else
                    $path .= CONFIG::$PATH_UserFiles.'/'.$id.'/avatar';
                break;

            case 'wave':
                if ($url)
                    $path = CONFIG::$PATH_URL.'/';
                else
                    $path = CONFIG::$PATH_ABS.'/';

                $dirPath = $path.CONFIG::$PATH_UserFiles."/$userId/waves";
                if (!file_exists($dirPath))
                    mkdir($dirPath);

                $path .= CONFIG::$PATH_UserFiles."/$userId/waves/$id.png";
                break;
        }
        return $path;
    }

    /**
     * Получение ссылки на скачивание трека
     * @param $id идентификатор трека
     * @return string ссылка
     */
    function getDownloadLink($id) {
	    if (!$id) return;
        $dirPath = CONFIG::$PATH_ABS.'/download/';
        if (!file_exists($dirPath))
            mkdir($dirPath);

        $aid   = (new ProjectModel)->load('id = '.(new TrackModel)->load("id = $id")->projectId->id)->creatorId->id;
        $key   =  md5($id.Registry::get('filekey'));
        $path  = $this->getPath($id, $aid, 'track');
        $track = (new TrackModel)->load("id = $id");
        $name  = $track->permanentName.".mp3";
        if (!file_exists($dirPath.$name) && file_exists($path))
            symlink($path, $dirPath.$name);

        return CONFIG::$PATH_DownloadUrl."/$name?id=$id&key=$key&sid=".session_id();
    }

    /**
     * Получение  ссылки для прослушивания
     * @param $id идентификатор трека
     * @return string ссылка на трек
     */
    function getListenLink($id){
        $dirPath = CONFIG::$PATH_ABS.'/listen/';
        if (!file_exists($dirPath))
            mkdir($dirPath);

        $aid   = (new ProjectModel)->load('id = '.(new TrackModel)->load("id = $id")->projectId->id)->creatorId->id;
        $path  = $this->getPath($id, $aid, 'track');
        $track = (new TrackModel)->load("id = $id");
        $name  = $track->permanentName.".mp3";
        if (!file_exists($dirPath.$name) && file_exists($path))
            symlink($path, $dirPath.$name);

        return CONFIG::$PATH_ListenUrl."/$name";
    }

    /**
     * Метод выдачи пути картинки трека для api
     * @return array|stdClass|string
     */
    function getTrackPic() {
        $id  = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
        $pid = (new TrackModel)->load("id = $id")->projectId->id;
        echo  JSONserialize($this->getPath($id, (new Project)->getShowModel($pid)->creatorId->id, 'trackPic', TRUE));
    }

    /**
     * Метод выдачи пути аватарки юзера для api
     * @return array|stdClass|string
     */
    function getAuthorAvatar() {
        $id  = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
        echo  JSONserialize($this->getPath($id, 0, 'avatar', TRUE));
    }
}
