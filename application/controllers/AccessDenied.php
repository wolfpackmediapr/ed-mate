<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccessDenied extends CI_Controller {
    public function index() {
        $this->load->view('no_access'); // Load custom view
    }
}
