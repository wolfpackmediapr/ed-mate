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

    private function super_admin_dashboard()
    {
        $data['title'] = 'Admin Dashboard';
		$data['page_name'] = 'Admin Dashboard';
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
