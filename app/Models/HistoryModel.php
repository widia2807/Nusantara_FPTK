<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoryModel extends Model
{
    protected $table = 'history';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pengajuan_id', 'aksi', 'komentar', 'created_at'];
}
