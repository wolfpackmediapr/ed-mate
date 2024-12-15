<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CategoriesController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Ensure the user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        middlewareAdmin();
    }

    public function index()
    {
        // $role_name = customMiddleware();
        $data['title'] = 'Edumate | Categories';
        $data['categories'] = $this->common_model->select_where_ASC_DESC('*', 'categories', array('isDeleted' => 0), 'category_id', 'DESC');

        $data['page_name'] = 'Categories';
        $var['content'] = $this->load->view('categories/categories', $data, true);
        $this->load->view('template2022', $var);
    }

    public function createCateggory()
    {
        // $role_name = customMiddleware();
        $data['title'] = 'Edumate | Create Category';
        $data['categories'] = $this->common_model->select_where_ASC_DESC('*', 'categories', array('isDeleted' => 0), 'category_id', 'DESC');

        $data['page_name'] = 'Create Category';
        $var['content'] = $this->load->view('categories/add_category', $data, true);
        $this->load->view('template2022', $var);
    }

    function storeCategory()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('category_name', 'Category Name', 'required');

        if ($this->form_validation->run() === TRUE) {
            $post = $this->input->post();
            $post['createdBy'] = $this->session->userdata('username');

            // Store the category in your database
            // $this->db->insert('categories', array('name' => $post));
            $this->common_model->insert_array('categories', $post);

            // Send an AJAX response
            echo json_encode(array('success' => true, 'message' => 'Category stored successfully'));
        } else {
            // Send an AJAX response with errors
            echo json_encode(array('success' => false, 'message' => validation_errors()));
        }
    }

    private function super_admin_dashboard()
    {
        $data['title'] = 'Admin Dashboard';
        $data['page_name'] = 'Admin Dashboard';
        $var['content'] = $this->load->view('dashboards/super_admin', $data, true);
        $this->load->view('template2022', $var);
    }

    private function categories()
    {
        $data['title'] = 'Edumate | Categories';
        $data['page_name'] = 'Categories';
        $var['content'] = $this->load->view('categories/categories', $data, true);
        $this->load->view('template2022', $var);
    }
}
