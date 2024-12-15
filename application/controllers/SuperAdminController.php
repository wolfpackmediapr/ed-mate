<?php

class SuperAdminController extends CI_Controller
{
    public function index()
    {
        check_role('Super Admin');
        $this->load->view('superadmin/dashboard');
    }
}
