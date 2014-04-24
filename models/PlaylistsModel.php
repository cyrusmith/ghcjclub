<?

/**
 * Class DemoModel
 *
 * @property int id
 * @property int userId
 * @property string name
 * @property string public
 */
class PlaylistsModel extends DModelValidated
{

    /**
     * @var DataBase
     */
    private $db;


    private $trackTable = "playlists_have_tracks";

    function __construct()
    {
        /*
         * описать структуру полей
         */
        $this
            ->addProperty('id', 'int', 11)
            ->addProperty('userId', 'int', 11)
            ->addProperty('name', 'varchar', 255)
            ->addProperty('public', 'enum', 'yes,no');
        /*
         * задать ключевое поле (может быть только одно
         */
        $this->keyName = 'id';

        $this->db = ObjectsPool::get('DataBase');
    }

    /**
     * Подгужаем ид трэков
     */
    public function getTrackIds()
    {
        $arr = $this->db->select($this->trackTable, "trackId", 'listId = ' . intval($this->id), DB_SELECT_ASC);
        $ret = array_values($arr);
        return $ret;
    }


    /**
     * связываем с таблицей БД
     * @return DModelProxy
     */
    protected function createProxy()
    {
        $fields = 'id,userId,name,public';
        return (new DModelProxyDatabase('playlists'))
            ->setFieldsRead($fields)
            ->setFieldsWrite($fields);
    }

    /**
     * Очищаем лист от треков
     */
    private function clearList()
    {
        $this->db->delete($this->trackTable, "listId = " . intval($this->id));
    }

    /**
     * Полностью переписывает все трэки в листе
     * @param $trackIds
     */
    function setTracks($trackIds)
    {
        $this->clearList();
        $order = 0; //TODO order плохо используется
        foreach ($trackIds as $trackId) {
            //TODO Предлагаю в класс дб добавить multiInsert
            $this->db->insert($this->trackTable, array(
                "trackId" => intval($trackId),
                "listId" => intval($this->id),
                "order" => $order++
            ));
        }
    }

    function afterDelete()
    {
        $this->clearList();
    }
}

?>