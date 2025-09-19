<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PengajuanModel;
use App\Models\HistoryModel;
use App\Models\RangeGajiModel;

class Pengajuan extends ResourceController
{
    protected $modelName = PengajuanModel::class;
    protected $format    = 'json';
    protected $history;
    protected $rangeGaji;

    public function __construct()
    {
        $this->history  = new HistoryModel();
        $this->rangeGaji = new RangeGajiModel();
    }

    // =====================
    // GET /api/pengajuan
    // =====================
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

    // =====================
    // POST /api/pengajuan
    // =====================
    public function create()
    {
        $data = $this->input() + [
            'status_hr'         => 'Pending',
            'status_management' => 'Pending',
            'status_rekrutmen'  => 'Pending',
            'archived'          => 0,
            'needs_hr_check'    => 0,
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

    // PUT /api/pengajuan/{id}
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

    // =====================
    // REVIEW HR
    // =====================
    public function hrReview($id = null)
    {
        $data = $this->input();
        $pengajuan = $this->model->find($id);
        if (!$pengajuan) return $this->failNotFound('Pengajuan tidak ditemukan');

        $status  = $data['status_hr'] ?? null;
        $minGaji = $data['min_gaji'] ?? null;
        $maxGaji = $data['max_gaji'] ?? null;
        $comment = $data['comment'] ?? null;

        if (!$minGaji || !$maxGaji) {
            return $this->failValidationErrors("Range gaji wajib diisi");
        }
        if ($status === 'Rejected' && !$comment) {
            return $this->failValidationErrors("Comment wajib diisi jika Reject");
        }
        if ($pengajuan['status_hr'] !== 'Pending') {
            return $this->fail("HR sudah memberi keputusan, tidak bisa diubah lagi");
        }

        // Update pengajuan
        $this->model->update($id, [
            'status_hr' => $status,
            'min_gaji'  => $minGaji,
            'max_gaji'  => $maxGaji,
            'comment'   => $comment,
        ]);

        // Insert/Update range gaji
        $this->rangeGaji->where('id_pengajuan', $id)->delete();
        $this->rangeGaji->insert([
            'id_pengajuan' => $id,
            'min_gaji'     => $minGaji,
            'max_gaji'     => $maxGaji,
        ]);

        // Catat history
        $this->history->insert([
            'id_pengajuan' => $id,
            'id_user'      => session()->get('id_user') ?? null,
            'role_user'    => 'HR',
            'action'       => $status,
            'comment'      => $comment,
        ]);

        // Kalau reject langsung archive
        if ($status === 'Rejected') {
            $this->model->update($id, ['archived' => 1]);
        }

        return $this->respond(['message' => 'Review HR berhasil']);
    }

    // =====================
    // REVIEW MANAGEMENT
    // =====================
    public function managementReview($id = null)
    {
        $data = $this->input();
        $pengajuan = $this->model->find($id);
        if (!$pengajuan) return $this->failNotFound('Pengajuan tidak ditemukan');

        $status  = $data['status_management'] ?? null;
        $comment = $data['comment'] ?? null;

        $this->model->update($id, ['status_management' => $status]);

        $this->history->insert([
            'id_pengajuan' => $id,
            'id_user'      => session()->get('id_user') ?? null,
            'role_user'    => 'Management',
            'action'       => $status,
            'comment'      => $comment,
        ]);

        // Kalau reject dan HR sebelumnya approve → butuh review ulang
        if ($status === 'Rejected' && $pengajuan['status_hr'] === 'Approved') {
            $this->model->update($id, ['needs_hr_check' => 1]);
        }

        return $this->respond([
            'message' => 'Review Management berhasil',
            'data'    => $this->model->find($id)
        ]);
    }

    // =====================
    // REVIEW REKRUTMEN
    // =====================
    public function rekrutmenReview($id = null)
    {
        $data = $this->input();
        $pengajuan = $this->model->find($id);
        if (!$pengajuan) return $this->failNotFound('Pengajuan tidak ditemukan');

        $status = $data['status_rekrutmen'] ?? null;
        $this->model->update($id, ['status_rekrutmen' => $status]);

        if ($status === 'Selesai' &&
            $pengajuan['status_hr'] === 'Approved' &&
            $pengajuan['status_management'] === 'Approved') {

            $this->history->insert([
                'id_pengajuan' => $id,
                'id_user'      => session()->get('id_user') ?? null,
                'role_user'    => 'Rekrutmen',
                'action'       => 'Finish',
                'comment'      => $pengajuan['comment'] ?? null,
            ]);
            $this->model->update($id, ['archived' => 1]);
        }

        return $this->respond([
            'message' => 'Review Rekrutmen berhasil',
            'data'    => $this->model->find($id)
        ]);
    }

    // =====================
    // ENDPOINT MANUAL MOVE TO HISTORY
    // =====================
    public function toHistory($id)
    {
        $pengajuan = $this->model->find($id);
        if (!$pengajuan) return $this->failNotFound('Pengajuan tidak ditemukan');

        $this->history->insert([
            'id_pengajuan' => $id,
            'id_user'      => session()->get('id_user') ?? null,
            'role_user'    => 'System',
            'action'       => 'Move',
            'comment'      => $pengajuan['comment'] ?? null,
        ]);

        $this->model->update($id, ['archived' => 1, 'needs_hr_check' => 0]);

        return $this->respond(['message' => 'Pengajuan dipindahkan ke history']);
    }
}
