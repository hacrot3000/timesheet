<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIsIt extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('is_it', 'users'))
        {
            return;
        }

        $this->forge->addColumn('users', [
            'is_it' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
                'after'      => 'is_admin',
            ],
        ]);
    }

    public function down()
    {
        if ($this->db->fieldExists('is_it', 'users'))
        {
            $this->forge->dropColumn('users', 'is_it');
        }
    }
}
