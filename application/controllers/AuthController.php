<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('supabase');
        $this->load->library('session');
        $this->load->model('User_model');
        $this->load->model('ActivityLog_model');
    }

    public function login()
    {
        $this->load->helper('supabase_auth');
        if ($this->input->post()) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            
            // First try to authenticate with Supabase
            $result = supabaseSignIn($email, $password);
            
            if (isset($result['access_token'])) {
                // Check if user exists in local database
                $user = $this->User_model->get_user_by_username_or_email($email);
                
                if (!$user) {
                    // Create user in local database
                    $user_data = [
                        'email' => $email,
                        'username' => $email,
                        'role_id' => 4, // Assuming 4 is the role_id for 'Student'
                        'created_at' => date('Y-m-d H:i:s'),
                        'supabase_id' => $result['user']['id'] ?? null
                    ];
                    $user_id = $this->User_model->create_user($user_data);
                    $user = $this->User_model->get_user_by_id($user_id);
                }
                
                // Log the login activity
                $this->ActivityLog_model->log_activity($user->user_id, 'login');
                
                // Set session data
                $this->session->set_userdata([
                    'supabase_token' => $result['access_token'],
                    'supabase_user' => $result['user'] ?? null,
                    'user_id' => $user->user_id,
                    'logged_in' => TRUE,
                    'email' => $user->email,
                    'username' => $user->username,
                    'role_id' => $user->role_id
                ]);
                
                // Check if user is a super admin (assuming role_id 1 is super admin)
                if ($user->role_id == 1) {
                    $this->session->set_userdata('is_super_admin', true);
                } else {
                    $this->session->set_userdata('is_super_admin', false);
                }
                
                redirect('dashboard');
            } else {
                $error = $result['error'] ?? 'Invalid credentials';
                if (stripos($error, 'invalid login credentials') !== false) {
                    $error = 'Incorrect email or password.';
                } elseif (stripos($error, 'email not confirmed') !== false) {
                    $error = 'Please confirm your email before logging in.';
                }
                $this->session->set_flashdata('error', $error);
                redirect('login');
            }
        }
        
        $this->load->view('pages/auth/login');
    }

    public function register()
    {
        if ($this->input->post()) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $result = supabase_signup($email, $password);
            if (isset($result['user'])) {
                $user_data = [
                    'email' => $email,
                    'username' => $email,
                    'role_id' => 4,
                    'created_at' => date('Y-m-d H:i:s'),
                    'supabase_id' => $result['user']['id']
                ];
                $user_id = $this->User_model->create_user($user_data);
                if ($user_id) {
                    $this->session->set_flashdata('success', 'Registration successful! Please check your email to confirm your account.');
                    redirect('login');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create local user account.');
                    redirect('register');
                }
            } else {
                $error = $result['error'] ?? $result['msg'] ?? 'Registration failed.';
                // Handle repeated signup (user already exists)
                if ((isset($result['msg']) && stripos($result['msg'], 'user_repeated_signup') !== false) ||
                    (isset($result['error']) && stripos($result['error'], 'user already registered') !== false)) {
                    $error = 'This email is already registered. Please log in or use another email.';
                } elseif (stripos($error, 'weak_password') !== false || stripos($error, 'Password should be at least 6 characters') !== false) {
                    $error = 'Password must be at least 6 characters.';
                } elseif (stripos($error, 'invalid email') !== false) {
                    $error = 'Please enter a valid email address.';
                }
                // Log the raw error for debugging
                log_message('error', 'Supabase registration error: ' . json_encode($result));
                $this->session->set_flashdata('error', $error);
                redirect('register');
            }
        }
        $this->load->view('pages/auth/register');
    }

    public function forgot_password()
    {
        if ($this->input->post()) {
            $email = $this->input->post('email');
            $result = supabase_forgot_password($email);
            if (empty($result['error'])) {
                $this->session->set_flashdata('success', 'Password reset email sent!');
                redirect(base_url('login'));
            } else {
                $this->session->set_flashdata('error', $result['error']);
                redirect(base_url('forgot-password'));
            }
        }
        $this->load->view('pages/auth/forgot_password');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
    
    public function unauthorized()
    {
        $this->load->view('auth/unauthorized');
    }
}
