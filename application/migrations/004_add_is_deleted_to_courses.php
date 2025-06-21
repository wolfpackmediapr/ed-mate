<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_is_deleted_to_courses extends CI_Migration {
    public function up() {
        $fields = array(
            'isDeleted' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'is_published'
            )
        );
        
        $this->dbforge->add_column('courses', $fields);
    }

    public function down() {
        $this->dbforge->drop_column('courses', 'isDeleted');
    }
} 