<?
class Group extends CRUD{
    protected $modelClassName = 'GroupModel';
    /**
     * @var FilterForm
     */
    protected $filter;
    function buildFilter(){
        /*
         * Модель данных фильтра
         */
        $filter = $this->getModelInstance()
            ->maskPropertyList('title')
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
            $filterForm->add((new Button())
                    ->setLabel('Все')
                    ->addAttribute(sprintf('onclick="location=\'%s/%s\'"', CONFIG::$PATH_URL,'admin/articles'))
                    ->addAttribute('style="display: inline-block;"')
            );

        if (RDS::get()->is('admin'))
            $filterForm->setAction('admin/groups.html');
        else $filterForm->setAction('groups.html');

        $this->dataForView
            ->createPropertyByValue('filterModel', $filter)
            ->createPropertyByValue('filterSql', $sql)
            ->createPropertyByValue('filterForm', $filterForm);
    }
    function lists(){
        if (RDS::get()->is('admin')) {
            $this->buildFilter();
            $this->customize->fields = 'id,title,public,hide,creation_date,author_id';
            $this->getModelInstance()->getProxy()->setPager(new PagerHelperFull(10));
        }
        $this->customize->conditions = "{$this->dataForView->get('filterSql')->getSQL()} ORDER BY id DESC";
        $data = parent::lists();
        return $data;
    }
}
?>
