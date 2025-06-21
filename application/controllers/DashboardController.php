<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->load->model('User_model');
        // Ensure the user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index()
    {
        $role_name = $this->user_model->get_role_by_user_id($this->session->userdata('user_id'));

        // Redirect based on role
        switch ($role_name) {
            case 'Super Admin':
                $this->super_admin_dashboard();
                break;
            case 'Admin for Teachers':
                $this->admin_teachers_dashboard();
                break;
            case 'Student':
                $this->student_dashboard();
                break;
            case 'Mentor':
                $this->mentor_dashboard();
                break;
            default:
                show_error('No dashboard available for this role.');
        }
    }

    public function super_admin_dashboard()
    {
        // Get total courses count
        $data['total_courses'] = $this->db->count_all('courses');
        
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
            ->order_by('courses.course_id', 'DESC')
            ->limit(5)
            ->get()
            ->result();

        // Get recent enrollments
        $data['recent_enrollments'] = $this->db->select('course_pricing.*, courses.title as course_title, users.username, course_pricing.created_at')
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

    private function admin_teachers_dashboard()
    {
        $data['title'] = 'Teacher Dashboard';
		$data['page_name'] = 'Teachers Dashboard';
        $var['content'] = $this->load->view('dashboards/admin_teachers', $data, true);
		$this->load->view('template2022', $var);
    }

    private function student_dashboard()
    {
        $data['title'] = 'Student Dashboard';
		$data['page_name'] = 'Student Dashboard';
        $var['content'] = $this->load->view('dashboards/student', $data, true);
		$this->load->view('template2022', $var);
    }

    private function mentor_dashboard()
    {
        $data['title'] = 'Mentor Dashboard';
		$data['page_name'] = 'Mentor Dashboard';
        $var['content'] = $this->load->view('dashboards/mentor', $data, true);
		$this->load->view('template2022', $var);
    }
}
