<?
class Comment extends CRUD {
	protected $modelClassName = 'CommentModel';
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

	function getListModel($where = '1') {
		/*$this->customize->conditions = "user_id = {$this->RDS->userId}";
		$this->filter = $this->buildFilter();
		$this->customize->conditions .= $this->filter->getResult(true, true);*/
        $this->customize->conditions = "$where";
        if ($where == '1')
            $this->customize->conditions .= " ORDER BY datewritten DESC";
            $this->getModelInstance()->getProxy()->setPager(new PagerHelperFull(10));
        $model = parent::getListModel();

		return $model;
	}

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

    /**
     * @param string $where необязательный параметр ранжирования выборки
     * @return string html-код страницы
     */
    function lists($where = '1'){
		//$this->buildFilter();
		//$this->customize->fields = 'id,author_id,datewritten,message,object_id,object_type,rating,track_sharing,complaint,com_to_com,complaint_author_id,complaint_date,status';
		$this->customize->conditions = "$where";
        if ($where == '1')
            $this->customize->conditions .= " ORDER BY datewritten DESC";

        $this->setView(VC_DTPL, 'commentListView.php');
		$data = parent::lists();
		return $data;
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
 		//$this->customize->fields = 'id,author_id,datewritten,message,object_id,object_type,rating,track_sharing,complaint,com_to_com,complaint_author_id,complaint_date,status';
		//$this->customize->conditions = "user_id = {$this->RDS->userId}";
		$data = parent::show($id);
		return $data;
	}
	*/

    function edit() {
        // Добавление комментария
        if (isset($_POST['operation']) && $_POST['operation'] == 'add') {
            // Обработка кходных данных
            $trackId = filter_var($_POST['object_id'], FILTER_SANITIZE_NUMBER_INT);
            if (isset($_POST['lid']) && is_numeric($_POST['lid'])) $trackId = $_POST['lid'];
            $comment = filter_var($_REQUEST['message'], FILTER_SANITIZE_MAGIC_QUOTES);
            $comment = strip_tags($comment);

            if (!isset($_POST['object_type']))
                $type = getPOSTfiltered('object_type', 'e', 'Invaid object type');
            elseif (empty($_POST['object_type']))
                $type = 'track';
            else $type = filter_var($_POST['object_type'], FILTER_SANITIZE_MAGIC_QUOTES);

            if (isset($_POST['answerid'])) {
                $answerId = getPOSTfiltered('answerid', 'n');
                if(!empty($_POST['authorid']))
                    $authorId = getPOSTfiltered('authorid', 'e');
                else
                    $authorId = "Удаленный пользователь";
                $datewritten = getPOSTfiltered('datewritten', 'e');

                $select = $this->getShowModel($answerId);

                $quote_link = 'QUOTE_LINK('.$type.','.$trackId.','.$answerId.')';
                // вместо всего сообщения, в качестве цитаты, вставляем редактированный её вариант
                if($quote_link != "photoalbumfile")
                    $comment = '<div class="quote"><a href='.$quote_link.'><b>'.$authorId.'</b> писал '.$datewritten.', <b>Цитата:</b></a>'."\n".'<i>'.getPOSTfiltered('original', 'e', 'Текст цитаты не может быть пустым').'</div></i>'."\n\n".$comment;
                else
                    $comment = '<div class="quote"><span><b>'.$authorId.'</b> писал '.$datewritten.', <b>Цитата:</b></span>'."\n".'<i>'.getPOSTfiltered('original', 'e', 'Текст цитаты не может быть пустым').'</div></i>'."\n\n".$comment;
            }

            $_POST['id'] = $trackId;
            $_POST['lastCommentDate'] = new SQLvar('NOW()');
            if($type == 'track') {
                (new Track)->edit();
                // обновить pit
                PIT::add($trackId, Registry::get('pit_comment'));
            }
            if($type == 'article')
                (new Article)->edit();

            $_POST = [
                'authorId'		=> RDS::get()->userId,
                'datewritten'	=> new SQLvar('NOW()'),
                'message'		=> $comment,
                'object_id'		=> $trackId,
                'object_type'	=> $type,
                'track_sharing'	=> isset($_POST['track_sharing']) ? 'true' : 'false',
                'com_to_com'    => isset($_POST['answerid']) ? (int)$_POST['answerid'] : 0
            ];
        }
        parent::edit();
        return (new handlingResult)->setRedirect('::reload');
    }

	/*
	function delete($id = null){
		//$this->customize->conditions = "user_id = {$this->RDS->userId}";
		return parent::delete($id);
	}
	*/
}
?>