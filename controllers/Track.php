<?
class Track extends CRUD {
	protected $modelClassName = 'TrackModel';
	/**
	 * @var FilterForm
	 */
	protected $filter;
	function buildFilter(){
		/*
		 * Модель данных фильтра
		 */
        $filter = $this->getModelInstance()
            ->maskPropertyList('id,public_date')
            ->sets($_REQUEST);
		/*
		 * Преобразователь Модель->SQL
		 */
		$sql = new ModelToSQL($filter, true);
		/*
		 * форма
		 */
		$filterForm = DForm::generateByModel($filter);
        $filterForm->generateLabels('filter_');
		$filterForm
			->addAttribute('class="filter"')
			->addAttribute('method="get"');
		$filterForm->add(
			(new StaticInput)
				->setValue(sprintf('<div id="title" class="title">%s</div>', s('filtertitle'))),
			DForm::$PREPEND // вставить заголовок в начало формы
			);
        // Кнопки применения и сброса фильтра
        $filterForm->add((new Submit)->setLabel('Применить')->addAttribute('style="display: inline-block;"'));
        if (count($_REQUEST) > 1)
            $filterForm->add((new Button)
                    ->setLabel('Все')
                    ->addAttribute(sprintf('onclick="location=\'%s/%s\'"', CONFIG::$PATH_URL,'admin/articles'))
                    ->addAttribute('style="display: inline-block;"')
            );

        if (RDS::get()->is('admin'))
            $filterForm->setAction('admin/tracks.html');
        else $filterForm->setAction('tracks.html');

		$this->dataForView
			->createPropertyByValue('filterModel', $filter)
			->createPropertyByValue('filterSql', $sql)
			->createPropertyByValue('filterForm', $filterForm);
	}
	/*
	function getModelInstance() {
		$model = parent::getModelInstance();
		return $model;
	}
	*/
	/*
	function getListModel() {
		$this->customize->conditions = "user_id = {$this->RDS->userId}";
		$this->filter = $this->buildFilter();
		$this->customize->conditions .= $this->filter->getResult(true, true);
		return parent::getListModel();
	}
	*/
	/*
	public function getListModelToFormMap($model) {
		$map = parent::getListModelToFormMap($model);
		//$map[''] = 'TextInput';
		return $map;
	}
	*/
	/*
	function getListForm($model) {
		$this->setView(VC_DTPL, 'list_template.php');
		return null;
		return parent::getListForm($model);
	}
	*/

	function getListModelToFormMap(DModelsCollection $model) {
		$map = parent::getListModelToFormMap($model);
        if (RDS::get()->is('admin')) {
            $map['projectId'] = 'AuthorIdView';
            $map['style_id']  = 'MusicStyleView';
        }
		return $map;
	}

	function lists(){
        Page::setName('Каталог треков');
        $this->getModelInstance()->getProxy()->setPager(new PagerHelperFull(10));
        $this->buildFilter();

        // Для админа в админке
        if (RDS::get()->is('admin') && strpos($_REQUEST['action'], 'admin') !== FALSE) {
            $this->customize->fields = 'id,projectId,style_id,name,public_datepit';
            $this->customize->conditions = "{$this->dataForView->get('filterSql')->getSQL()} ORDER BY public_date DESC";
        }// Для api
        elseif (isset($_REQUEST['action']) && strpos($_REQUEST['action'], 'api/') !== FALSE) {
            $ids = filter_var($_REQUEST['tracks'], FILTER_SANITIZE_MAGIC_QUOTES);
            $this->customize->fields = 'id,projectId,name,count_listen,pit';
            $this->customize->conditions = "id IN ($ids) ORDER BY public_date DESC";
            $data = parent::getListModel();
            $data = $data->getAsStdObjects();
            foreach ($data as $track) {
                $uid = (new ProjectModel)->load('id = '.$track->projectId->id)->creatorId->id;
                $track->avatar = (new File)->getPath($track->id, $uid, 'trackPic', TRUE);
                $track->listen = (new File)->getListenLink($track->id);
            }
            echo JSONserialize($data);
            exit;
        } // Для всего остального
        else {
            $this->setView(VC_DTPL, 'trackListView.php');

            // Фильтрация на странице пользовательского списка
            $where = "";
            if (isset($_REQUEST['project_id']) && is_numeric($_REQUEST['project_id']))
                $where .= 'projectId = '.$_REQUEST['project_id'];
            if (isset($_REQUEST['label']) && $_REQUEST['label'] == 'true'){
                if (!empty($where)) $where .= ' AND ';
                $where .= 'label = "'.$_REQUEST['label'].'"';
            }
            if (isset($_REQUEST['name']) && !empty($_REQUEST['name'])){
                if (!empty($where)) $where .= ' AND ';
                $where .= 'name LIKE "%'.$_REQUEST['name'].'%"';
            }
            if (isset($_REQUEST['type']) && !empty($_REQUEST['type'])){
                if (!empty($where)) $where .= ' AND ';
                $where .= 'type = "'.$_REQUEST['type'].'"';
            }
            if (isset($_REQUEST['top']) && is_numeric($_REQUEST['top'])){
                if (!empty($where)) $where .= ' AND ';
                $where .= 'top_id = '.$_REQUEST['top'];
            }
            if (isset($_REQUEST['style_id']) && is_numeric($_REQUEST['style_id'])){
                if (!empty($where)) $where .= ' AND ';
                $where .= 'style_id = '.$_REQUEST['style_id'];
            }
            if (empty($where)) $where = "true";

            $this->customize->fields = 'id,projectId,style_id,album_id,name,filesize,timelength,type,descr,public_date,published,placeintop,topincrement,count_download,count_listen,label,pit,license,points';
            $this->customize->conditions = "$where ORDER BY public_date DESC";
        }

        $data = parent::lists();
		return $data;
	}

	function getShowModelToFormMap(DModel $model) {
		$map = parent::getShowModelToFormMap($model);
        $map['projectId'] = 'EmptyView';
        $map['style_id']  = 'MusicStylesSelector';
        $map['album_id']  = 'AlbumSelector';
        $map['published'] = 'EmptyView';
		return $map;
	}

	/*
	function getShowModel($id) {
		return parent::getShowModel($id);
	}
	*/

	function getShowForm(DModel $model) {
		$form = parent::getShowForm($model);

        $labels = [
            'projectId' => 'Проект',
            'album_id'  => 'Альбом',
            'style_id'  => 'Стиль',
            'type'      => 'Тип',
            'name'      => 'Название трека',
            'descr'     => 'Описание',
            'public_date' => 'Дата публикации',
            'license'     => 'Лицензия Creative Commons',
            'track_sharing'    => 'Участвует в трекообмене',
            'comments_blocked' => 'Запретить комментирование трека',
            'ban_high_quality' => 'Запрет на скачивание и прослушивание в высоком качестве',
            'top_id'     => 'Топ',
            'pit'        => 'PiT',
            'points'     => 'Плюсы',
            'filesize'   => 'Размер файла',
            'timelength' => 'Время',
            'format'     => 'Формат',
            'uploaddate' => 'Дата загрузке',
            'published'  => 'Опубликовано',
            'link'       => 'Ссылка(hq)',
            'link_lowfi' => 'Ссылка(lq)',
            'forum_topic_id' => 'Форум',
            'placeintop'     => 'Место в топе',
            'topincrement'   => 'Движение по топу',
            'placeoverall'   => '',
            'count_download' => 'Количество скачиваний',
            'count_listen'   => 'Количество прослушиваний',
            'bumper'         => '',
            'refunded'       => '',
            'label'          => 'Метка',
            'albumOrder'     => 'Место в альбоме',
            'inrelease'      => 'Подан на релиз',
            'userOrder'      => 'Место в списке треков',
            'lastCommentDate' => 'Дата последнего комментария',
            'newVersionId'    => 'Последняя версия трека',
            'linkToListPage'  => ''//'Обратно',
        ];
        foreach ($form as $element) {
            if ($element instanceof LabeledInput) {
                // Назначение меток
                $name = $element->getName();
                if($name != 'linkToListPage')
                    $element->setLabel($labels[$name]);

                // Удаление меток у пустых полей
                if (get_class($element) == 'EmptyView')
                    $element->setLabel('');

                // Для загрузки трека в текущий проект
                if ($name == 'projectId' && !RDS::get()->is('admin') && isset(RDS::get()->config->curProjectId))
                    $form->add((new HiddenInput)->setName('projectId')->setValue(RDS::get()->config->curProjectId));
            }
        }
        if (!isset($_REQUEST['edit'])) {
            $form->add((new FileInput)->setLabel('Файл трека')->setName('trackfile'));
            $form->add((new FileInput)->setLabel('Картинка трека')->setName('trackpic'));
        }

		return $form;
	}

	function show($id = null){
        if ((!RDS::get()->is('admin') || strpos($_REQUEST['action'], 'admin') === FALSE)) {
            if ((!isset($_REQUEST['edit']) || !RDS::get()->isLogged) && !empty($id)) {
                $track= $this->getShowModel($id);
                //$this->setView(VC_DTPL, 'trackView.php');
                Page::setName("Трек '{$track->name}'");
            } elseif (RDS::get()->isLogged) {
                if (isset($_REQUEST['edit']))
                    $this->customize->fields = 'id,public_date,projectId,album_id,style_id,name,type,descr,track_sharing,comments_blocked,license,ban_high_quality';
                else
                    $this->customize->fields = 'id,projectId,public_date,published,album_id,style_id,name,type,descr,track_sharing,comments_blocked,license,ban_high_quality';

                if ($id == null)
                    Page::setName("Загрузить трек");
                else
                    Page::setName("Редактировать трек");

                //$this->setView(VC_DTPL, 'trackEdit.php');
            }
        }
		$data = parent::show($id);
		return $data;
	}

    function edit() {
        if (isset($_REQUEST['public_date']) && (time() >= strtotime($_REQUEST['public_date'])))
            $_REQUEST['published'] = 'true';
        parent::edit();

        if (isset($_REQUEST['edit']))
            $mes = "Ok.";
        else
            $mes = "Трек загружен";
        $res =  new handlingResult($mes);
        $res->setRedirect('/tracks.html?project_id='.RDS::get()->config->curProjectId);
        return $res;
    }

    /*
    function delete($id = null){
        //$this->customize->conditions = "user_id = {$this->RDS->userId}";
        return parent::delete($id);
    }
    */

    /**
     * Удаление трека
     * @return handlingResult сообщение об удалении
     */
    function deleteRequest(){
        $id = getPOSTfiltered('id', 'n');
        $trackInfo = $this->getShowModel($id);
        if (!$this->isAuthor(RDS::get()->userId)) accessDenied();
        $key = defferedActions::insert('Track/delete', $id);

        $mailData = [
            'id'	=> $id,
            'name'	=> $trackInfo->name,
            'key'	=> $key
        ];

        Mailer::push(new MailLetter($mailData, 'deletetrack'), RDS::get()->userInfo->email, MAIL_INSTANT);

        return new handlingResult('На ваш email отправлено письмо с ключом удаления трека');
    }

    /**
     * Плюсование счётчика скачек и прослушиваний для трека
     */
    function actionPlus() {
        $id     = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $action = filter_var($_POST['action'], FILTER_SANITIZE_MAGIC_QUOTES);

        $RDS  = RDS::get();
        $ip   = $_SERVER['REMOTE_ADDRESS'];
        $flag = FALSE;

        if ($RDS->isLogged) {
            $uid = $RDS->userId;
            $progressModel = (new TrackProgressModel)->load("trackId = $id AND userId = $uid AND action=\"$action\"");
            if (!isset($progressModel->id)) {
                $model = new TrackProgressModel();
                $model->trackId = $id;
                $model->userId  = $uid;
                $model->ip      = $ip;
                $model->action  = $action;
                $model->create();
                $flag = TRUE;
            }
        } else {
            $progress = new DModelsCollection('TrackProgressModel');
            $progress->load("trackId = $id AND userId = $ip AND action=\"$action\"");
            if ($progress->count() <= 1) {
                $model = new TrackProgressModel();
                $model->trackId = $id;
                $model->userId  = 0;
                $model->ip      = $ip;
                $model->action  = $action;
                $model->create();
                $flag = TRUE;
            }
        }

        if ($flag) {
            $trackModel = $this->getShowModel($id);
            switch ($action) {
                case 'download':
                    $trackModel->count_download++;
                    break;

                case 'listen':
                    $trackModel->count_listen++;
                    break;
            }
            $trackModel->save();
        }
    }

    /**
     * Получение коллекции лучших треков
     * @param string $int период публикации
     * @param int $limit количество треков
     * @return DModelsCollection коллекция лучших треков
     */
    function getBest($int = 'week', $limit = 5) {
        switch ($int) {
            case 'week':
                $this->customize->conditions = 'public_date IS NOT NULL AND published="true" ORDER BY points DESC, public_date DESC LIMIT 0,'.$limit;
                break;

            case 'month':
                $this->customize->conditions = 'public_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH) AND published="true" ORDER BY points DESC, public_date DESC LIMIT 0,'.$limit;
                break;

            case 'all':
                $this->customize->conditions = 'public_date IS NOT NULL AND published="true" ORDER BY points DESC,public_date DESC LIMIT 0,'.$limit;
                break;
        }
        return $this->getListModel();
    }

    /**
     * Получение комментариев к треку
     * @param $id идентификатор трека
     * @param string $limit ограничения выборки
     * @return DModelsCollection коллекция моделей комментариев к заданному треку
     */
    function getComments($id, $limit = '') {
        return (new Comment)->getListModel('object_id = '.$id.' AND object_type = "track" ORDER BY datewritten DESC'.$limit);
    }

    /**
     * Получение обсуждаемых треков
     * @return DModelsCollection коллекция обсуждаемых треков
     */
    function getDiscussed() {
        $this->customize->conditions = 'lastCommentDate IS NOT NULL AND published="true" ORDER BY lastCommentDate DESC LIMIT 0,10';
        return $this->getListModel();
    }

    /**
     * Получение новых треков
     * @return DModelsCollection коллекция новых треков
     */
    function getNew() {
        $this->customize->conditions = 'public_date IS NOT NULL AND published="true" ORDER BY points DESC, public_date DESC LIMIT 0,5';
        return $this->getListModel();
    }

    /**
     * Получение PiT за период
     * @param $id идентификатор трека
     * @param string $dateFrom начало периода
     * @param string $dateTo окончание периода
     * @return int PiT за период
     */
    function getPiT ($id, $dateFrom = '', $dateTo = '') {
        $pits = new DModelsCollection('PiTModel');
        $where = "track_id = $id";
        if (!empty($dateFrom))
            $where .= " AND date >= $dateFrom";
        if (!empty($dateTo))
            $where .= " AND date <= $dateTo";
        $pits->load($where);
        $pit = 0;
        if ($pits->count() > 0)
            foreach ($pits as $unit)
                $pit += $unit->pit;

        return $pit;
    }

    /**
     * Получение списка плюсанувших юзеров (ajax)
     * @return handlingResult html-код для вставки
     */
    function getPluses() {
        $id    = is_numeric($_POST['trackId']) ? $_POST['trackId'] : 0;
        $show  = is_numeric($_POST['show']) ? $_POST['show'] : 0;
        $data = '';

        $users = new DModelsCollection('CjclubUserModel');
        $proxy = new DModelProxyDatabaseJoins('rds_users');
        $proxy->addJoinedTable('track_has_points', 'track_has_points.userId = rds_users.id');
        $users->getModelInstance()->maskPropertyList('id,type_id,name')->setProxy($proxy);
        $count = $users->load("trackId = $id ORDER BY date DESC")->count();

        switch ($show) {
            case 1: // Список плюсанувших с расширением (страница трека)
            case 2: // Список всех плюсанувших (страница трека)
                $limit  = isset($_POST['limit']) && is_numeric($_POST['limit']) ? $_POST['limit'] : 12;
                $users->load("trackId = $id ORDER BY date DESC LIMIT 0,$limit");

                $tpl = new stdClass();
                $tpl->users   = $users;
                $tpl->count   = $users->count();
                $tpl->trackId = $id;
                $tpl->show    = 1;
                $tpl->limit   = isset($_POST['limit']) && is_numeric($_POST['limit']) ? ($_POST['limit'] + 12) : 24;

                if ($show == 2 || $count == $tpl->count) {
                    $tpl->name  = 'Свернуть';
                    $tpl->limit = 12;
                    if ($show == 2) {
                        $tpl->users = $users->load("trackId = $id ORDER BY date DESC");;
                        $tpl->count = $tpl->users->count();
                    }

                }

                $data .= renderTemplate('plusListView.php', $tpl);
                break;

            case 3: // График плюсанувших с расширением (страница трека)
            case 4: // График всех плюсанувших (страница трека)
                $limit = isset($_POST['limit']) && is_numeric($_POST['limit']) ? $_POST['limit'] : 20;
                if ($show == 3)
                    $users->load("trackId = $id ORDER BY type_id DESC LIMIT 0,$limit");
                else
                    $users->load("trackId = $id ORDER BY type_id DESC");

                $tpl = new stdClass();
                $tpl->count    = $users->count();
                $tpl->users    = array();
                $tpl->show     = 3;
                $tpl->trackId  = $id;
                $tpl->list     = 'graph';
                $tpl->name     = 'Свернуть';
                $tpl->limit    = isset($_POST['limit']) && is_numeric($_POST['limit']) ? ($_POST['limit'] + 20) : 40;
                $tpl->users['new'] = $tpl->users['adv'] = $tpl->users['sus'] = $tpl->users['pro'] = array();

                if ($users->count() > 0) {
                    foreach ($users as $user)
                        switch ($user->type_id->id) {
                            case 2:
                                $tpl->users['new'][] = $user;
                                break;

                            case 3:
                                $tpl->users['begin'][] = $user;
                                break;

                            case 4:
                                $tpl->users['good'][] = $user;
                                break;

                            case 5:
                                $tpl->users['pro'][] = $user;
                                break;
                        }
                }

                if ($count == $tpl->count) {
                    $tpl->name  = 'Свернуть';
                    $tpl->limit = 20;
                }
                $data .= renderTemplate('plusListView.php', $tpl);
                break;

            case 5: // Список всех плюсанувших (блок трека)
                $data .= 'Трек понравился: ';
                if ($users->count() > 0) {
                    foreach ($users as $user) {
                        $stat = '';
                        switch ($user->type_id->id) {
                            case 2:
                                $stat = 'новичок';
                                break;

                            case 3:
                                $stat = 'продвинутый';
                                break;

                            case 4:
                                $stat = 'опытный';
                                break;

                            case 5:
                                $stat = 'профессионал';
                                break;
                        }
                        $data .= "<strong><a href='authors/{$user->id}' alt='{$user->name}' title='{$user->name}, $stat'>{$user->name}</a></strong>, ";
                    }
                    $data = substr($data, 0, strlen($data) - 2);
                }
                break;
        }

        return (new handlingResult)->setResponse($data);
    }

    /**
     * Получение треков для пром-зоны
     * @param string $type тип пром-зоны
     * @return $this|DModelsCollection колекция моделей треков для пром-зоны
     */
    function getPromo($type = 'general') {
        if ($type == 'general') {
            $tracks = new DModelsCollection('TrackModel');
            $proxy  = new DModelProxyDatabaseJoins('tracks');

            $bottom = time() - (10 * 24 * 60 * 60);
            $proxy->addJoinedTable('track_has_points', "track_has_points.trackId = tracks.id AND track_has_points.date >= $bottom");
            $tracks->getModelInstance()->setProxy($proxy);

            return $tracks->load('public_date IS NOT NULL AND published="true" ORDER BY RAND() LIMIT 0,5');
        }
        return $this->getListModel();
    }

    /**
     * Получение списка музыкальных стилей с их "размерами"
     * @return DModelsCollection коллекция моделей стилей
     */
    function getStylesList () {
        $minSize = 11.0;
        $maxSize = 22.0;
        $sizeRange = $maxSize - $minSize;

        $stylesCloud = (new DModelsCollection('MusicStyleModel'))->load('id IN(SELECT DISTINCT(style_id) FROM cjclub_tracks WHERE true) ORDER BY value');

        foreach ($stylesCloud as $style)
            $style->tracks = (new DModelsCollection('TrackModel'))->load('style_id = '.$style->id)->count();
        $max = $min = 0;
        foreach ($stylesCloud as $style) {
            if ($max < $style->tracks) $max = $style->tracks;
            if ($min > $style->tracks) $min = $style->tracks;
        }

        // Чтоб не захламлять память лишними объектами
        // подкладываю в parent_id размер стиля
        foreach ($stylesCloud as $style)
            $style->parent_id = $minSize + (($style->tracks - $min) / ($max - $min) * $sizeRange);

        return $stylesCloud;
    }

    /**
     * Определение места в топе
     * @param $id идентификатор трека
     * @return string место в топе
     */
    function getTopPlace($id) {
        $track = $this->getShowModel($id);
        $place = ($track->placeintop > Registry::get('topdeep') || !$track->placeintop) ? '' : $track->placeintop;
        return $place;
    }

    /**
     * Определение авторства
     * @param $id int идентификатор трека
     * @return bool
     */
    function isAuthor($id) {
        if (empty($id))
            return FALSE;
        $uid = RDS::get()->userId;
        $aid = (new ProjectModel)->load('id ='.$id)->creatorId->id;

        if ($aid == $uid)
            return TRUE;
        return FALSE;
    }

    /**
     * Определение плюсанул ли пользователь заданный трек
     * @param $id   идентификатор трека
     * @return bool да/нет
     */
    function isPoint($id) {
        if (empty($id))
            return FALSE;
        $uid   = RDS::get()->userId;
        $point = (new TrackPointModel)->load("trackId = $id AND userId = $uid");

        if (isset($point->userId) && !empty($point->userId))
            return TRUE;
        return FALSE;
    }

    /**
     * Оценка трека плюсом
     * @throws Exception ошибка чтения идентификатора
     */
    function plusOne() {
        $trackId = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $userId  = RDS::get()->userId;
        if (!$trackId)
            throw new Exception('Ошибка чтения идентификатора.');
        $user = (new CjclubUserModel)->load('id = '.$userId);
        $plus = 0;
        switch ($user->type_id->id) {
            case 2:
                $plus = Registry::get('pit_mark_newbie');
                break;

            case 3:
                $plus = Registry::get('pit_mark_advanced');
                break;

            case 4:
                $plus = Registry::get('pit_mark_skilled');
                break;

            case 5:
                $plus = Registry::get('pit_mark_pro');
                break;
        }
        PIT::add($trackId, $plus);

        $trackModel = $this->getShowModel($trackId);
        $trackModel->points++;
        $trackModel->save();

        $plusModel = new TrackPointModel();
        $plusModel->userId  = $userId;
        $plusModel->trackId = $trackId;
        $plusModel->create();

        return (new handlingResult)->setStatus('Ваш голос учтён!');
    }
}
/**
 * Преобразование объекта в html-код
 * из-за переопределения значения поля в getterConversions
 * модели models/TrackModel.php
 */
class MusicStyleView extends StaticInput {
    function getHtml() {
        if (isset($this->value))
            return $this->value->value;
        else return (new TextInput)->getHtml();
    }
}
?>