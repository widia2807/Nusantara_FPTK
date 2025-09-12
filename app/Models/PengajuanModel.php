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
        'status_hr','status_management','status_rekrutmen','created_at'
    ];
}
