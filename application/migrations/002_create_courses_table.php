<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_create_courses_table extends Migration {
    public function up() {
        // Create categories table first
        $category_fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => TRUE
            ],
            'description' => [
                'type' => 'TEXT',
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
        $this->create_table('categories', $category_fields);

        // Create courses table
        $course_fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => TRUE
            ],
            'description' => [
                'type' => 'TEXT'
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ],
            'instructor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00
            ],
            'discount_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE
            ],
            'thumbnail' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'status' => [
                'type' => 'ENUM("draft", "published", "archived")',
                'default' => 'draft'
            ],
            'featured' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'created_at' => [
                'type' => 'DATETIME'
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ];
        $this->create_table('courses', $course_fields);

        // Add foreign keys
        $this->db->query('ALTER TABLE courses ADD CONSTRAINT fk_course_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE courses ADD CONSTRAINT fk_course_instructor FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE');
    }

    public function down() {
        $this->drop_table('courses');
        $this->drop_table('categories');
    }
} 