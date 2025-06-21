<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_lessons_table extends Migration {
    public function up() {
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'content' => [
                'type' => 'TEXT'
            ],
            'video_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'duration' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Duration in minutes'
            ],
            'order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'status' => [
                'type' => 'ENUM("draft", "published")',
                'default' => 'draft'
            ],
            'created_at' => [
                'type' => 'DATETIME'
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ];

        $this->create_table('lessons', $fields);

        // Add foreign key
        $this->db->query('ALTER TABLE lessons ADD CONSTRAINT fk_lesson_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE ON UPDATE CASCADE');

        // Create lesson progress table
        $progress_fields = [
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
            'lesson_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ],
            'status' => [
                'type' => 'ENUM("not_started", "in_progress", "completed")',
                'default' => 'not_started'
            ],
            'progress' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
                'comment' => 'Progress percentage'
            ],
            'last_accessed' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],
            'completed_at' => [
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

        $this->create_table('lesson_progress', $progress_fields);

        // Add foreign keys for progress table
        $this->db->query('ALTER TABLE lesson_progress ADD CONSTRAINT fk_progress_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE lesson_progress ADD CONSTRAINT fk_progress_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down() {
        $this->drop_table('lesson_progress');
        $this->drop_table('lessons');
    }
} 