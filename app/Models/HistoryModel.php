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
                      p.nama_divisi, p.nama_posisi, p.nama_cabang, 
                      p.jumlah_karyawan, p.job_post_number, p.tipe_pekerjaan,
                      u.full_name')
            ->join('pengajuan p', 'p.id_pengajuan = h.id_pengajuan')
            ->join('users u', 'u.id_user = h.id_user', 'left')
            ->orderBy('h.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
