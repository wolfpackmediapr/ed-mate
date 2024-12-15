<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LessonsController extends CI_Controller
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
        $data['title'] = 'Edumate | Lessons';
        if (!getRoleName() == 'Super Admin') {
            $conditions = array('isDeleted' => 0, 'createdBy' => $this->session->userdata('user_id'));
        } else {
            $conditions = array('isDeleted' => 0);
        }
        $data['lessons'] = $this->common_model->select_where_ASC_DESC('*', 'lessons', $conditions, 'lesson_id', 'DESC');

        $data['page_name'] = 'Lessons';
        $var['content'] = $this->load->view('lessons/lessons', $data, true);
        $this->load->view('template2022', $var);
    }

    public function createLesson()
    {
        // $role_name = customMiddleware();
        $data['title'] = 'Edumate | Create Lesson';
        // $data['categories'] = $this->common_model->select_where_ASC_DESC('*', 'categories', array('isDeleted' => 0), 'category_id', 'DESC');
        $data['lessons'] = $this->common_model->select_where_ASC_DESC('*', 'lessons', array('isDeleted' => 0), 'lesson_id', 'DESC');

        $data['page_name'] = 'Create Lesson';
        $var['content'] = $this->load->view('lessons/add_lesson', $data, true);
        $this->load->view('template2022', $var);
    }

    function storeLesson()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('lesson_title', 'Lesson Title', 'required');

        if ($this->form_validation->run() === TRUE) {
            $post = $this->input->post();
            $post['createdBy'] = $this->session->userdata('user_id');
            $lesson_id = $this->input->post('lesson_id');
            if (!$lesson_id) {
                $lesson_id = $this->common_model->insert_array('lessons', $post);
            } else {
                $isDeleted = $this->common_model->update_array(array('lesson_id' => $lesson_id), 'lessons', $post);
            }

            // Send an AJAX response
            echo json_encode(array('success' => true, 'message' => 'Lesson stored successfully', 'lesson_id' => $lesson_id));
        } else {
            // Send an AJAX response with errors
            echo json_encode(array('success' => false, 'message' => validation_errors()));
        }
    }

    function publishLesson()
    {
        $lesson_id = $this->input->post('lesson_id');
        $is_updated = $this->common_model->update_array(array('lesson_id' => $lesson_id), 'lessons', array('status' => 1));
        if ($is_updated) {
            echo json_encode(array('success' => true, 'message' => 'Lesson stored successfully', 'lesson_id' => $lesson_id));
            die;
        }
        echo json_encode(array('error' => true, 'message' => 'Error'));
        die;
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
