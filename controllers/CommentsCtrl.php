<?php
class CommentsCtrl extends DController
{

    function __construct()
    {
        $this->model = new CommentModel();
        $this->db = ObjectsPool::get('DataBase');
        $this->rds = ObjectsPool::get('RDS');
    }

    public function lists($objectType, $id, $page = null)
    {
        if (!in_array($objectType, $this->model->getProperty('object_type')->parameters)) {
            throw new Exception('Invalid object type requested');
        }
        if (!is_numeric($id)) {
            throw new Exception('Invalid id');
        }
        $sql = "object_type = '$objectType' AND object_id = $id";
        $sql .= " ORDER BY datewritten DESC";
        if ($page !== null) {
            $itemsPerPage = 20;
            $start = ($page - 1) * $itemsPerPage;
            $sql .= " LIMIT $start, $itemsPerPage";
        }
        $collection = (new DModelsCollection($this->model))->load($sql)->getAsStdObjects();
        return $collection;
    }

    public function create($authorId, $message, $object_type, $object_id, $track_sharing)
    {
        $data = array(
            "authorId" => $authorId,
            "message" => $message,
            "object_type" => $object_type,
            "object_id" => $object_id,
            "track_sharing" => $track_sharing
        );

        $this->model->sets($data);
        $this->model->create();
        return $this->model;
    }

}