<?
class Album extends CRUD {
	protected $modelClassName = 'AlbumModel';
	/**
	 * @var FilterForm
	 */
	protected $filter;
	function buildFilter(){
		/*
		 * Модель данных фильтра
		 */
		$filter = $this->getModelInstance()->sets($_REQUEST);
		/*
		 * Преобразователь Модель->SQL
		 */
		$sql = new ModelToSQL($filter, true);
		/*
		 * форма
		 */
		$filterForm = DForm::generateByModel($filter);
		$filterForm
			->addAttribute('class="filter"')
			->addAttribute('method="get"');
		$filterForm->add(
			FormElement::factory('StaticInput')
				->setValue(sprintf('<div id="title" class="title">%s</div>', s('filtertitle'))),
			DForm::$PREPEND // вставить заголовок в начало формы
			);
		$filterForm->setAction('XXX.html');
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
	/*
	function getListModelToFormMap($model) {
		$map = parent::getListModelToFormMap($model);
		return $map;
	}
	*/

	function lists(){
        Page::setName('Альбомы');
        $this->getModelInstance()->getProxy()->setPager(new PagerHelperFull(10));

        // Администрирование
        if (RDS::get()->is('admin') && strpos($_REQUEST['action'], 'admin') !== FALSE) {
            $this->buildFilter();
            $this->customize->fields = 'id,projectId,date,name,descr,tracks,userOrder';
            $this->customize->conditions = "1";
        } // API
        elseif (isset($_REQUEST['action']) && strpos($_REQUEST['action'], 'api/') !== FALSE) {
            $ids = filter_var($_REQUEST['albums'], FILTER_SANITIZE_MAGIC_QUOTES);
            $this->customize->fields = 'id,name,tracks,userOrder';
            $this->customize->conditions = "id IN ($ids) ORDER BY id DESC";
        } // Всё остальное
        else {
            $this->setView(VC_DTPL, 'albumListView.php');

            // Фильтрация списка
            $where = "";
            if (isset($_REQUEST['project_id']) && is_numeric($_REQUEST['project_id']))
                $where .= 'projectId = '.$_REQUEST['project_id'];
            if (isset($_REQUEST['style_id']) && is_numeric($_REQUEST['style_id'])){
                if (!empty($where)) $where .= ' AND ';
                $where .= 'style_id = '.$_REQUEST['style_id'];
            }
            if (empty($where)) $where = "true";
            $this->customize->conditions = "$where ORDER BY date DESC";
        }
		$data = parent::lists();
		return $data;
	}

	function getShowModelToFormMap(DModel $model) {
		$map = parent::getShowModelToFormMap($model);
        $map['projectId'] = 'EmptyView';
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
            'date'      => 'Дата',
            'name'      => 'Название',
            'descr'     => 'Описание',
            'tracks'    => 'Количество треков',
            'userOrder' => 'Порядоковый номер',
            'linkToListPage'     => ''//'Обратно',
        ];
        $RDS = RDS::get();
        foreach ($form as $element) {
            if ($element instanceof LabeledInput) {
                // Назначение меток
                $name = $element->getName();
                if ($name != 'linkToListPage')
                    $element->setLabel($labels[$name]);

                // Удаление меток у пустых полей
                if (get_class($element) == 'EmptyView')
                    $element->setLabel('');

                // Для создания альбома в текущем проекте
                if ($name == 'projectId' && !$RDS->is('admin') && $RDS->isLogged)
                    $form->add((new HiddenInput)->setName('projectId')->setValue($RDS->config->curProjectId));
            }
        }

		//$this->setView(VC_DTPL, 'show_template.php');
		return $form;
	}

	function show($id = null){
 		//$this->customize->fields = 'id,author_id,date,name,descr,tracks,userOrder';
		//$this->customize->conditions = "user_id = {$this->RDS->userId}";
        if (!empty($id)) {
            Page::setName("Альбом ".$this->getShowModel($id)->name);
            $this->getModelInstance()->getProxy()->setPager(new PagerHelperFull(10));

            // Администрирование
            if (RDS::get()->is('admin') && strpos($_REQUEST['action'], 'admin') !== FALSE) {}
            // Редактирование
            elseif (isset($_REQUEST['edit']) && RDS::get()->isLogged) {}
            // Все остальные
            else {
                $this->setView(VC_DTPL, 'albumView.php');
            }
        } else {
            Page::setName("Создание альбома");
            $this->customize->fields = 'id,projectId,date,name,descr';
        }

		$data = parent::show($id);
		return $data;
	}

    function edit() {
        parent::edit();
        return (new HandlingResult)->setRedirect('/albums.html?project_id='.RDS::get()->config->curProjectId);
    }

	/*
	function delete($id = null){
		//$this->customize->conditions = "user_id = {$this->RDS->userId}";
		return parent::delete($id);
	}
	*/

    /**
     * Определение основного музыкального стиля альбома
     * @param $id идентификатор альбома
     * @return stdClass объект основного стиля
     */
    function getMainStyle($id) {
        $tracks = $this->getTracks($id);
        $styles = [];
        if ($tracks->count() > 0)
            foreach ($tracks as $track)
                if (!isset($styles[$track->style_id->id]))
                    $styles[$track->style_id->id] = 1;
                else $styles[$track->style_id->id]++;
        $res = new stdClass();
        $res->id   = array_search(max($styles), $styles);
        $res->name = (new MusicStyleModel)->load('id = '.$res->id)->value;
        return $res;
    }

    /**
     * Получение треков заданного альбома
     * @param $id идентификатор альбома
     * @return DModelsCollection коллекция моделей треков
     */
    function getTracks($id = 0) {
        $tracks = new DModelsCollection('TrackModel');
        // для api
        if (isset($_REQUEST['action']) && strpos($_REQUEST['action'], 'api/') !== FALSE) {
            $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
            $tracks->getModelInstance()->maskPropertyList('id,name,projectId,album_id,style_id,top_id');
        }

        $tracks->load('album_id = '.$id.' AND published="true" ORDER BY public_date, points DESC');

        // для api
        if (isset($_REQUEST['action']) && strpos($_REQUEST['action'], 'api/') !== FALSE) {
            $tracks = $tracks->getAsStdObjects();
            foreach($tracks as $track) {
                $uid = (new ProjectModel)->load('id = '.$track->projectId->id)->creatorId->id;
                $track->avatar = (new File)->getPath($track->id, $uid, 'trackPic', TRUE);
                $track->listen = (new File)->getListenLink($track->id);
            }
            echo JSONserialize($tracks);
            exit;
        }
        else
            return $tracks;
    }

    /**
     * Получение музыкальных стилей альбома
     * @param $id идентификатор альбома
     * @return array массив стилей треков, загруженных в альбом
     */
    function getStyles($id) {
        $tracks = $this->getTracks($id);
        $styles = [];
        if ($tracks->count() > 0)
            foreach ($tracks as $track)
                if (!array_key_exists($track->style_id->id, $styles))
                    $styles[$track->style_id->id] = $track->style_id->value;
        return $styles;
    }

    /**
     * Определение авторства
     * @param $id int идентификатор альбома
     * @return bool
     */
    function isAuthor($id) {
        if (empty($id))
            return FALSE;
        $uid = RDS::get()->userId;
        $aid = (new ProjectModel)->load('id ='.$this->getShowModel($id)->projectId->id)->creatorId->id;

        if ($aid == $uid)
            return TRUE;
        return FALSE;
    }
}
?>