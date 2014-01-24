<?/**
 * @property int $id 
 * @property int $userId 
 * @property datetime $date 
 * @property int $sum 
 * @property text $comment 
 */
class PaymentsLogModel extends DModelValidated {
	function __construct($fillWithDefault = DModel::DONT_FILL_WITH_DEFAULTS) {
		$this->keyName = 'id';
		/*
		 * структура таблицы
		 */
		$this
			->addProperty('id', 'int', "11", null)
			->addProperty('userId', 'int', "10")
			->addProperty('date', 'datetime', "")
			->addProperty('sum', 'int', "11")
			->addProperty('comment', 'text', "")
		;
		parent::__construct($fillWithDefault);
	}
	/**
	 * связываем с таблицей БД
	 * @return DModelProxy
	 */
	protected function createProxy() {
		$fields = 'id,userId,date,sum,comment';
		return (new DModelProxyDatabase('payments_log'))
			->setFieldsRead($fields)
			->setFieldsWrite($fields);
	}
	/*
	public function getterConversions($field, $value) {
		$value = parent::getterConversions($field, $value);
		switch ($field) {
			case 'userId':
				break;
			case 'date':
				break;
			case 'sum':
				break;
			case 'comment':
				break;
		}
		return $value;
	}
	*/
	/*
	public function setterConversions($field, $value) {
		switch ($field) {
			case 'userId':
				break;
			case 'date':
				break;
			case 'sum':
				break;
			case 'comment':
				break;
		}
		return parent::setterConversions($field, $value);
	}
	*/
}
?>