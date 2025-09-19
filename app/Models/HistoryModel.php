<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoryModel extends Model {
    protected $table = 'history';
    protected $primaryKey = 'id_history';
    protected $allowedFields = [
        'id_pengajuan','id_user','role_user','action','comment','created_at'
    ];

    public function getWithRelations() {
        return $this->select('history.*, d.nama_divisi, p.nama_posisi, c.nama_cabang, pg.jumlah_karyawan, pg.job_post_number, pg.tipe_pekerjaan, pg.created_at as tgl_pengajuan, u.full_name')
            ->join('pengajuan pg', 'pg.id_pengajuan = history.id_pengajuan')
            ->join('divisi d', 'pg.id_divisi = d.id_divisi', 'left')
            ->join('posisi p', 'pg.id_posisi = p.id_posisi', 'left')
            ->join('cabang c', 'pg.id_cabang = c.id_cabang', 'left')
            ->join('user u', 'u.id_user = history.id_user', 'left')
            ->orderBy('history.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
