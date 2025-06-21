<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migrate extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('migration');
        
        // Only allow migrations in development environment
        if (ENVIRONMENT !== 'development') {
            show_error('Migrations are only allowed in development environment.');
        }
    }

    public function index() {
        if ($this->migration->current() === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo 'Migrations completed successfully.';
        }
    }

    public function version($version) {
        if ($this->migration->version($version) === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo 'Migration to version ' . $version . ' completed successfully.';
        }
    }

    public function rollback() {
        if ($this->migration->version(0) === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo 'Database rolled back successfully.';
        }
    }

    public function refresh() {
        // Rollback all migrations
        if ($this->migration->version(0) === FALSE) {
            show_error($this->migration->error_string());
            return;
        }

        // Run all migrations again
        if ($this->migration->current() === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo 'Database refreshed successfully.';
        }
    }

    public function status() {
        $migrations = $this->migration->find_migrations();
        $current = $this->migration->get_version();
        
        echo "Current Migration Version: " . $current . "\n\n";
        echo "Available Migrations:\n";
        foreach ($migrations as $version => $migration) {
            $status = ($version <= $current) ? '✓' : '✗';
            echo $status . ' ' . $version . ' - ' . basename($migration, '.php') . "\n";
        }
    }
} 