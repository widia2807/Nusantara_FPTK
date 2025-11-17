<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'user'; // atau 'users' kalau tabelmu beda
    protected $primaryKey    = 'id_user';

    protected $allowedFields = [
        'username', 'full_name', 'password', 'role', 'profile_photo', 'is_active', 'created_at'
    ];

    protected $returnType    = 'array';
}
