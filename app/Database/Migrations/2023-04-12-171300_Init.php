<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Init extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'request_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'absent_type' => [
                'type' => 'INT',
                'null' => false,
            ],
            'approve_status' => [
                'type' => 'INT',
                'null' => false,
                'default'    => '0',
            ],
            'leave_count_type' => [
                'type' => 'INT',
                'null' => false,
                'default'    => '0',
            ],
            'request_group' => [
                'type' => 'BIGINT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey(['id'], true);    
        $this->forge->addKey(['user_id', 'deleted_at'], false, false, 'search_user');
        $this->forge->addKey(['request_date', 'deleted_at'], false, false, 'search_date');
        $this->forge->addUniqueKey(['user_id', 'request_date', 'absent_type'], 'unique');
        $this->forge->createTable('users');
        
        
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'           => 'VARCHAR',
                'constraint'     => 45,
            ],
            'fullname' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
            ],
            'team' => [
                'type'           => 'VARCHAR',
                'constraint'     => 45,
            ],
            'is_admin' => [
                'type' => 'INT',
                'null' => false,
            ],
            'password' => [
                'type'           => 'VARCHAR',
                'constraint'     => 60,
            ],
            'paid_leave_per_year' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'paid_leave_left_this_year' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'paid_leave_left_last_year' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'remember_key' => [
                'type'           => 'VARCHAR',
                'constraint'     => 45,
            ],
            'email' => [
                'type'           => 'VARCHAR',
                'constraint'     => 60,
            ],            
            'is_team_lead' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],            
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey(['id'], true);    
        $this->forge->addUniqueKey(['username'], 'username');
        $this->forge->createTable('absent_requests');
        
        
        $this->forge->addField([
            'date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'time' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => false,
            ],
        ]);
        $this->forge->addKey(['date', 'time', 'user_id'], true);        
        $this->forge->createTable('checkin');
        
        $this->forge->addField([
            'month' => [
                'type'           => 'TIME',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'day' => [
                'type'           => 'TIME',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
        ]);
        $this->forge->addKey(['month', 'day'], true);
        $this->forge->createTable('holidays');
        
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'ip' => [
                'type'           => 'VARCHAR',
                'constraint'     => 45,
            ],
            'step' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'valid_until' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
        ]);
        $this->forge->addKey(['id'], true);
        $this->forge->createTable('iplogs');
        
        $this->forge->addField([
            'key' => [
                'type'           => 'VARCHAR',
                'constraint'     => 40,
            ],
            'value' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
        ]);
        $this->forge->addKey(['key'], true);
        $this->forge->createTable('settings');
    }

    public function down()
    {
//        $this->forge->dropTable('users');
//        $this->forge->dropTable('settings');
//        $this->forge->dropTable('holidays');
//        $this->forge->dropTable('checkin');
//        $this->forge->dropTable('absent_requests');
    }
}
