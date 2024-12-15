<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->user_model->get_user_by_username_or_email($username);

            if ($user && $user->password === md5($password)) {
                $this->session->set_userdata([
                    'user_id' => $user->user_id,
                    'role_id' => $user->role_id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'logged_in' => TRUE
                ]);
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid username or password');
                redirect(base_url());
            }
        }

        $this->load->view('pages/auth/login');
    }

    public function unauthorized()
    {
        // Load an unauthorized access view
        $this->load->view('auth/unauthorized');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
