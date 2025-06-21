<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_admin_user extends Migration {
    public function up() {
        $data = array(
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@edmate.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->insert('users', $data);
    }

    public function down() {
        $this->db->where('email', 'admin@edmate.com');
        $this->db->delete('users');
    }
} 