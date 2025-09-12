<?php
namespace App\Models;
use CodeIgniter\Model;

class PosisiModel extends Model
{
    protected $table = 'posisi';
    protected $primaryKey = 'id_posisi';
    protected $allowedFields = ['id_divisi', 'nama_posisi'];
}
