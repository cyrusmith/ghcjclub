<?

/**
 * Class PlaylistsCtrl
 * Контроллер управления трэк листами
 *
 * Все методы начинающиеся с action отвечают за обработку запросов
 *
 * Связь URL и HTTP-метода запроса с контроллером осуществляется в роутере.
 * funcs/routers.php
 *
 * Router::get()->connect('HTTP-метод', 'регулярное выражение для URL', 'Класс контроллера/метод контроллера');
 * HTTP-метод может быть:
 *
 * Router::$GET
 * Router::$POST
 * Router::$PUT
 * Router::$DELETE
 *
 * регулярное выражение для URL может содержать именованные группы, тогда значение группы будет передано в метод контроллера с таким же именем:
 * Router::get()->connect(Router::$PUT, '^projects/(?<projectId>\d+)?$', 'DemoCtrl/doAction');
 *
 * значение projectId будет подставлено, как аргумент $projectId метода doAction
 *
 *
 * На уровне ядра прописана проверка уровня доступа "access.{Класс контроллера}.{метод контроллера}"
 */
class PlaylistsCtrl extends DController
{
    /**
     * В дальнейшем перейдем на использование Dependency Injection,
     * поэтому уже сейчас надо в конструкторе инициализировать все свойства,
     * чтобы затем переписать это на присвоение из аргументов конструктора
     */

    /**
     * @var DataBase
     */
    private $db;
    /**
     * @var RDS
     */
    private $rds;
    /**
     * @var PlaylistsModel
     */
    private $model;

    function __construct()
    {
        $this->db = ObjectsPool::get('DataBase');
        $this->rds = ObjectsPool::get('RDS');

        $this->model = new PlaylistsModel();
    }

    /**
     * Создание нового листа
     * @param $name
     * @param $userId
     * @param $public
     * @return playlist Id
     */
    function actionNew($name, $userId, $public)
    {
        $this->model->sets(array(
            "name" => $name,
            "userId" => $userId,
            "public" => $public
        ));

        $this->model->create();

        //   $playlistId = $this->db->lastId(); // Магия | Лучшего способа нет ?
        $playlistId = $this->model->id;

        return $playlistId;
    }

    /**
     * Загрузка модели плейлиста по ид
     * @param $id
     * @throws Exception
     */
    private function loadUsingId($id)
    {
        $this->model->load('id = ' . intval($id));
        if (!$this->model->id) {
            throw new Exception("Track list was not found");
        }
    }

    /**
     * Сохраняет треклист в дб
     * @param $id
     * @param $name
     * @param $public
     * @throws Exception
     */
    function actionUpdate($id, $name, $public)
    {
        $this->loadUsingId($id);
        $this->model->sets(array(
            "name" => $name,
            "public" => $public
        ));
        $this->model->save();
    }

    /**
     * Удаление по ид
     * @param $id
     */
    function actionDelete($id)
    {
        $this->loadUsingId($id);
        $this->model->delete();
    }


    /**
     * Полностью перезаписывает все трэки в листе (Старые удаляет)
     * @param $listId
     * @param array $trackIds
     * @throws Exception
     */
    function actionTracksSet($listId, $trackIds)
    {
        if (!is_array($trackIds)) {
            throw new Exception("trackIds must be an array");
        }
        $this->loadUsingId($listId);
        $this->model->setTracks($trackIds);
    }


    /**
     * Для проверки прав доступа
     * @param $userId
     * @throws Exception
     */
    private function compareLoginedUserId($userId)
    {
        if ($this->rds->userId != $userId) {
            throw new Exception("access denied");
        }
    }

    /**
     * Список плейлистов юзера
     * @param $userId
     * @return array Массив {id,name}
     * @throws Exception
     */

    function actionList($userId)
    {
        $this->compareLoginedUserId($userId);
        $listOfModels = new DModelsCollection($this->model);
        $listOfModels->load('userId = ' . intval($userId));
        $list = array();
        foreach ($listOfModels as $model) {
            $list[] = array(
                "id" => $model->id,
                "name" => $model->name
            );
        }
        return $list;
    }

    function actionTracksList($listId, $fullInfo = "false")
    {
        $fullInfo = $fullInfo == "true";
        $list = array();
        $this->loadUsingId($listId);
        if ($fullInfo) {
            $this->compareLoginedUserId($this->model->userId);
            $tracks = new DModelsCollection('TrackModel');
            $trackIds = $this->model->getTrackIds();
            $in = "(" . implode(', ') . ")"; //TODO Хорошо бы такое вносить в библиотеку
            $tracks->load('id in ' . $in);
            /** @var $track TrackModel */
            foreach ($tracks as $track) {
                $list[] = $track->getAsStdObject();
            }
        } else {
            $list = $this->model->getTrackIds();
        }
        return $list;
    }

}

?>