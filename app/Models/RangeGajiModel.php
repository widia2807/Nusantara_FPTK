<?php

namespace App\Models;

use CodeIgniter\Model;

class RangeGajiModel extends Model
{
    protected $table         = 'rangegaji';
    protected $primaryKey    = 'id_gaji';
    protected $allowedFields = [
        'id_pengajuan',
        'min_gaji',
        'max_gaji',
        'created_by',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;
}
