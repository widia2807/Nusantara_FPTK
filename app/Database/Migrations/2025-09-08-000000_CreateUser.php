<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUser extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_user'   => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'username'  => ['type'=>'VARCHAR','constraint'=>100,'null'=>false,'unique'=>true],
            'full_name' => ['type'=>'VARCHAR','constraint'=>150,'null'=>false],
            'password'  => ['type'=>'VARCHAR','constraint'=>255,'null'=>false],
            'role'      => ['type'=>'ENUM','constraint'=>['HR','Management','Rekrutmen','Divisi'],'null'=>false],
            'is_active' => ['type'=>'TINYINT','constraint'=>1,'default'=>1],
            'created_at'=> ['type'=>'DATETIME','null'=>true,'default'=>null],
            'updated_at'=> ['type'=>'DATETIME','null'=>true,'default'=>null],
        ]);
        $this->forge->addKey('id_user', true);
        $this->forge->createTable('user', true);
    }

    public function down()
    {
        $this->forge->dropTable('user', true);
    }
}
