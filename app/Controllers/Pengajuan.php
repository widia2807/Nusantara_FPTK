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
        'name' => session()->get('full_name') ?? null, // kalau ada
    ];
}

/**
 * Simpan 1 baris history untuk actor yang login.
 * Cegah duplikat (aksi sama oleh actor yang sama dalam 1 menit).
 */
private function logHistoryOnce(
    int $idPengajuan,
    ?string $role,
    ?int $idUser,
    string $action,
    ?string $comment = null,
    ?string $reviewerName = null // opsional
): bool {
    // Wajib ada actor yang valid
    $role   = $role   ?: (session()->get('role') ?? null);
    $idUser = $idUser ?: (session()->get('id_user') ?? null);
    if (!$role || !$idUser) {
        log_message('error', 'logHistoryOnce: actor kosong (id_user/role).');
        return false;
    }

    $action = strtolower(trim($action));

    // Cegah duplikat
    $qb = $this->history
        ->where('id_pengajuan', $idPengajuan)
        ->where('action', $action)
        ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-1 minute')))
        ->where('id_user', $idUser);

    if ($qb->first()) return false;

    $this->history->insert([
        'id_pengajuan'  => $idPengajuan,
        'id_user'       => $idUser,
        'role_user'     => $role,
        'action'        => $action,          // 'hr_send', 'management_approve', 'rekrutmen_done', dll.
        'comment'       => $comment,
        'reviewer_name' => $reviewerName,    // jika kolomnya kamu tambahkan (lihat #3)
        'created_at'    => date('Y-m-d H:i:s'),
    ]);

    return true;
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
    // REVIEW MANAGEMENT
    // =====================
   public function managementReview($id = null)
{
    $data = $this->input();
    $pengajuan = $this->model->find($id);
    if (!$pengajuan) return $this->failNotFound('Pengajuan tidak ditemukan');

    $status  = $data['status_management'] ?? null;   // 'Approved' | 'Rejected' | 'Pending'
    $comment = $data['comment']            ?? '';
    $actor   = $this->actor();

    if (!$status) return $this->failValidationErrors('status_management wajib.');
    if (strcasecmp($status, 'Rejected') === 0 && trim($comment) === '')
        return $this->failValidationErrors('Comment wajib diisi jika Reject.');

    // guard: hanya kalau berubah
    $prev = $pengajuan['status_management'] ?? null;
    if ($prev !== $status) {
        $ok = $this->model->update($id, [
            'status_management'  => $status,
            'comment_management' => $comment,
        ]);
        if (!$ok) return $this->failValidationErrors($this->model->errors() ?: 'Gagal update.');

        $action = (strcasecmp($status,'Approved')===0) ? 'management_approve'
                 : ((strcasecmp($status,'Rejected')===0) ? 'management_reject' : 'management_pending');

        $this->logHistoryOnce($id, 'Management', $actor['id'], $action, $comment ?: null);
    }

    // Auto-archive opsional (tetap)
    $updated = $this->model->find($id);
    if (($updated['status_hr'] ?? null) === 'Approved'
        && ($updated['status_management'] ?? null) === 'Approved'
        && ($updated['status_rekrutmen'] ?? null)  === 'Selesai') {
        $this->model->update($id, ['archived' => 1]);
    }

    return $this->respondUpdated(['ok' => true, 'data' => $this->model->find($id)]);
}

// =====================
// REVIEW HR
// =====================
public function hrReview($id = null)
{
    // Ambil input (JSON / raw / POST)
    $data = $this->input();

    // Cari pengajuan
    $pengajuan = $this->model->find($id);
    if (!$pengajuan) {
        return $this->failNotFound('Pengajuan tidak ditemukan');
    }

    // Ambil nilai dari body
    $statusIn = isset($data['status_hr']) ? trim($data['status_hr']) : null;    // 'Approved' | 'Rejected'
    $actionIn = isset($data['action'])    ? strtolower(trim($data['action'])) : '';  // 'accept' | 'send' | 'reject'
    $minGaji  = $data['min_gaji'] ?? null;
    $maxGaji  = $data['max_gaji'] ?? null;
    $comment  = $data['comment']  ?? '-';

    $actor = $this->actor();

    if (!$statusIn) {
        return $this->failValidationErrors("Status HR wajib diisi");
    }

    // =====================
    // REJECT
    // =====================
    if ($actionIn === 'reject') {
        if (trim($comment) === '') {
            return $this->failValidationErrors("Comment wajib diisi jika Reject");
        }

        // hanya update kalau sebelumnya belum Rejected
        if (($pengajuan['status_hr'] ?? null) !== 'Rejected') {
            $this->model->update($id, [
                'status_hr' => 'Rejected',
                'archived'  => 1,
            ]);

            $this->logHistoryOnce($id, 'HR', $actor['id'], 'hr_reject', $comment);
        }

        return $this->respond(['message' => 'Pengajuan ditolak oleh HR.']);
    }

    // =====================
    // ACCEPT (belum kirim ke management)
    // =====================
    if ($actionIn === 'accept') {

        if (($pengajuan['status_hr'] ?? null) !== 'Approved') {
            $this->model->update($id, [
                'status_hr' => 'Approved',
            ]);

            $this->logHistoryOnce($id, 'HR', $actor['id'], 'hr_accept', $comment);
        }

        return $this->respond([
            'message' => 'HR menyetujui pengajuan, menunggu pengisian range gaji.'
        ]);
    }

    // =====================
    // SEND (isi gaji + kirim ke management)
    // =====================
    if ($actionIn === 'send') {

        // wajib isi gaji
        if (!$minGaji || !$maxGaji) {
            return $this->failValidationErrors("Range gaji wajib diisi sebelum dikirim.");
        }

        // 1. SELALU upsert range gaji di tabel rangegaji
        $existingGaji = $this->rangeGaji->where('id_pengajuan', $id)->first();

        if ($existingGaji) {
            // pakai primary key yang benar: id_gaji
            $this->rangeGaji->update($existingGaji['id_gaji'], [
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

        // 2. Status hanya di-set kalau belum pernah dikirim ke management
        $prevMng = $pengajuan['status_management'] ?? null;
        if ($prevMng !== 'Pending' && $prevMng !== 'Approved' && $prevMng !== 'Rejected') {
            $this->model->update($id, [
                'status_hr'         => 'Approved',
                'status_management' => 'Pending',
                // kalau di tabel pengajuan ada kolom min_gaji & max_gaji dan mau ikut disimpan, boleh aktifkan:
                // 'min_gaji'          => $minGaji,
                // 'max_gaji'          => $maxGaji,
            ]);
        }

        $this->logHistoryOnce($id, 'HR', $actor['id'], 'hr_send', $comment ?: 'Dikirim ke Management');

        return $this->respond(['message' => 'Pengajuan dikirim ke Management.']);
    }

    // =====================
    // ACTION tidak dikenal
    // =====================
    return $this->failValidationErrors("Aksi HR tidak valid. Gunakan accept / send / reject.");
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
    if (!$status) return $this->failValidationErrors('status_rekrutmen wajib.');

    // guard: hanya jika berubah
    if (($pengajuan['status_rekrutmen'] ?? null) !== $status) {
        $this->model->update($id, ['status_rekrutmen' => $status]);

        if (strcasecmp((string)$status, 'Selesai') === 0
            && ($pengajuan['status_hr'] ?? null) === 'Approved'
            && ($pengajuan['status_management'] ?? null) === 'Approved') {
            $actor = $this->actor();
            $this->logHistoryOnce($id, 'Rekrutmen', $actor['id'], 'rekrutmen_done', $pengajuan['comment'] ?? null);
            $this->model->update($id, ['archived' => 1]);
        }
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
