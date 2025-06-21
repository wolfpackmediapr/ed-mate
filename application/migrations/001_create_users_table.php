<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_users_table extends Migration {
    public function up() {
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => TRUE
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE
            ],
            'profile_picture' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'role' => [
                'type' => 'ENUM("admin", "instructor", "student")',
                'default' => 'student'
            ],
            'status' => [
                'type' => 'ENUM("active", "inactive", "suspended")',
                'default' => 'active'
            ],
            'last_login' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],
            'created_at' => [
                'type' => 'DATETIME'
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ];

        $this->create_table('users', $fields);
    }

    public function down() {
        $this->drop_table('users');
    }
} 