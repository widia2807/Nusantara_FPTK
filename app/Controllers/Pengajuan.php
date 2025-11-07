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
    try {
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
    } catch (\Throwable $e) {
        // ✅ tangkap pesan error biar kelihatan
        return $this->respond([
            'error' => $e->getMessage(),
            'file'  => $e->getFile(),
            'line'  => $e->getLine(),
        ], 500);
    }
}
    // Helper ambil input (JSON, raw, atau form)
// =====================
    // HELPER AMBIL INPUT
    // =====================
    private function input(): array
    {
        $data = $this->request->getJSON(true);
        if (is_array($data) && !empty($data)) {
            return $data;
        }

        $data = $this->request->getRawInput();
        if (is_array($data) && !empty($data)) {
            return $data;
        }

        $data = $this->request->getPost();
        return is_array($data) ? $data : [];
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


        // Catat ke history (aksi Create)
        $this->history->insert([
            'id_pengajuan' => $id,
            'id_user'      => session()->get('id_user') ?? null,
            'role_user'    => 'Divisi',
            'action'       => 'Create',
            'comment'      => $data['kualifikasi'] ?? null,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

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
    $comment = $data['comment'] ?? '-';
    $action  = strtolower($data['action'] ?? ''); // "accept" | "send" | "reject"
    $idUser  = session()->get('id_user') ?? null;

    // ✅ Validasi dasar
    if (!$status) {
        return $this->failValidationErrors("Status HR wajib diisi");
    }

    if ($status === 'rejected' && !$comment) {
        return $this->failValidationErrors("Comment wajib diisi jika Reject");
    }

    // ✅ Cegah duplikat history dalam 1 menit terakhir
    $exists = $this->history
        ->where('id_pengajuan', $id)
        ->where('role_user', 'HR')
        ->where('action', ucfirst($action))
        ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-1 minute')))
        ->first();

    if ($exists) {
        return $this->respond([
            'message' => 'Aksi sudah tercatat, abaikan duplikat.'
        ]);
    }

    // =====================
    // 🟥 1. HR REJECT
    // =====================
    if ($action === 'reject') {
        $this->model->update($id, [
            'status_hr' => 'Rejected',
            'archived'  => 1,
        ]);

        $this->history->insert([
            'id_pengajuan' => $id,
            'id_user'      => $idUser,
            'role_user'    => 'HR',
            'action'       => 'Rejected',
            'comment'      => $comment,
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        return $this->respond(['message' => 'Pengajuan ditolak oleh HR.']);
    }

    // =====================
    // 🟩 2. HR ACCEPT (belum isi gaji)
    // =====================
    if ($action === 'accept') {
        $this->model->update($id, ['status_hr' => 'Approved']);

        $this->history->insert([
            'id_pengajuan' => $id,
            'id_user'      => $idUser,
            'role_user'    => 'HR',
            'action'       => 'Accept',
            'comment'      => $comment,
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        return $this->respond(['message' => 'HR menyetujui pengajuan, menunggu pengisian range gaji.']);
    }

    // =====================
    // 🟦 3. HR SEND (isi gaji + kirim ke management)
    // =====================
    if ($action === 'send') {
        if (!$minGaji || !$maxGaji) {
            return $this->failValidationErrors("Range gaji wajib diisi sebelum dikirim.");
        }

        // Update status pengajuan
        $this->model->update($id, [
            'status_hr'         => 'Approved',
            'status_management' => 'Pending'
        ]);

        // Simpan range gaji (update kalau sudah ada)
        $existingGaji = $this->rangeGaji->where('id_pengajuan', $id)->first();
        if ($existingGaji) {
            $this->rangeGaji->update($existingGaji['id_range'] ?? $existingGaji['id'], [
                'min_gaji'   => $minGaji,
                'max_gaji'   => $maxGaji,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $this->rangeGaji->insert([
                'id_pengajuan' => $id,
                'min_gaji'     => $minGaji,
                'max_gaji'     => $maxGaji,
                'created_by'   => $idUser,
                'created_at'   => date('Y-m-d H:i:s'),
            ]);
        }

        // Catat history hanya sekali
        $this->history->insert([
            'id_pengajuan' => $id,
            'id_user'      => $idUser,
            'role_user'    => 'HR',
            'action'       => 'Send',
            'comment'      => 'Dikirim ke Management',
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        return $this->respond(['message' => 'Pengajuan dikirim ke Management.']);
    }

    // =====================
    // 🔶 Default (jika action tidak dikenali)
    // =====================
    return $this->failValidationErrors("Aksi HR tidak valid. Gunakan accept / send / reject.");
}

public function managementReview($id = null)
{
    $data = $this->input();
    $pengajuan = $this->model->find($id);
    if (!$pengajuan) return $this->failNotFound('Pengajuan tidak ditemukan');

    // Ambil input
    $status  = $data['status_management'] ?? null;   // 'Approved' | 'Rejected' | 'Pending'
    $comment = $data['comment']            ?? '';    // wajib kalau Rejected
    $idUser  = session()->get('id_user')    ?? null; // opsional, untuk history

    if (!$status) {
        return $this->failValidationErrors('status_management wajib.');
    }
    if (strcasecmp($status, 'Rejected') === 0 && trim($comment) === '') {
        return $this->failValidationErrors('Comment wajib diisi jika Reject.');
    }

    // Update status management (+ simpan comment kalau ada kolomnya)
    $ok = $this->model->update($id, [
        'status_management'  => $status,
        'comment_management' => $comment,
    ]);
    if (!$ok) {
        return $this->failValidationErrors($this->model->errors() ?: 'Gagal update.');
    }

    // Tulis history
    $this->history->insert([
        'id_pengajuan' => $id,
        'id_user'      => $idUser,
        'role_user'    => 'Management',
        'action'       => $status,               // 'Approved' atau 'Rejected'
        'comment'      => $comment ?: null,
        'created_at'   => date('Y-m-d H:i:s'),
    ]);

    // (Opsional) auto-archive jika semua sudah Approved
    $updated = $this->model->find($id);
    if (
        ($updated['status_hr'] ?? null)         === 'Approved' &&
        ($updated['status_management'] ?? null) === 'Approved' &&
        ($updated['status_rekrutmen'] ?? null)  === 'Selesai'
    ) {
        $this->model->update($id, ['archived' => 1]);
    }

    return $this->respondUpdated([
        'ok'   => true,
        'data' => $this->model->find($id),
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
                'created_at'   => date('Y-m-d H:i:s'),
            ]);
            $this->model->update($id, ['archived' => 1]);
        }

        return $this->respond([
            'message' => 'Review Rekrutmen berhasil',
            'data'    => $this->model->find($id),
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
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        $this->model->update($id, ['archived' => 1, 'needs_hr_check' => 0]);

        return $this->respond(['message' => 'Pengajuan dipindahkan ke history']);
    }
}
