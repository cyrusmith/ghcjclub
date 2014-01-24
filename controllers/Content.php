<?
class Content extends CRUD {
	protected $modelClassName = 'ContentModel';
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
	/*
	function lists(){
		//$this->buildFilter();
		//$this->customize->fields = 'id,content,page_id,label';
		//$this->customize->conditions = "1";
		$data = parent::lists();
		return $data;
	}
	*/
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
 		//$this->customize->fields = 'id,content,page_id,label';
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
}
?>