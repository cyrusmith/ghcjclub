<?
class Project extends CRUD {
    protected $modelClassName = 'ProjectModel';

    function lists(){
        Page::setName('Проекты');
        $this->getModelInstance()->getProxy()->setPager(new PagerHelperFull(10));

        // Администрирование
        if (RDS::get()->is('admin') && strpos($_REQUEST['action'], 'admin') !== FALSE) {}
        // Всё остальное
        else {
            $this->setView(VC_DTPL, 'projectListView.php');

            // Фильтрация списка
            $where = "";
            if (isset($_REQUEST['author_id']) && is_numeric($_REQUEST['author_id']))
                $where .= 'creatorId = '.$_REQUEST['author_id'];
            if (!empty($where)) $where .= " AND";
            $this->customize->conditions = "$where musicRating IS NOT NULL AND musicPlace IS NOT NULL AND createDate IS NOT NULL ORDER BY musicRating DESC,musicPlace ASC";
        }
        $data = parent::lists();
        return $data;
    }

    function getShowModelToFormMap(DModel $model) {
        $map = parent::getShowModelToFormMap($model);

        $map['infoShort']  = 'TextInput';
        $map['config']     = 'EmptyView';
        $map['city_id']    = 'CityView';
        $map['country_id'] = 'CountryView';
        return $map;
    }

    function getShowForm(DModel $model) {
        $RDS = RDS::get();
        $form = parent::getShowForm($model);

        $labels = [
            'createDate' => 'Дата создания',
            'name'       => 'Название',
            'email'      => 'Электронная почта',
            'type'       => 'Тип',
            'info'       => 'Описание',
            'infoShort'  => 'Краткая информация',
            'city_id'    => 'Город',
            'country_id' => 'Страна',
            'creatorId'  => 'Создатель',
            'totalTracks' => 'Количество треков',
            'totalNews'   => 'Количество публикаций',
            'musicRating' => 'Музыкальный рейтинг',
            'musicPlace'  => 'Место в музыкальном топе',
            'config'     => '',
            'linkToListPage'     => ''//'Обратно',
        ];
        foreach ($form as $element) {
            if ($element instanceof LabeledInput) {
                // Назначение меток
                $name = $element->getName();
                if ($name != 'linkToListPage')
                    $element->setLabel($labels[$name]);
                if ($name == 'config') {
                    $element->setLabel('');
                    break;
                }

                // Удаление меток у пустых полей
                if (get_class($element) == 'EmptyView')
                    $element->setLabel('');

                // Редактирование поля "Тип проекта"
                if ($name == 'type') {
                    $options = $element->getOptions();
                    $options['dj']        = 'Профессиональный DJ';
                    $options['band']      = 'Музыкальная группа';
                    $options['group']     = 'Сообщество';
                    $options['podcast']   = 'Подкаст';
                    $options['radioshow'] = 'Радио-шоу';
                    $options['life']      = 'Лейб';
                    $options['contest']   = 'Конкурс';
                    $element->addOptions($options);
                }
            }
        }
        // Основной музыкальный стиль проекта
        $form->add((new MusicStylesSelector)->setValue('0')->setLabel('Музыкальный стиль'));

        // Контактная информация
        if ($RDS->isLogged) {
            $contacts = [
                'icq'        => 'ICQ',
                'phone'      => 'Телефон',
                'skype'      => 'Skype',
                'site'       => 'Сайт',
                'vkontakte'  => 'ВКонтакте',
            ];
            $projectConfig = $model->config;
            foreach ($contacts as $key => $item) {
                $nameV = $key.'Visible';
                $flag = isset($projectConfig->$nameV) ? $projectConfig->$nameV : FALSE;
                $form->add(
                    (new TextInput)->setLabel($item)
                        ->setName($key)
                        ->setValue($projectConfig->$key)
                        ->addAttribute('class="textinput"')
                );
                $form->add(
                    (new Checkbox)->setLabel('Видно всем')
                        ->setName($nameV)
                        ->setChecked($flag)
                );
            }
        }
        return $form;
    }

    function show($id = null) {
        if (!empty($id)) {
            Page::setName("Проект '".$this->getShowModel($id)->name."'");
            $this->getModelInstance()->getProxy()->setPager(new PagerHelperFull(10));

            // Администрирование
            if (RDS::get()->is('admin') && strpos($_REQUEST['action'], 'admin') !== FALSE) {}
            // Редактирование
            elseif (isset($_REQUEST['edit']) && RDS::get()->isLogged) {
                $this->customize->fields = 'id,name,email,info,infoShort,createDate,config,city_id,country_id';
            }
            // Все остальные
            else {
                $this->setView(VC_DTPL, 'projectView.php');
            }
        } else {
            Page::setName("Создание проекта");
            $this->customize->fields = 'id,createDate,name,email,type,info,infoShort,config,city_id,country_id';
        }
        $data = parent::show();
        return $data;
    }

    function edit(){
        $_REQUEST['config'] = new stdClass();

        $_REQUEST['config']->mainStyle = $_POST['style_id'];

        $_REQUEST['config']->phone = $_POST['phone'];
        $_REQUEST['config']->phoneVisible = isset($_POST['phoneVisible']) ? TRUE : FALSE;

        $_REQUEST['config']->skype = $_POST['skype'];
        $_REQUEST['config']->skypeVisible = isset($_POST['skypeVisible']) ? TRUE : FALSE;

        $_REQUEST['config']->icq = $_POST['icq'];
        $_REQUEST['config']->icqVisible = isset($_POST['icqVisible']) ? TRUE : FALSE;

        $_REQUEST['config']->site = $_POST['site'];
        $_REQUEST['config']->siteVisible = isset($_POST['siteVisible']) ? TRUE : FALSE;

        $_REQUEST['config']->vkontakte = $_POST['vkontakte'];
        $_REQUEST['config']->vkontakteVisible = isset($_POST['vkontakteVisible']) ? TRUE : FALSE;

        return parent::edit();
    }

    /**
     * Получение коллекции альбомов заданного проекта
     * @param $id идентификатор проекта
     * @param $limit количественное ограничение выборки
     * @return DModelsCollection коллекция альбомов проекта
     */
    function getAlbums($id, $limit = ''){
        $albums = new DModelsCollection('AlbumModel');
        $albums->load('projectId = '.$id.' ORDER BY userOrder ASC'.$limit);

        // для api
        if (isset($_REQUEST['action']) && strpos($_REQUEST['action'], 'api/') !== FALSE) {
            echo JSONserialize($albums->getAsStdObjects());
            exit;
        }
        return $albums;
    }

    /**
     * Получение коллекции статей заданного проекта
     * @param $id идентификатор проекта
     * @param $limit количественное ограничение выборки
     * @return DModelsCollection коллекция треков проекта
     */
    function getArticles($id, $limit = '') {
        $tracks = new DModelsCollection('ArticleModel');
        $tracks->load('projectId = '.$id.' ORDER BY public_date DESC'.$limit);
        $tracks->getModelInstance()->getProxy()->setPager(new PagerHelperFull(10));
        return $tracks;
    }

    /**
     * Получение топа проектов
     * @param $count int количество проектов
     * @return DModelsCollection коллекция моделей проектов
     */
    function getBest($count = 5) {
        $model = new DModelsCollection('ProjectModel');
        $model->getModelInstance()
            ->maskPropertyList('id,name,creatorId,type,infoShort,city_id');
        $model->load("musicPlace <= 20 ORDER BY RAND() LIMIT 0,$count");
        return $model;
    }

    /**
     * Получение записей на стене проекта
     * @param $id идентификатор проекта
     * @param string $limit ограничения выборки
     * @return DModelsCollection коллекция моделей записей на стене заданного проекта
     */
    function getComments($id, $limit = '') {
        return (new Comment)->getListModel('object_id = '.$id.' AND object_type = "project" ORDER BY datewritten DESC'.$limit);
    }

    /**
     * Получение музыкальных стилей проекта
     * @param $id идентификатор проекта
     * @return array массив стилей треков, загруженных в проект
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
     * Получение коллекции треков заданного проекта
     * @param $id идентификатор проекта
     * @param $limit количественное ограничение выборки
     * @return DModelsCollection коллекция треков проекта
     */
    function getTracks($id, $limit = '') {
        $tracks = new DModelsCollection('TrackModel');
        $tracks->load('projectId = '.$id.' AND published="true" ORDER BY public_date, points DESC'.$limit);
        $tracks->getModelInstance()->getProxy()->setPager(new PagerHelperFull(10));
        return $tracks;
    }

    /**
     * Определение авторства
     * @param $id int идентификатор проекта
     * @return bool
     */
    function isAuthor($id) {
        if (empty($id))
            return FALSE;
        if (RDS::get()->userId == $this->getShowModel($id)->creatorId->id)
            return TRUE;
        return FALSE;
    }

    /**
     * Обновление статусов проектов
     * @param string $dateFrom начало изучаемого периода
     * @param string $dateTo кончание изучаемого периода
     */
    function updateStatuses($dateFrom = '', $dateTo = '') {
        $projects = $this->getListModel();
        foreach ($projects as $project) {
            $tracks = $project->getTracks($project->id);
            $pit = 0;
            foreach ($tracks as $track)
                $pit += (new Track)->getPiT($track->id);
            if ($pit <= 2)
                $project->status = 2;
            elseif ($pit <= 3)
                $project->status = 3;
            elseif ($pit <= 4)
                $project->status = 4;
            elseif ($pit <= 5)
                $project->status = 5;
            $project->save();
        }
    }
}
?>
