<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoryModel extends Model
{
    protected $table            = 'history';
    protected $primaryKey       = 'id_history';
    protected $allowedFields    = [
        'id_pengajuan',
        'id_user',
        'role_user',
        'action',
        'comment',
        'created_at'
    ];

    // Optional: otomatis timestamp
    protected $useTimestamps = false; 
    // kalau mau created_at auto isi, bisa pakai event di Controller waktu insert
}
