<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration {
    protected $ci;
    protected $db;

    public function __construct() {
        $this->ci = &get_instance();
        $this->db = $this->ci->db;
    }

    public function up() {
        // To be implemented by child classes
    }

    public function down() {
        // To be implemented by child classes
    }

    protected function create_table($table, $fields, $primary_key = 'id') {
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key($primary_key, TRUE);
        return $this->dbforge->create_table($table, TRUE);
    }

    protected function drop_table($table) {
        return $this->dbforge->drop_table($table, TRUE);
    }

    protected function add_column($table, $field) {
        return $this->dbforge->add_column($table, $field);
    }

    protected function drop_column($table, $column_name) {
        return $this->dbforge->drop_column($table, $column_name);
    }

    protected function modify_column($table, $field) {
        return $this->dbforge->modify_column($table, $field);
    }
} 