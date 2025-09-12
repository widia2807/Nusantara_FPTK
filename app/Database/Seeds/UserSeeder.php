<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('user')->insert([
            'username'  => 'wid',
            'full_name' => 'Widia Simanjuntak',
            'password'  => password_hash('123456', PASSWORD_DEFAULT),
            'role'      => 'HR',
            'is_active' => 1,
            'created_at'=> date('Y-m-d H:i:s'),
        ]);
    }
}
