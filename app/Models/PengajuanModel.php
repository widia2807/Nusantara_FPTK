<?php
namespace App\Models;

use CodeIgniter\Model;

class PengajuanModel extends Model
{
    protected $table = 'pengajuan';
    protected $primaryKey = 'id_pengajuan';
    protected $allowedFields = [
    'id_user_divisi','id_divisi','id_posisi','id_cabang','jumlah_karyawan',
    'job_post_number','tipe_pekerjaan','range_umur','tempat_kerja','kualifikasi',
    'status_hr','status_management','status_rekrutmen','comment',
    'min_gaji','max_gaji','archived','needs_hr_check','created_at'
];


    // ✅ Fungsi custom untuk join tabel terkait
    public function getWithRelations($perPage = 20)
    {
        return $this->select('
                pengajuan.*,
                divisi.nama_divisi,
                posisi.nama_posisi,
                cabang.nama_cabang
            ')
            ->join('divisi', 'divisi.id_divisi = pengajuan.id_divisi', 'left')
            ->join('posisi', 'posisi.id_posisi = pengajuan.id_posisi', 'left')
            ->join('cabang', 'cabang.id_cabang = pengajuan.id_cabang', 'left')
            ->paginate($perPage);
    }

    // ✅ Kalau mau untuk find by ID juga
    public function findWithRelations($id)
    {
        return $this->select('
                pengajuan.*,
                divisi.nama_divisi,
                posisi.nama_posisi,
                cabang.nama_cabang
            ')
            ->join('divisi', 'divisi.id_divisi = pengajuan.id_divisi', 'left')
            ->join('posisi', 'posisi.id_posisi = pengajuan.id_posisi', 'left')
            ->join('cabang', 'cabang.id_cabang = pengajuan.id_cabang', 'left')
            ->where('pengajuan.id_pengajuan', $id)
            ->first();
    }
}
