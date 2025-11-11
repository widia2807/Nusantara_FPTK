<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PengajuanModel;
use App\Models\HistoryModel;
use App\Models\RangeGajiModel;
use App\Models\DivisiModel;
use App\Models\PosisiModel;
use App\Models\CabangModel;


class Pengajuan extends ResourceController
{
    protected $modelName = PengajuanModel::class;
    protected $format    = 'json';

    protected $history;
    protected $rangeGaji;

    public function __construct()
    {
        $this->history   = new HistoryModel();
        $this->rangeGaji = new RangeGajiModel();
    }

    // =====================
    // GET /api/pengajuan
    // =====================
    public function index()
    {
        try {
            $perPage = (int) ($this->request->getGet('perPage') ?? 20);
            $data    = $this->model->getWithRelations($perPage);

            return $this->respond([
                'data'  => $data,
                'pager' => [
                    'total'       => $this->model->pager->getTotal(),
                    'perPage'     => $perPage,
                    'currentPage' => $this->model->pager->getCurrentPage(),
                    'pageCount'   => $this->model->pager->getPageCount(),
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->respond([
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ], 500);
        }
    }

    // =====================
    // HELPER INPUT
    // =====================
    private function input(): array
    {
        $data = $this->request->getJSON(true);
        if (is_array($data) && !empty($data)) return $data;

        $data = $this->request->getRawInput();
        if (is_array($data) && !empty($data)) return $data;

        $data = $this->request->getPost();
        return is_array($data) ? $data : [];
    }

    // helper untuk tarik & validasi FK
private function sanitizeAndValidateFK(array $in): array
{
    $id_divisi = isset($in['id_divisi']) ? (int)$in['id_divisi'] : 0;
    $id_posisi = isset($in['id_posisi']) ? (int)$in['id_posisi'] : 0;
    $id_cabang = isset($in['id_cabang']) ? (int)$in['id_cabang'] : 0;

    // wajib diisi sesuai skenario kamu; sesuaikan jika opsional
    if ($id_divisi <= 0) return ['error' => 'id_divisi wajib & valid'];
    if ($id_posisi <= 0) return ['error' => 'id_posisi wajib & valid'];
    if ($id_cabang <= 0) return ['error' => 'id_cabang wajib & valid'];

    $divisiOK = model(DivisiModel::class)->find($id_divisi);
    if (!$divisiOK) return ['error' => 'Divisi tidak ditemukan'];

    $posisiOK = model(PosisiModel::class)->find($id_posisi);
    if (!$posisiOK) return ['error' => 'Posisi tidak ditemukan'];

    $cabangOK = model(CabangModel::class)->find($id_cabang);
    if (!$cabangOK) return ['error' => 'Cabang tidak ditemukan'];

    // kembalikan nilai final yang sudah dibersihkan
    $in['id_divisi'] = $id_divisi;
    $in['id_posisi'] = $id_posisi;
    $in['id_cabang'] = $id_cabang;
    return $in;
}

    // =====================
    // HELPER ACTOR & HISTORY
    // =====================
    private function actor(): array
    {
        return [
            'id'   => session()->get('id_user') ?? null,
            'role' => session()->get('role')    ?? 'System',
        ];
    }

    private function logHistoryOnce(int $idPengajuan, string $role, ?int $idUser, string $action, ?string $comment = null): void
    {
        $exists = $this->history
            ->where('id_pengajuan', $idPengajuan)
            ->where('role_user', $role)
            ->where('action', $action)
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-1 minute')))
            ->first();

        if ($exists) return;

        $this->history->insert([
            'id_pengajuan' => $idPengajuan,
            'id_user'      => $idUser,
            'role_user'    => $role,
            'action'       => $action, // gunakan label final yang konsisten
            'comment'      => $comment ?: null,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);
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
$data = $this->sanitizeAndValidateFK($data);
if (isset($data['error'])) return $this->failValidationErrors($data['error']);

        if (!$this->model->insert($data, true)) {
            return $this->failValidationErrors($this->model->errors());
        }

        $id = $this->model->getInsertID();

        $actor = $this->actor();
        $this->logHistoryOnce(
            $id,
            'Divisi',
            $actor['id'],
            'Create',
            $data['kualifikasi'] ?? null
        );

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
$data = $this->sanitizeAndValidateFK($data);
if (isset($data['error'])) return $this->failValidationErrors($data['error']);

if (!$this->model->update($id, $data)) {
    return $this->failValidationErrors($this->model->errors());
}

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

        $statusIn = isset($data['status_hr']) ? trim($data['status_hr']) : null; // 'Approved' | 'Rejected'
        $actionIn = isset($data['action']) ? strtolower(trim($data['action'])) : ''; // 'accept' | 'send' | 'reject'
        $minGaji  = $data['min_gaji'] ?? null;
        $maxGaji  = $data['max_gaji'] ?? null;
        $comment  = $data['comment']  ?? '-';

        $actor = $this->actor();

        if (!$statusIn) {
            return $this->failValidationErrors("Status HR wajib diisi");
        }

        // REJECT
        if ($actionIn === 'reject') {
            if (trim($comment) === '') {
                return $this->failValidationErrors("Comment wajib diisi jika Reject");
            }

            $this->model->update($id, [
                'status_hr' => 'Rejected',
                'archived'  => 1,
            ]);

            $this->logHistoryOnce($id, 'HR', $actor['id'], 'Rejected', $comment);
            return $this->respond(['message' => 'Pengajuan ditolak oleh HR.']);
        }

        // ACCEPT (belum kirim)
        if ($actionIn === 'accept') {
            $this->model->update($id, ['status_hr' => 'Approved']);
            $this->logHistoryOnce($id, 'HR', $actor['id'], 'Approved', $comment);
            return $this->respond(['message' => 'HR menyetujui pengajuan, menunggu pengisian range gaji.']);
        }

        // SEND (isi gaji + kirim ke management)
        if ($actionIn === 'send') {
            if (!$minGaji || !$maxGaji) {
                return $this->failValidationErrors("Range gaji wajib diisi sebelum dikirim.");
            }

            // Update status pengajuan
            $this->model->update($id, [
                'status_hr'         => 'Approved',
                'status_management' => 'Pending',
            ]);

            // Upsert range gaji
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
                    'created_by'   => $actor['id'],
                    'created_at'   => date('Y-m-d H:i:s'),
                ]);
            }

            $this->logHistoryOnce($id, 'HR', $actor['id'], 'Sent to Management', $comment ?: 'Dikirim ke Management');
            return $this->respond(['message' => 'Pengajuan dikirim ke Management.']);
        }

        return $this->failValidationErrors("Aksi HR tidak valid. Gunakan accept / send / reject.");
    }

    // =====================
    // REVIEW MANAGEMENT
    // =====================
    public function managementReview($id = null)
    {
        $data = $this->input();
        $pengajuan = $this->model->find($id);
        if (!$pengajuan) return $this->failNotFound('Pengajuan tidak ditemukan');

        $status  = $data['status_management'] ?? null;   // 'Approved' | 'Rejected' | 'Pending'
        $comment = $data['comment']            ?? '';    // wajib kalau Rejected
        $actor   = $this->actor();

        if (!$status) {
            return $this->failValidationErrors('status_management wajib.');
        }
        if (strcasecmp($status, 'Rejected') === 0 && trim($comment) === '') {
            return $this->failValidationErrors('Comment wajib diisi jika Reject.');
        }

        $ok = $this->model->update($id, [
            'status_management'  => $status,
            'comment_management' => $comment,
        ]);
        if (!$ok) {
            return $this->failValidationErrors($this->model->errors() ?: 'Gagal update.');
        }

        $this->logHistoryOnce($id, 'Management', $actor['id'], $status, $comment ?: null);

        // Auto-archive opsional
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

        if (
            strcasecmp((string)$status, 'Selesai') === 0 &&
            ($pengajuan['status_hr'] ?? null) === 'Approved' &&
            ($pengajuan['status_management'] ?? null) === 'Approved'
        ) {
            $actor = $this->actor();
            $this->logHistoryOnce($id, 'Rekrutmen', $actor['id'], 'Rekrutmen Selesai', $pengajuan['comment'] ?? null);
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

        $actor = $this->actor();
        $this->logHistoryOnce($id, 'System', $actor['id'], 'Move', $pengajuan['comment'] ?? null);

        $this->model->update($id, ['archived' => 1, 'needs_hr_check' => 0]);

        return $this->respond(['message' => 'Pengajuan dipindahkan ke history']);
    }
}
