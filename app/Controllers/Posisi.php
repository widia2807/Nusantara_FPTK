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

     public function update($id = null)
    {
        if (!$id) return $this->failValidationErrors('ID kosong');

        $row = $this->model->find($id);
        if (!$row) return $this->failNotFound('Posisi tidak ditemukan');

        $data       = $this->request->getJSON(true) ?? [];
        $nama       = array_key_exists('nama_posisi', $data) ? trim((string)$data['nama_posisi']) : ($row['nama_posisi'] ?? '');
        $id_divisi  = array_key_exists('id_divisi', $data) ? (int)$data['id_divisi'] : (int)$row['id_divisi'];

        if ($nama === '' || !$id_divisi) {
            return $this->failValidationErrors('id_divisi dan nama_posisi wajib');
        }

        $this->model->update($id, [
            'id_divisi'   => $id_divisi,
            'nama_posisi' => $nama,
        ]);

        return $this->respond(['message' => 'updated']);
    }

    public function delete($id = null)
    {
        if (!$id) return $this->failValidationErrors('ID kosong');
        if (!$this->model->find($id)) return $this->failNotFound('Posisi tidak ditemukan');

        $this->model->delete($id);
        return $this->respondDeleted(['message' => 'deleted']);
    }
}
