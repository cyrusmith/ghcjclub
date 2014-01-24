<?php
class Tag extends CRUD {
    protected $modelClassName = 'TagModel';
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

    function getListModel() {
        $collection = parent::getListModel();
        if (RDS::get()->is('admin') && strpos($_REQUEST['action'], 'admin') !== FALSE) {
            $this->customize->conditions = "true ORDER BY value";
            $collection->getModelInstance()->maskPropertyList('id,value,code');
        }
        return $collection;
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
    /*
    function lists(){
        //$this->buildFilter();
        //$this->customize->fields = 'id,name,href';
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
     * Получение списка тегов к статье по заданному идентификатору
     * @param $id идентификатор статьи
     * @return DModelsCollection коллекция тэгов заданной статьи
     */
    function getForArticle($id) {
        $linkModel = new DModelDynamic(array(
            new DModelProperty('tag_id', 'int'),
            new DModelProperty('article_id', 'int'),
        ));
        $linkModel->setProxy(new DModelProxyDatabase('articles_has_tags'));
        $linkModels = new DModelsCollection($linkModel);
        $linkModels->load('article_id = '.$id.' ORDER BY tag_id');
        $tids = [];
        foreach ($linkModels as $unit)
            $tids[] = $unit->tag_id;
        $tids = implode(',', $tids);
        if (empty($tids)) $tids = '0';
        $this->customize->conditions = 'id IN ('.$tids.') ORDER BY id';
        return $this->getListModel();
    }

    /**
     * Построение формы поиска блогов
     * @return string html-код формы
     */
    function getBlogSearchForm() {
        Page::setName("Блоги");
        $html = '<form class="search search_tags search_tags_full" action="#">
                <input type="submit" value="найти" class="search_btn">
                <input type="text" onblur="if(this.value == \'\') this.value = \'Поиск по тегам\';" onfocus="if(this.value==\'Поиск по тегам\') this.value = \'\';" value="Поиск по тегам" name="qtags" class="search_txt">
            </form>';
        if (isset($_REQUEST['tag']))
            $html .= '<div class="b searched_tags">
                    <i class="ico2 ico2_tags_toggle ico2_tags_toggle__open" onclick="$(\'#tags\').slideToggle(300); $(this).toggleClass(\'ico2_tags_toggle__open\');"></i>
                    <span class="searched_tags__title">Выбранный тэг:</span> <a class="link_g" href="articles.html">'.base64_decode(urldecode($_REQUEST['tag'])).'</a>
                </div>';
        $class = isset($_REQUEST['tag']) ? ' hide' : '';
        $html .= '<article class="b'.$class.'" id="tags"><div class="tags"><ul class="tags_list tags_list__selector">';
        $minSize = 13.0;
        $maxSize = 30.0;
        $sizeRange = $maxSize - $minSize;
        $tagsCloud = json_decode( Registry::get('blogTagsCloud'));
        $minCount = log($tagsCloud->min + 1);
        $maxCount = log($tagsCloud->max + 1);
        $countRange = $maxCount - $minCount;
        foreach ($tagsCloud->list as $tag) {
            $size = $minSize + (log($tag->count + 1) - $minCount) * $sizeRange / $countRange;
            $html .= "<li>
                    <a href='blogs.html?tag=".urlencode(base64_encode($tag->value))."' style='font-size:".str_replace(',', '.', round($size, 2))."px'>".$tag->value."</a>
                </li>
            ";
        }
        $html .= '</ul></div></article>';
        return $html;
    }
}
