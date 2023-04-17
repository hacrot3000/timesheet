<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Init extends Migration
{

    public function up()
    {
        $fields = [
            'desc'      => [
                'type'       => 'VARCHAR',
                'constraint' => 55,
            ],
            'descext'      => [
                'type'       => 'TEXT',
            ],
            'canchange' => [
                'type'    => 'INT',
                'default' => 1,
            ],
        ];
        $this->forge->addColumn('settings', $fields);
    }

    public function down()
    {

    }
}
