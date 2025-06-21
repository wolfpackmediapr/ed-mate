<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PagesController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // Check if user is logged in as super admin
        if (!$this->session->userdata('is_super_admin')) {
            redirect('login');
        }
    }
    public function assignment() { 
        $data['title'] = 'Assignments';
        $data['page_name'] = 'Assignments';
        $var['content'] = $this->load->view('pages/assignment', $data, true);
        $this->load->view('template2022', $var);
    }
    public function mentors() {
        $this->load->model('User_model');
        $data['mentors'] = $this->User_model->get_all_mentors();
        $data['page_name'] = 'Mentors';
        $var['content'] = $this->load->view('pages/mentors', $data, true);
        $this->load->view('template2022', $var);
    }
    public function resources() {
        $this->load->model('Lesson_model');
        $resources = $this->Lesson_model->get_all_resources();
        $data['resources'] = $resources['success'] ? $resources['data'] : [];
        $data['page_name'] = 'Resources';
        $var['content'] = $this->load->view('pages/resources', $data, true);
        $this->load->view('template2022', $var);
    }
    public function message() { 
        $this->load->model('Message_model');
        $this->load->model('User_model');
        
        $user_id = $this->session->userdata('user_id');
        $messages = $this->Message_model->get_user_messages($user_id);
        $users_result = $this->User_model->get_all_users();
        
        $data['messages'] = $messages['success'] ? $messages['data'] : [];
        $data['users'] = $users_result['success'] ? $users_result['data'] : [];
        $data['current_user'] = $user_id;
        $data['page_name'] = 'Messages';
        
        if (!$users_result['success']) {
            $this->session->set_flashdata('error', 'Failed to load users: ' . ($users_result['error'] ?? 'Unknown error'));
        }
        
        $var['content'] = $this->load->view('pages/message', $data, true);
        $this->load->view('template2022', $var);
    }
    public function analytics() { 
        $this->load->model('Analytics_model');
        
        // Get all analytics data
        $enrollments = $this->Analytics_model->get_course_enrollments();
        $revenue = $this->Analytics_model->get_revenue_stats();
        $user_growth = $this->Analytics_model->get_user_growth();
        $completion_rates = $this->Analytics_model->get_course_completion_rates();
        $active_users = $this->Analytics_model->get_active_users();
        
        $data = [
            'enrollments' => $enrollments['success'] ? $enrollments['data'] : [],
            'revenue' => $revenue['success'] ? $revenue['data'] : [],
            'user_growth' => $user_growth['success'] ? $user_growth['data'] : [],
            'completion_rates' => $completion_rates['success'] ? $completion_rates['data'] : [],
            'active_users' => $active_users['success'] ? $active_users['data'] : [],
            'page_name' => 'Analytics'
        ];
        
        $var['content'] = $this->load->view('pages/analytics', $data, true);
        $this->load->view('template2022', $var);
    }
    public function event() { $this->load->view('pages/event'); }
    public function library() { $this->load->view('pages/library'); }
    public function setting() { $this->load->view('pages/setting'); }
    public function sign_in() { $this->load->view('pages/sign_in'); }
    public function sign_up() { $this->load->view('pages/sign_up'); }
    public function forgot_password() { $this->load->view('pages/forgot_password'); }
    public function reset_password() { $this->load->view('pages/reset_password'); }
    public function verify_email() { $this->load->view('pages/verify_email'); }
    public function two_step_verification() { $this->load->view('pages/two_step_verification'); }
} 