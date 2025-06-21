<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfileController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library(['form_validation', 'upload', 'security']);
        $this->load->model('User_model');
        $this->load->helper(['url', 'form', 'file']);
        
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    /**
     * Display user profile
     */
    public function index() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user($user_id);
        $data['title'] = 'My Profile';
        
        $this->load->view('templates/header', $data);
        $this->load->view('profile/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Update profile information
     */
    public function update() {
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('profile');
        } else {
            $user_id = $this->session->userdata('user_id');
            $data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->User_model->update_user($user_id, $data)) {
                $this->session->set_flashdata('success', 'Profile updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update profile');
            }
            redirect('profile');
        }
    }

    /**
     * Update profile picture
     */
    public function update_picture() {
        $user_id = $this->session->userdata('user_id');
        
        // Configure upload
        $config['upload_path'] = './uploads/profile_pictures/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = 2048; // 2MB
        $config['file_name'] = 'profile_' . $user_id . '_' . time();
        
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('profile_picture')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
        } else {
            $upload_data = $this->upload->data();
            
            // Update user profile picture
            $data = [
                'profile_picture' => $upload_data['file_name'],
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            if ($this->User_model->update_user($user_id, $data)) {
                $this->session->set_flashdata('success', 'Profile picture updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update profile picture');
            }
        }
        redirect('profile');
    }

    /**
     * Change password
     */
    public function change_password() {
        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('profile');
        } else {
            $user_id = $this->session->userdata('user_id');
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');

            // Verify current password
            $user = $this->User_model->get_user($user_id);
            if (!password_verify($current_password, $user->password)) {
                $this->session->set_flashdata('error', 'Current password is incorrect');
                redirect('profile');
            }

            // Update password
            $data = [
                'password' => password_hash($new_password, PASSWORD_DEFAULT),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->User_model->update_user($user_id, $data)) {
                $this->session->set_flashdata('success', 'Password changed successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to change password');
            }
            redirect('profile');
        }
    }

    /**
     * Get user's enrolled courses
     */
    public function my_courses() {
        $user_id = $this->session->userdata('user_id');
        $this->load->model('CourseModel');
        
        $data['courses'] = $this->CourseModel->get_enrolled_courses($user_id);
        $data['title'] = 'My Courses';
        
        $this->load->view('templates/header', $data);
        $this->load->view('profile/my_courses', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Get user's course progress
     */
    public function course_progress($course_id) {
        $user_id = $this->session->userdata('user_id');
        $this->load->model('CourseModel');
        
        $data['course'] = $this->CourseModel->get_course($course_id);
        $data['progress'] = $this->CourseModel->get_course_progress($user_id, $course_id);
        $data['title'] = 'Course Progress';
        
        $this->load->view('templates/header', $data);
        $this->load->view('profile/course_progress', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Get user's certificates
     */
    public function certificates() {
        $user_id = $this->session->userdata('user_id');
        $this->load->model('CourseModel');
        
        $data['certificates'] = $this->CourseModel->get_user_certificates($user_id);
        $data['title'] = 'My Certificates';
        
        $this->load->view('templates/header', $data);
        $this->load->view('profile/certificates', $data);
        $this->load->view('templates/footer');
    }
} 