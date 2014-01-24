<?
class Article extends CRUD {
	protected $modelClassName = 'ArticleModel';
	/**
	 * @var FilterForm
	 */
	protected $filter;
	function buildFilter() {
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
        if (isset($_REQUEST['public_date_end']))
            $date_end = ' AND public_date <= "'.$_REQUEST['public_date_end'].' 23:59:59"';
        else $date_end = '';
        $sql->setPatternFor('public_date', 'public_date >= "%2$s 00:00:00"'.$date_end);
        if (isset($_REQUEST['tags']) && !empty($_REQUEST['tags'])) {
            $ids = (new Article)->getListModel();
            $ids->getModelInstance()->maskPropertyList('id')->setProxy(new ArticleModelProxyForTag)->load($_REQUEST['tags']);
            $sql->setPatternFor('id', 'id IN ('.implode(',', $ids->getAsHash('id')).')');
        }

		/*
		 * форма
		 */
		$filterForm = DForm::generateByModel($filter);
		$filterForm->generateLabels('filter_');
		$filterForm
			->addAttribute('class="filter"')
			->addAttribute('method="get"');
		$filterForm->add(
			(new DateInput)
				->setName('public_date_end')
                ->setValue(date("Y-m-d"))
                ->setLabel('Опубликовано раньше')
        );
        $tags = (new Tag)->getListModel();
        $tags->getModelInstance()->maskPropertyList('id,value');
		$filterForm->add(
			(new Selector)
				->setName('tags')
                ->addOption('', '')
                ->addOptions($tags->getAsHash('value'))
                ->setLabel('Тэг')
        );
		$filterForm->add(
			(new StaticInput)
				->setValue(sprintf('<div id="title" class="title">%s</div>', s('Фильтр'))),
			DForm::$PREPEND // вставить заголовок в начало формы
        );
        // Кнопки применения и сброса фильтра
        $filterForm->add((new Submit)->setLabel('Применить')->addAttribute('style="display: inline-block;"'));
        if (count($_REQUEST) > 1)
            $filterForm->add((new Button())
                ->setLabel('Все')
                ->addAttribute(sprintf('onclick="location=\'%s/%s\'"', CONFIG::$PATH_URL,'admin/articles'))
                ->addAttribute('style="display: inline-block;"')
            );

        if (RDS::get()->is('admin'))
		    $filterForm->setAction('admin/articles.html');
        else $filterForm->setAction('articles.html');

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
    /**
     * Формирование карты построения формы списка статей
     * @param DModelsCollection $model коллекция моделей для вывода списка статей
     * @return array отредактированная карта построения формы списка статей
     */
    public function getListModelToFormMap(DModelsCollection $model) {
		$map = parent::getListModelToFormMap($model);
        if (RDS::get()->is('admin'))
		    $map['projectId'] = 'AuthorIdView';
		return $map;
	}

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

	function lists() {
        Page::setName('Статьи');
        $this->getModelInstance()->getProxy()->setPager(new PagerHelperFull(10));

        // Админка
        if (RDS::get()->is('admin') && strpos($_REQUEST['action'], 'admin') !== FALSE) {
            $this->buildFilter();
		    $this->customize->fields     = 'id,projectId,public_date,subject,shorttext,forum_topic_id,points,draft_checkbox,main_blog_checkbox';
            $this->customize->conditions = "{$this->dataForView->get('filterSql')->getSQL()} ORDER BY public_date DESC";
        } else  { // Всё остальное
            $this->setView(VC_DTPL, 'articleListView.php');
            $this->customize->fields = 'id,projectId,public_date,subject,shorttext,main_blog_checkbox,points,points_minus';

            // Фильтрация списка
            $where = "";
            if ((isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) || (isset($_REQUEST['project_id']) && is_numeric($_REQUEST['project_id']))) {
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $_REQUEST['project_id'];
                $where .= "projectId = ".$id;
            }
            if (empty($where)) $where = "true";

            $this->customize->conditions = "$where ORDER BY public_date DESC";
        }
		$data = parent::lists();
		return $data;
	}

	function getShowModelToFormMap(DModel $model) {
		$map = parent::getShowModelToFormMap($model);
        $map['subject']     = 'TextInput';
        $map['tags_cached'] = 'TagsView';

        if (RDS::get()->is('admin')) {
		    $map['projectId'] = 'AuthorIdView';
        } else {
            //$map['projectId'] = 'ProjectSelector'; // if (!empty($model->id)) - редактирование
            $map['projectId'] = 'EmptyView';
        }
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
            'projectId'   => 'Проект',
            'subject'     => 'Загловок',
            'shorttext'   => 'Анонс',
            'maintext'    => 'Текст',
            'public_date' => 'Дата публикации',
            'draft_checkbox'     => 'Черновик',
            'main_blog_checkbox' => 'Важный блог',
            'comments_blocked'   => 'Запретить комментирование',
            'forum_topic_id'     => 'Тема на форуме',
            'tags_cached'  => 'Популярные теги',
            'points'       => 'Количество плюсов',
            'points_minus' => 'Количество минусов',
            'pointspaid'   => 'Какие-то очки',
            'nopoints'     => 'Ещё какие-то очки',
            'linkToListPage'     => ''//'Обратно',
        ];
        foreach ($form as $element) {
            if ($element instanceof LabeledInput) {
                // Назначение меток
                $name = $element->getName();
                if ($name != 'linkToListPage')
                    $element->setLabel($labels[$name]);

                // Удаление меток у пустых полей
                if (get_class($element) == 'EmptyView')
                    $element->setLabel('');

                // Для публикации статьи в текущем проекте
                if ($name == 'projectId' && !RDS::get()->is('admin') && RDS::get()->isLogged)
                    $form->add((new HiddenInput)->setName('projectId')->setValue(RDS::get()->config->curProjectId));
            }
        }
		return $form;
	}

	function show($id = null) {
 		$this->customize->fields = 'id,projectId,public_date,subject,shorttext,maintext,forum_topic_id,points,pointspaid,nopoints,points_minus,draft_checkbox,main_blog_checkbox,comments_blocked,tags_cached';
		//$this->customize->conditions = "user_id = {$this->RDS->userId}";
        if ((!RDS::get()->is('admin') || strpos($_REQUEST['action'], 'admin') === FALSE) && $id !== NULL) {
            if (!isset($_REQUEST['edit']) || !RDS::get()->isLogged) {
                $article = $this->getShowModel($id);
                $this->setView(VC_DTPL, 'articleView.php');
                Page::setName($article->subject);
            } elseif (RDS::get()->isLogged) {
                $this->setView(VC_DTPL, 'articleEdit.php');
                Page::setName("Редактировать статью");
            }
        } elseif ($id == null) {
            Page::setName("Написать статью");
            $this->setView(VC_DTPL, 'articleEdit.php');
            $this->customize->fields = 'id,projectId,subject,shorttext,maintext,draft_checkbox,main_blog_checkbox,comments_blocked,tags_cached';
        }

		$data = parent::show($id);
		return $data;
	}

    function edit() {
        parent::edit();
        return (new HandlingResult)->setRedirect('/blogs/'.RDS::get()->config->curProjectId);
    }

	/*
	function delete($id = null) {
		//$this->customize->conditions = "user_id = {$this->RDS->userId}";
		return parent::delete($id);
	}
	*/



    /**
     * Удаление статьи
     * @param $id идентификатор статьи
     * @return handlingResult сообщение о результате удаления
     */
    function deleteByOwner($id){
        $_GET['ids'] = array($id);
        $this->delete();
        return new handlingResult('Запись блога удалена');
    }

    /**
     * Создание события удаление статьи
     * @return handlingResult
     */
    function deleteRequest(){
        $id = getPOSTfiltered('id', 'n', 'Некорректный ID');
        if (!$this->isAuthor($id)) accessDenied();
        $key = defferedActions::insert('articles/deleteByOwner', $id);

        $article = $this->getShowModel($id);
        $mailData = [
            'id'   => $id,
            'name' => $article->name,
            'key'  => $key
        ];
        Mailer::push(new MailLetter($mailData, 'deletearticle'), RDS::get()->userInfo->email, MAIL_INSTANT);

        return new handlingResult('На ваш email отправлено письмо с ключом удаления');
    }

    /**
     * Определение авторства
     * @param $id int идентификатор статьи
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

    /**
     * Получение комментариев к статье
     * @param $id идентификатор статьи
     * @param string $limit ограничения выборки
     * @return DModelsCollection коллекция моделей комментариев к заданноой статье
     */
    function getComments($id, $limit = '') {
        return (new Comment)->getListModel('object_id = '.$id.' AND object_type = "article" ORDER BY datewritten DESC'.$limit);
    }

    /**
     * Получение списка похожих статей
     * @param $id идентификатор статьи
     * @return array массив моделей похожих статейы
     */
    function getLike($id) {
        //Получаем теги, относящиеся к текущей статье
        $tags = (new Tag)->getForArticle($id);
        //Проверяем колличество повторений каждого тега, записываем в переменную
        $tags_array = array();
        foreach($tags as $key) {
            if((new TagModel)->load("id = {$key->id}")->system == "false") {
                $ids = (new Article)->getListModel();
                $ids->getModelInstance()->maskPropertyList('id')->setProxy(new ArticleModelProxyForTag)->load($key->id);
                $tags_num = $ids->count();
                if($tags_num > 1)
                    $tags_array[$key->id] = $tags_num;
            }
        }
        //Сортируем теги по популярности (количеству повторений), сначала самые не популярные
        asort($tags_array);

        $have_articles = 0;
        $show_articles = [];
        //Из каждого "общего" тега выбираем по статье
        foreach($tags_array as $key => $value) {
            if($have_articles == 5) break;
            $articles_with_tag = $this->getListModel();
            $articles_with_tag->getModelInstance()->maskPropertyList('id')->setProxy(new ArticleModelProxyForTag)->load($key);

            foreach($articles_with_tag as $unit) {
                if($have_articles == 5) break;
                $show_articles[$unit->id] = $unit->id;
                $have_articles++;
            }
        }

        //Если "похожих" статей каким-то образом у нас меньше 5, то добираем еще, добавляя самые популярные
        while($have_articles != 5) {
            $need_articles = 5 - $have_articles;
            $sideNews = $this->getTagged('index', $need_articles);
            foreach($sideNews as $key) {
                $show_articles[$key->id] = $key->id;
                $have_articles++;
            }
        }

        //Собираем данные в массив
        $articles_output = [];
        foreach($show_articles as $key)
            $articles_output[$key] = (new Article)->getShowModel($key);

        return $articles_output;
    }

    /**
     * Список лучших статей с тегами
     * @param $tags строка тегов, перечисленных через запятую
     * @param null $limit ограничение по количесву статей
     * @param bool $invertTags теги, которые не должны быть проанализированы при отборе
     * @return mixed html-код списка лучший статей
     */
    function getTagged($tags, $limit = null, $invertTags = false) {
        $params = ['tags' => $tags, 'limit' => $limit, 'invertTags' => $invertTags];

        $this->getModelInstance()
	        ->maskPropertyList('id,projectId,subject,shorttext,public_date,points')
	        ->setProxy(new ArticleModelProxyForBest);

        $this->customize->conditions = $params;
        return $this->getListModel();
    }

    /**
     * Построение списка статей, имеющих теги
     * @return mixed список статей
     */
    function listsNode(){
        $tag = filter_var($_REQUEST['action'], FILTER_SANITIZE_MAGIC_QUOTES);
        if ($tag[strlen($tag) - 1] == 's')
            $tag = substr($tag, 0, strlen($tag) - 1);

        $data = $this->getListModel();
        $data->getModelInstance()->setProxy(new ArticleModelProxyForTag)->load($tag);
        $data->getModelInstance()->getProxy()->setPager(new PagerHelperFull(10));

        $class = new stdClass();
        $class->model = $data;
        return renderTemplate('articleListView.php', $class);
    }

    /**
     * Установка главного блога для проекта
     * @return handlingResult сообщение об успехе
     * @throws Exception_CMS сообщение об ошибке
     */
    function setMainBlog(){
        $id = getPOSTfiltered('id', 'n', 'Invalid ID');

        if ($this->isAuthor($id))
            throw new Exception_CMS('Нельзя ставить главным чужой блог');
        $pid = $this->getShowModel($id)->projectId;

        $article = (new ArticleModel)->load("id = $id AND projectId = $pid");
        $article->main_blog_checkbox = 'false';
        $article->save();

        $article = $this->getShowModel($id);
        $article->main_blog_checkbox = 'true';
        $article->save();

        $res = new handlingResult('Блог установлен главным');
        $res->setRedirect('::reload');
        return $res;
    }

    /**
     * Построение страници статьи с тегами
     * @param $id идентификатор статьи
     * @return stdClass страница статьи
     */
    function showNode($id){
        $this->setView(VC_DTPL, 'articleView.php');
        return parent::show($id);
    }
}

/**
 * Вводится новое виртуальное поле "тэги"
 */
class TagsView extends StaticInput{
    function getHtml() {
        $tags = (new Tag)->getListModel();
        $tags->getModelInstance()->maskPropertyList('id,value');
        $checkboxes = (new CheckboxesGroup)
            ->setName('tags')
            ->addOption('', '')
            ->addOptions($tags->getAsHash('value'))
            ->setLabel('Тэги')->getHtml();
        return "<div style='height: 314px; overflow: scroll;'>$checkboxes</div>";
    }
}
?>