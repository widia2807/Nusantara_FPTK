<?php
namespace App\Models;

use CodeIgniter\Model;

class CabangModel extends Model
{
    protected $table            = 'cabang';        // nama tabel
    protected $primaryKey       = 'id_cabang';     // primary key
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_cabang', 'lokasi']; // kolom yang bisa diisi

    // optional: kalau mau pakai created_at / updated_at
    protected $useTimestamps = false;
}
