<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PengajuanModel;

class Pengajuan extends ResourceController
{
    protected $modelName = PengajuanModel::class;
    protected $format    = 'json';

    // GET /api/pengajuan
    public function index()
    {
        $perPage = (int) ($this->request->getGet('perPage') ?? 20);
        $data = $this->model->getWithRelations($perPage);
        return $this->respond([
            'data'  => $data,
            'pager' => [
                'total'        => $this->model->pager->getTotal(),
                'perPage'      => $perPage,
                'currentPage'  => $this->model->pager->getCurrentPage(),
                'pageCount'    => $this->model->pager->getPageCount(),
            ],
        ]);
    }

    // Helper ambil input (JSON, raw, atau form)
    private function input(): array
    {
        $d = $this->request->getJSON(true);
        if ($d === null) $d = $this->request->getRawInput();
        if (!$d) $d = $this->request->getPost();
        return $d ?? [];
    }

    // POST /api/pengajuan
    public function create()
    {
        $data = $this->input() + [
            'status_hr'         => 'Pending',
            'status_management' => 'Pending',
            'status_rekrutmen'  => 'Pending',
        ];

        if (!$this->model->insert($data, true)) {
            return $this->failValidationErrors($this->model->errors());
        }
        $id = $this->model->getInsertID();
        return $this->respondCreated($this->model->find($id));
    }

    // GET /api/pengajuan/{id}
    public function show($id = null)
    {
        $row = $this->model->findWithRelations($id);
        return $row ? $this->respond($row) : $this->failNotFound('Pengajuan not found');
    }

    // PUT/PATCH /api/pengajuan/{id}
    public function update($id = null)
    {
        $data = $this->input();
        if (!$this->model->update($id, $data)) {
            return $this->failValidationErrors($this->model->errors());
        }
        return $this->respond($this->model->find($id));
    }

    // DELETE /api/pengajuan/{id}
    public function delete($id = null)
    {
        if (!$this->model->find($id)) return $this->failNotFound('Pengajuan not found');
        $this->model->delete($id);
        return $this->respondDeleted(['id' => $id]);
    }

    // --- Actions khusus status ---
    private function updateStatus($id, $field, $allowed)
    {
        $data = $this->input();
        $val  = $data[$field] ?? null;
        if (!in_array($val, $allowed, true)) {
            return $this->failValidationErrors("$field invalid");
        }
        if (!$this->model->update($id, [$field => $val])) {
            return $this->failValidationErrors($this->model->errors());
        }
        return $this->respond(['message' => "$field updated", 'data' => $this->model->find($id)]);
    }

    public function hrReview($id = null)         { return $this->updateStatus($id, 'status_hr', ['Pending','Approved','Rejected']); }
    public function managementReview($id = null) { return $this->updateStatus($id, 'status_management', ['Pending','Approved','Rejected']); }
    public function rekrutmenReview($id = null)  { return $this->updateStatus($id, 'status_rekrutmen', ['Pending','Selesai']); }
}
