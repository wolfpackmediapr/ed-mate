<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_enrollments_table extends CI_Migration {
    public function up() {
        // Create payments table first
        $payment_fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ],
            'currency' => [
                'type' => 'VARCHAR',
                'constraint' => 3,
                'default' => 'USD'
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'transaction_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ],
            'status' => [
                'type' => 'ENUM("pending", "completed", "failed", "refunded")',
                'default' => 'pending'
            ],
            'created_at' => [
                'type' => 'DATETIME'
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ];
        $this->create_table('payments', $payment_fields);

        // Create enrollments table
        $enrollment_fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ],
            'payment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ],
            'status' => [
                'type' => 'ENUM("active", "completed", "expired", "cancelled")',
                'default' => 'active'
            ],
            'enrolled_at' => [
                'type' => 'DATETIME'
            ],
            'expires_at' => [
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
        $this->create_table('enrollments', $enrollment_fields);

        // Add foreign keys
        $this->db->query('ALTER TABLE payments ADD CONSTRAINT fk_payment_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE');
        
        $this->db->query('ALTER TABLE enrollments ADD CONSTRAINT fk_enrollment_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE enrollments ADD CONSTRAINT fk_enrollment_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE enrollments ADD CONSTRAINT fk_enrollment_payment FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down() {
        $this->drop_table('enrollments');
        $this->drop_table('payments');
    }
} 