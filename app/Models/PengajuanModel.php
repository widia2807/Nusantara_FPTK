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
        'archived','needs_hr_check','created_at'
    ];

    // ✅ Ambil semua pengajuan dengan relasi + pagination
    public function getWithRelations($perPage = 20)
    {
        return $this->select('
                pengajuan.*,
                divisi.nama_divisi,
                posisi.nama_posisi,
                cabang.nama_cabang,
                rangegaji.min_gaji AS min_gaji,
                rangegaji.max_gaji AS max_gaji
            ')
            ->join('divisi', 'divisi.id_divisi = pengajuan.id_divisi', 'left')
            ->join('posisi', 'posisi.id_posisi = pengajuan.id_posisi', 'left')
            ->join('cabang', 'cabang.id_cabang = pengajuan.id_cabang', 'left')
            ->join('rangegaji', 'rangegaji.id_pengajuan = pengajuan.id_pengajuan', 'left')
            ->paginate($perPage);
    }

    // ✅ Cari 1 pengajuan by ID dengan relasi lengkap
    public function findWithRelations($id)
    {
        return $this->select('
                pengajuan.*,
                divisi.nama_divisi,
                posisi.nama_posisi,
                cabang.nama_cabang,
                rangegaji.min_gaji AS min_gaji,
                rangegaji.max_gaji AS max_gaji
            ')
            ->join('divisi', 'divisi.id_divisi = pengajuan.id_divisi', 'left')
            ->join('posisi', 'posisi.id_posisi = pengajuan.id_posisi', 'left')
            ->join('cabang', 'cabang.id_cabang = pengajuan.id_cabang', 'left')
            ->join('rangegaji', 'rangegaji.id_pengajuan = pengajuan.id_pengajuan', 'left')
            ->where('pengajuan.id_pengajuan', $id)
            ->first();
    }

    // ✅ Ambil semua tanpa paginate
    public function getAllWithRelations()
    {
        return $this->select('
                pengajuan.*,
                divisi.nama_divisi,
                posisi.nama_posisi,
                cabang.nama_cabang,
                rangegaji.min_gaji AS min_gaji,
                rangegaji.max_gaji AS max_gaji
            ')
            ->join('divisi', 'divisi.id_divisi = pengajuan.id_divisi', 'left')
            ->join('posisi', 'posisi.id_posisi = pengajuan.id_posisi', 'left')
            ->join('cabang', 'cabang.id_cabang = pengajuan.id_cabang', 'left')
            ->join('rangegaji', 'rangegaji.id_pengajuan = pengajuan.id_pengajuan', 'left')
            ->findAll();
    }
}
