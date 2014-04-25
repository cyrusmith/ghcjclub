<?
class UsersCtrl extends DController {
	protected $model;

	function __construct() {
		$this->model = new UserModel();
	}
	function lists(){
//        $limit = Registry::get('topdeep');
//        $this->customize->conditions = "1 LIMIT 0,$limit";
		return (new DModelsCollection($this->model))->load('true limit 0,50');
	}
	function listsOnline(){
		// todo выбирать юзеров онлайн
		return (new DModelsCollection('UserModel'))->load('id IN (2, 4) limit 0,9');
	}
	function show($id) {
		return $this->model->load("id = $id");
	}
}
