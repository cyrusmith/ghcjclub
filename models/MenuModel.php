<?/**
 * @property int $id
 * @property varchar $pagetype
 * @property varchar $foldername
 * @property varchar $pagename
 * @property text $meta
 */
class MenuModel extends DModelValidated {
    function __construct() {
        $this->keyName = 'id';
        /*
         * структура таблицы
         */
        $this
            ->addProperty('id', 'int', "10", null)
            ->addProperty('pagetype', 'varchar', "50", null)
            ->addProperty('foldername', 'varchar', "50", null)
            ->addProperty('pagename', 'varchar', "255", null)
            ->addProperty('pagename', 'varchar', "255", null)
            ->addProperty('meta', 'text')
        ;
        parent::__construct();
    }
    /**
     * связываем с таблицей БД
     * @return DModelProxy
     */
    protected function createProxy() {
        return new DModelProxyDatabase('menu');
    }
}