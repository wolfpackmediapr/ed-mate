<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StudentsController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Ensure the user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        // Load any required models here
        $this->load->model('User_model');
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        $data['title'] = 'Students List';
        $data['page_name'] = 'Students';

        // Check user role
        if ($user->role_id == 1) { // Super Admin
            // Super Admin can see all students with full details
            $data['students'] = $this->User_model->get_all_students();
        } else { // Mentor/Teacher
            // Mentor/Teacher sees only students enrolled in their courses
            $data['students'] = $this->User_model->get_students_for_mentor($user_id);
        }

        $var['content'] = $this->load->view('students/students', $data, true);
        $this->load->view('template2022', $var);
    }

    public function getStudentDetails($id) {
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);

        // Check user role
        if ($user->role_id == 1) { // Super Admin
            // Super Admin can see full details of any student
            $student = $this->User_model->get_user_by_id($id);
        } else { // Mentor/Teacher
            // Mentor/Teacher can only see details of students enrolled in their courses
            $student = $this->User_model->get_student_details_for_mentor($id, $user_id);
        }

        if ($student) {
            $result = [
                'id' => $student->user_id,
                'name' => trim($student->first_name . ' ' . $student->last_name),
                'email' => $student->email,
            ];
            echo json_encode($result);
        } else {
            echo json_encode(['error' => 'Student not found or access denied']);
        }
    }

    public function getStudents()
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);

        if ($user->role_id == 1) {
            // Super Admin: Fetch all students
            $students = $this->User_model->get_all_students();
        } else {
            // Mentor/Teacher: Fetch only students enrolled in their courses
            $students = $this->User_model->get_students_by_mentor($user_id);
        }

        // Map to expected structure
        $result = [];
        foreach ($students as $student) {
            $result[] = [
                'id' => $student->user_id,
                'name' => trim($student->first_name . ' ' . $student->last_name),
                'email' => $student->email,
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function create() {
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);

        // Only Super Admin can access
        if ($user->role_id != 1) {
            show_error('Access denied');
            return;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            // Show the form
            $data['title'] = 'Add Student';
            $data['page_name'] = 'Add Student';
            $var['content'] = $this->load->view('students/create', $data, true);
            $this->load->view('template2022', $var);
        } else {
            // Create the student in Supabase Auth first
            $this->load->helper('supabase');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $supabaseResult = supabase_signup($email, $password);
            if (isset($supabaseResult['user'])) {
                // Proceed to create user in local DB
                $student_data = [
                    'username' => $this->input->post('username'),
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'role_id' => 3, // Student role
                    'created_at' => date('Y-m-d H:i:s'),
                    // Optionally store Supabase user ID
                    'supabase_id' => $supabaseResult['user']['id'] ?? null
                ];
                $this->User_model->create_user($student_data);
                $this->session->set_flashdata('success', 'Student created successfully!');
                redirect('students');
            } else {
                // Handle error (show message)
                $this->session->set_flashdata('error', 'Failed to create user in Supabase: ' . ($supabaseResult['error'] ?? json_encode($supabaseResult)));
                redirect('students/create');
            }
        }
    }

    // Sync existing students to Supabase Auth
    public function sync_to_supabase() {
        // Check if user is logged in and is Super Admin
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            show_error('Please log in first');
            return;
        }
        
        $user = $this->User_model->get_user_by_id($user_id);
        if ($user->role_id != 1) {
            show_error('Only Super Admin can perform this action');
            return;
        }

        $this->load->helper('supabase');
        $this->load->model('User_model');
        
        // Get students without Supabase ID
        $students = $this->User_model->get_students_without_supabase_id();
        if (empty($students)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'No students need syncing - all students are already in Supabase Auth',
                'synced' => 0,
                'failed' => []
            ]);
            return;
        }

        $synced = 0;
        $failed = [];
        $results = [];

        foreach ($students as $student) {
            try {
                // Generate a random password
                $password = bin2hex(random_bytes(6)); // 12-char random password
                
                // Create user in Supabase Auth
                $result = supabase_signup($student->email, $password);
                
                if (isset($result['user']['id'])) {
                    // Update local DB with supabase_id
                    $this->User_model->update_user($student->user_id, [
                        'supabase_id' => $result['user']['id']
                    ]);
                    
                    $synced++;
                    $results[] = [
                        'email' => $student->email,
                        'status' => 'success',
                        'supabase_id' => $result['user']['id']
                    ];
                    
                    // Log the successful sync
                    log_message('info', "Successfully synced student {$student->email} to Supabase Auth");
                } else {
                    $error = $result['error'] ?? json_encode($result);
                    $failed[] = $student->email;
                    $results[] = [
                        'email' => $student->email,
                        'status' => 'failed',
                        'error' => $error
                    ];
                    
                    // Log the failure
                    log_message('error', "Failed to sync student {$student->email} to Supabase Auth: {$error}");
                }
            } catch (Exception $e) {
                $failed[] = $student->email;
                $results[] = [
                    'email' => $student->email,
                    'status' => 'error',
                    'error' => $e->getMessage()
                ];
                
                // Log the exception
                log_message('error', "Exception while syncing student {$student->email}: " . $e->getMessage());
            }
        }

        // Return JSON response
        $response = [
            'status' => 'success',
            'message' => "Sync completed. Successfully synced {$synced} students.",
            'synced' => $synced,
            'failed' => $failed,
            'results' => $results
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
} 