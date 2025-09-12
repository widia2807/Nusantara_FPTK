<?php
namespace App\Controllers;
use App\Models\PosisiModel;
use CodeIgniter\RESTful\ResourceController;

class Posisi extends ResourceController
{
    protected $modelName = PosisiModel::class;
    protected $format    = 'json';

    public function index()
    {
        $id_divisi = $this->request->getGet('id_divisi');
        if ($id_divisi) {
            return $this->respond($this->model->where('id_divisi', $id_divisi)->findAll());
        }
        return $this->respond($this->model->findAll());
    }

    public function create()
    {
        $data = $this->request->getJSON(true);
        $this->model->insert($data);
        return $this->respondCreated($data);
    }
}
