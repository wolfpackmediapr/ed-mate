<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    // Get user by username
    public function get_user_by_username($username)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row(); // Returns a single user object
    }

    public function get_user_by_username_or_email($identifier)
{
    // Use where condition with OR to check for both username and email
    $this->db->where('username', $identifier);
    $this->db->or_where('email', $identifier);
    $query = $this->db->get('users');
    return $query->row(); // Returns a single user object
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
}
