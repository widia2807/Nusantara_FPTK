<?php
namespace App\Models;
use CodeIgniter\Model;

class HistoryModel extends Model
{
    protected $table      = 'history';
    protected $primaryKey = 'id_history';
    protected $allowedFields = [
        'id_pengajuan','id_user','role_user','action','comment','created_at'
    ];

   public function getWithRelations()
{
    return $this->db->table($this->table . ' h')
        ->select('h.*,
                  p.jumlah_karyawan, p.job_post_number, p.tipe_pekerjaan,
                  p.status_hr, p.status_management, p.status_rekrutmen,
                  d.nama_divisi,
                  ps.nama_posisi,
                  c.nama_cabang,
                  u.full_name')
        ->join('pengajuan p', 'p.id_pengajuan = h.id_pengajuan', 'left')
        ->join('divisi d', 'd.id_divisi = p.id_divisi', 'left')
        ->join('posisi ps', 'ps.id_posisi = p.id_posisi', 'left')
        ->join('cabang c', 'c.id_cabang = p.id_cabang', 'left')
        ->join('user u', 'u.id_user = h.id_user', 'left')
        ->orderBy('h.created_at', 'DESC')
        ->get()
        ->getResultArray();
}


}
