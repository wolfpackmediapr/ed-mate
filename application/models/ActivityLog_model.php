<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ActivityLog_model extends CI_Model {
    private $table = 'activity_logs';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Log user activity
     */
    public function log_activity($user_id, $activity_type, $entity_type = null, $entity_id = null) {
        $data = [
            'user_id' => $user_id,
            'activity_type' => $activity_type,
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->db->insert($this->table, $data);
    }

    /**
     * Get user activity logs
     */
    public function get_user_activity($user_id, $limit = 10, $offset = 0) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result();
    }

    /**
     * Get activity logs by type
     */
    public function get_activity_by_type($activity_type, $limit = 10, $offset = 0) {
        $this->db->where('activity_type', $activity_type);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result();
    }

    /**
     * Get entity activity logs
     */
    public function get_entity_activity($entity_type, $entity_id, $limit = 10, $offset = 0) {
        $this->db->where('entity_type', $entity_type);
        $this->db->where('entity_id', $entity_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result();
    }

    /**
     * Get recent activity logs
     */
    public function get_recent_activity($limit = 10) {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get($this->table)->result();
    }
} 