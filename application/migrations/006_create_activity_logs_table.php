<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_activity_logs_table extends Migration {
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
            'activity_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'Type of activity (login, course_view, etc.)'
            ],
            'entity_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
                'comment' => 'Type of entity (course, lesson, etc.)'
            ],
            'entity_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'comment' => 'ID of the related entity'
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => TRUE
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'created_at' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->create_table('activity_logs', $fields);

        // Add foreign key
        $this->db->query('ALTER TABLE activity_logs ADD CONSTRAINT fk_activity_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE');

        // Add indexes for better performance
        $this->db->query('CREATE INDEX idx_activity_user ON activity_logs(user_id)');
        $this->db->query('CREATE INDEX idx_activity_type ON activity_logs(activity_type)');
        $this->db->query('CREATE INDEX idx_activity_created_at ON activity_logs(created_at)');
    }

    public function down() {
        $this->drop_table('activity_logs');
    }
} 