<?php
namespace App\Models;

use CodeIgniter\Model;

class DivisiModel extends Model
{
    protected $table         = 'Divisi';
    protected $primaryKey    = 'id_divisi';
    protected $allowedFields = ['nama_divisi'];
   
}
