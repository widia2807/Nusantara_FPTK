<?php
namespace App\Controllers;
use App\Models\DivisiModel;
use CodeIgniter\RESTful\ResourceController;

class Divisi extends ResourceController
{
    protected $modelName = DivisiModel::class;
    protected $format    = 'json';

    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    public function create()
    {
        $data = $this->request->getJSON(true);
        $this->model->insert($data);
        return $this->respondCreated($data);
    }
}
