<?
/**
 * Class DemoModel
 *
 * @property int id
 * @property string name
 * @property string type
 * @property string date
 */
class DemoModel extends DModelValidated {
	function __construct() {
		/*
		 * описать структуру полей
		 */
		$this
			->addProperty('id', 'int')
			->addProperty('name', 'string')
			->addProperty('type', 'enum', 'no,yes')
			->addProperty('date', 'datetime');
		/*
		 * задать ключевое поле (может быть только одно
		 */
		$this->keyName = 'id';
		/*
		 * задать прокси-объект для загрузки-выгрузки данных.
		 *
		 * прокси для связи с одной таблицей:
		 */
		$this->proxy = new DModelProxyDatabase();
		$this->proxy->setTableName('tablename');
		/*
		 * прокси для чтения и записи из нескольких связанны таблиц БД
		 */
		$this->proxy = new DModelProxyDatabaseJoins(); //todo спросить в скайпе
		$this->proxy->setTableName('tablename');
		/*
		 * прокси для чтения из БД произвольным запросом
		 */
		$this->proxy = new DModelProxyDatabaseCustom(''); //todo спросить в скайпе
	}
}
?>