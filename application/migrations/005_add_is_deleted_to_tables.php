<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_is_deleted_to_tables extends CI_Migration {
    protected $migration_version = 3;

    public function up() {
        // Add isDeleted to courses table
        $fields = array(
            'isDeleted' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'is_published'
            )
        );
        $this->dbforge->add_column('courses', $fields);

        // Add isDeleted to users table
        $fields = array(
            'isDeleted' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'status'
            )
        );
        $this->dbforge->add_column('users', $fields);

        // Add isDeleted to lessons table
        $fields = array(
            'isDeleted' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'status'
            )
        );
        $this->dbforge->add_column('lessons', $fields);

        // Add isDeleted to categories table
        $fields = array(
            'isDeleted' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'status'
            )
        );
        $this->dbforge->add_column('categories', $fields);
    }

    public function down() {
        // Remove isDeleted from all tables
        $this->dbforge->drop_column('courses', 'isDeleted');
        $this->dbforge->drop_column('users', 'isDeleted');
        $this->dbforge->drop_column('lessons', 'isDeleted');
        $this->dbforge->drop_column('categories', 'isDeleted');
    }
} 