<?php
namespace App\Controllers;
use App\Models\PengajuanModel;
use CodeIgniter\RESTful\ResourceController;

class Pengajuan extends ResourceController
{
    protected $modelName = PengajuanModel::class;
    protected $format    = 'json';

    public function create()
    {
        $data = $this->request->getJSON(true);
        $this->model->insert($data);
        return $this->respondCreated($data);
    }

    public function show($id = null)
    {
        $pengajuan = $this->model->find($id);
        if (!$pengajuan) return $this->failNotFound("Pengajuan not found");
        return $this->respond($pengajuan);
    }

    public function hrReview($id = null)
    {
        $data = $this->request->getJSON(true);
        $this->model->update($id, ['status_hr' => $data['status_hr']]);
        return $this->respond(["message" => "HR review updated"]);
    }

    public function managementReview($id = null)
    {
        $data = $this->request->getJSON(true);
        $this->model->update($id, ['status_management' => $data['status_management']]);
        return $this->respond(["message" => "Management review updated"]);
    }

    public function rekrutmenReview($id = null)
    {
        $data = $this->request->getJSON(true);
        $this->model->update($id, ['status_rekrutmen' => $data['status_rekrutmen']]);
        return $this->respond(["message" => "Rekrutmen review updated"]);
    }
}
