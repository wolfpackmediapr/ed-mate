<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SuperAdminController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Ensure the user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        middlewareAdmin();
        $this->load->model('User_model');
        $this->load->model('Course_model');
    }

    public function index()
    {
        $data['title'] = 'Super Admin Dashboard';
        
        // Get total courses count
        $data['total_courses'] = $this->db->where('isDeleted', 0)->count_all_results('courses');
        
        // Get total students count (role_id = 3)
        $data['total_students'] = $this->db->where('role_id', 3)->count_all_results('users');
        
        // Get total teachers count (role_id = 2)
        $data['total_teachers'] = $this->db->where('role_id', 2)->count_all_results('users');
        
        // Get total revenue from course_pricing
        $data['total_revenue'] = $this->db->select_sum('price')
            ->where('is_paid', 1)
            ->get('course_pricing')
            ->row()
            ->price ?? 0;

        // Get recent courses
        $data['recent_courses'] = $this->db->select('courses.*, categories.category_name, users.username as creator_name')
            ->from('courses')
            ->join('categories', 'categories.category_id = courses.category_id', 'left')
            ->join('users', 'users.user_id = courses.created_by', 'left')
            ->where('courses.isDeleted', 0)
            ->order_by('courses.course_id', 'DESC')
            ->limit(5)
            ->get()
            ->result();

        // Get recent enrollments
        $data['recent_enrollments'] = $this->db->select('course_pricing.*, courses.course_title, users.username')
            ->from('course_pricing')
            ->join('courses', 'courses.course_id = course_pricing.course_id')
            ->join('users', 'users.user_id = course_pricing.user_id')
            ->where('course_pricing.is_paid', 1)
            ->order_by('course_pricing.id', 'DESC')
            ->limit(5)
            ->get()
            ->result();

        $data['page_name'] = 'Dashboard';
        $var['content'] = $this->load->view('dashboards/super_admin', $data, true);
        $this->load->view('template2022', $var);
    }
}
