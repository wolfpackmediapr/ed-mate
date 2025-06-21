<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('middlewareAdmin')) {
    function middlewareAdmin()
    {
        $CI = &get_instance();
        $user_id = $CI->session->userdata('user_id');
        
        if (!$user_id) {
            redirect('login');
        }

        // Get user role
        $CI->load->model('User_model');
        $user = $CI->User_model->get_user_by_id($user_id);
        
        if (!$user) {
            redirect('login');
        }

        // Check if user is super admin (role_id = 1) or teacher (role_id = 2)
        if ($user->role_id != 1 && $user->role_id != 2) {
            redirect('unauthorized');
        }
    }
}

if (!function_exists('middlewareSuperAdmin')) {
    function middlewareSuperAdmin()
    {
        $CI = &get_instance();
        $user_id = $CI->session->userdata('user_id');
        
        if (!$user_id) {
            redirect('login');
        }

        // Get user role
        $CI->load->model('User_model');
        $user = $CI->User_model->get_user_by_id($user_id);
        
        if (!$user) {
            redirect('login');
        }

        // Check if user is super admin (role_id = 1)
        if ($user->role_id != 1) {
            redirect('unauthorized');
        }
    }
} 