<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Get user by username
    public function get_user_by_username($username)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row(); // Returns a single user object
    }

    public function get_user_by_username_or_email($username)
    {
        $this->db->where('username', $username);
        $this->db->or_where('email', $username);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Get role name by user ID
    public function get_role_by_user_id($user_id)
    {
        $this->db->select('roles.role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.role_id = users.role_id');
        $this->db->where('users.user_id', $user_id);
        $query = $this->db->get();
        return $query->row()->role_name; // Returns role name as string
    }

    public function get_user_by_id($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('users');
        return $query->row();
    }

    public function create_user($data)
    {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function update_user($user_id, $data)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    public function delete_user($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->delete('users');
    }

    // Get all students (role_id = 3)
    public function get_all_students()
    {
        $this->db->where('role_id', 3);
        $query = $this->db->get('users');
        return $query->result();
    }

    // Get all students without a supabase_id
    public function get_students_without_supabase_id()
    {
        $this->db->where('role_id', 3);
        $this->db->where('(supabase_id IS NULL OR supabase_id = "")', NULL, FALSE);
        $query = $this->db->get('users');
        return $query->result();
    }

    // Get students by mentor/teacher (students enrolled in mentor's courses)
    public function get_students_by_mentor($mentor_id)
    {
        $this->db->select('DISTINCT u.*');
        $this->db->from('users u');
        $this->db->join('course_pricing cp', 'cp.user_id = u.user_id');
        $this->db->join('courses c', 'c.course_id = cp.course_id');
        $this->db->where('c.created_by', $mentor_id);
        $this->db->where('u.role_id', 3);
        $this->db->where('cp.is_paid', 1);
        $query = $this->db->get();
        return $query->result();
    }

    // Get all mentors (role_id = 2 or 3)
    public function get_all_mentors()
    {
        $this->db->where_in('role_id', [2, 3]);
        $query = $this->db->get('users');
        return $query->result();
    }

    /**
     * Get all users
     */
    public function get_all_users()
    {
        try {
            $result = supabase_query('users', [
                'select' => 'id,email,name,avatar_url,created_at',
                'order' => 'created_at.desc'
            ]);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Users fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
