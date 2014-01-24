<?

class Advert extends CRUD {
    protected $modelClassName = 'AdvertModel';

    /**
     * Получение списка секций
     * @param $limit int лимит выборки
     * @return array коллекция моделей секций
     */
    function getSections($limit = 0) {
        $limit = empty($limit) ? '' : " LIMIT 0,$limit";
        return (new DModelsCollection('AdvertSectionModel'))->load("1 ORDER BY placeOrder ASC$limit");
    }

    /**
     * Получение списка объявлений для заданной секции
     * @param int $id идентификатор секции
     * @return array коллекция моделей объявлений
     */
    function getSectionUnits($id = 0) {
        $id = empty($id) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : $id;
        return (new DModelsCollection('AdvertModel'))->load("sectionId = $id ORDER BY date DESC");
    }

    /**
     * Добавление секции
     * @return Object модель созданной секции
     */
    function addSection() {
        $section = new AdvertSectionModel();
        $section->name       = filter_var($_POST['name'], FILTER_SANITIZE_MAGIC_QUOTES);
        $section->placeOrder = $this->getSections()->count() + 1;
        $section->create();

        return $section->getAsStdObject();
    }

    /**
     * Удаление секции
     * @param int $id идентификатор секции
     * @return handlingResult
     */
    function removeSection($id = 0) {
        $id = empty($id) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : $id;
        $section = (new AdvertSectionModel)->load("id = $id");
        $section->delete();

        return (new handlingResult)->setStatus('Действие успешно выполнено');
    }
}