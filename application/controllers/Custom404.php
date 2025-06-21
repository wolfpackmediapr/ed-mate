<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Custom404 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->output->set_status_header('404'); // Set 404 status code
        $this->load->view('custom_404'); // Load custom 404 view
    }
}
