<?
class Menu extends CRUD {
    protected $modelClassName = 'MenuModel';

    function show($id = NULL) {
        if (isset($_REQUEST['action']) && is_string($_REQUEST['action'])) {
            $folderName = filter_var($_REQUEST['action'], FILTER_SANITIZE_MAGIC_QUOTES);
            $pageModel  = (new MenuModel)->load("foldername = '$folderName'");

            if (isset($pageModel->id)) { // Статические страницы для просмотра
                $this->setView(VC_DTPL, 'staticPageView.php');
                Page::setName($pageModel->pagename);
                $contentModel = (new ContentModel)->load("page_id = {$pageModel->id}");
                $data = new stdClass();
                $data->id       = $pageModel->id;
                $data->pagename = $pageModel->pagename;
                $data->content  = (new WikiParser)->parse($contentModel->content);
                return renderTemplate('staticPageView.php', $data);
            } else if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) // Все страницы
                return parent::show($_REQUEST['id']);
        }

        return '';
    }

    /**
     * Полчучение списка статичных страниц
     * @return array массив ссылок на статичные старницы
     */
    function getStaticPages() {
        $this->customize->conditions = "pagetype = 'text' ORDER BY pagename";
        $models = $this->getListModel();
        $pages = [];
        foreach ($models as $model)
            $pages[$model->id] = $model->foldername;
        return $pages;
    }

    /**
     * Получение списка типов статей
     * @return DModelsCollection коллекция моделей типов статей
     */
    function getArticlesNodes() {
        $this->customize->conditions = "pagetype = 'ArticlesNode' ORDER BY pagename";
        return $this->getListModel();
    }
}