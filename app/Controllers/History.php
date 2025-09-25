<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoryModel extends Model
{
    protected $table      = 'history';
    protected $primaryKey = 'id_history';

    protected $allowedFields = [
        'id_pengajuan',
        'nama_divisi',
        'nama_posisi',
        'nama_cabang',
        'jumlah_karyawan',
        'job_post_number',
        'tipe_pekerjaan',
        'created_at',
        'role_user',
        'action',
        'comment'
    ];

    public function getWithRelations()
    {
        return $this->db->table($this->table)
            ->select('history.*, pengajuan.nama_divisi, pengajuan.nama_posisi, pengajuan.nama_cabang')
            ->join('pengajuan', 'pengajuan.id_pengajuan = history.id_pengajuan', 'left')
            ->get()
            ->getResultArray();
    }
}

