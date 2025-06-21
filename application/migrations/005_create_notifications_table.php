<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_notifications_table extends Migration {
    public function up() {
        $fields = [
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
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'Type of notification (enrollment, completion, etc.)'
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'message' => [
                'type' => 'TEXT'
            ],
            'related_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'comment' => 'ID of related entity (course_id, lesson_id, etc.)'
            ],
            'related_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
                'comment' => 'Type of related entity (course, lesson, etc.)'
            ],
            'is_read' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'read_at' => [
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

        $this->create_table('notifications', $fields);

        // Add foreign key
        $this->db->query('ALTER TABLE notifications ADD CONSTRAINT fk_notification_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');

        // Add indexes for better performance
        $this->db->query('CREATE INDEX idx_notification_user_type ON notifications(user_id, type)');
        $this->db->query('CREATE INDEX idx_notification_created_at ON notifications(created_at)');
        $this->db->query('CREATE INDEX idx_notification_is_read ON notifications(is_read)');
    }

    public function down() {
        $this->drop_table('notifications');
    }
} 