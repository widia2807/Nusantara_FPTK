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

      public function update($id = null)
    {
        if (!$id) return $this->failValidationErrors('ID kosong');

        $row = $this->model->find($id);
        if (!$row) return $this->failNotFound('Divisi tidak ditemukan');

        $data = $this->request->getJSON(true) ?? [];
        $nama = array_key_exists('nama_divisi', $data) ? trim((string)$data['nama_divisi']) : ($row['nama_divisi'] ?? '');

        if ($nama === '') return $this->failValidationErrors('nama_divisi wajib');

        $this->model->update($id, ['nama_divisi' => $nama]);
        return $this->respond(['message' => 'updated']);
    }

    public function delete($id = null)
    {
        if (!$id) return $this->failValidationErrors('ID kosong');
        if (!$this->model->find($id)) return $this->failNotFound('Divisi tidak ditemukan');

        $this->model->delete($id);
        return $this->respondDeleted(['message' => 'deleted']);
    }
}
