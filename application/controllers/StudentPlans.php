<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require 'vendor/autoload.php';
include_once('vendor/getID3-master/getid3/getid3.php');
// use FFMpeg\FFMpeg;
// use FFMpeg\Media\Video;

class StudentPlans extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Ensure the user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        middlewareStudents();
    }

    public function index()
    {
        $course_id = $this->uri->segment(2);
        $created_by = $this->uri->segment(3);
        
        $data['title'] = 'Pricing Plan';
        $data['course_id'] = decode($course_id);
        $created_by = decode($created_by);
// echo $created_by;die;

        $data['plans'] = $this->common_model->select_where_ASC_DESC('*', 'price_plan', array('isDeleted' => 0, 'created_by' => $created_by), 'plan_id', 'ASC');
// echo "<pre>";
// print_r($data['plans']);
// die;
        $data['page_name'] = 'Pricing Plan';
        $var['content'] = $this->load->view('students/plans/choose_plan', $data, true);
        $this->load->view('template2022', $var);
    }

    function storeCourse()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('course_title', 'Course Title', 'required');

        if ($this->form_validation->run() === TRUE) {
            // Handle file upload
            $config['upload_path'] = './uploads/courses/'; // Set your upload path
            $config['allowed_types'] = 'gif|jpg|png'; // Set allowed file types
            $config['max_size'] = 5120; // Max file size in KB

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('thumbnail_image')) {
                // Upload failed
                $response = array(
                    'success' => false,
                    'message' => $this->upload->display_errors()
                );
            } else {
                // Upload successful
                $uploadData = $this->upload->data();
                $filePath = $uploadData['file_name'];

                // Gather all POST data
                $post = $this->input->post();
                $lesson_id = $this->input->post('lesson_id');

                if (is_array($lesson_id)) {
                    $lesson_id = implode(',', $lesson_id); // Convert array to comma-separated string
                }

                // Assign converted lesson_id to the post array
                $post['lesson_id'] = $lesson_id;

                // Additional fields
                $post['description'] = $this->input->post('description'); // Ensure this is also properly fetched
                $post['created_by'] = $this->session->userdata('user_id');
                $post['thumbnail_image'] = $filePath; // Add file path to data

                // Store the course in your database
                $course_id = $this->common_model->insert_array('courses', $post);

                $response = array(
                    'success' => true,
                    'course_id' => $course_id,
                    'description' => $post['description'],
                    'lesson_id' => $lesson_id,
                    'message' => 'Course stored successfully'
                );
            }
        } else {
            // Send an AJAX response with validation errors
            $response = array(
                'success' => false,
                'message' => validation_errors()
            );
        }

        // Send AJAX response
        echo json_encode($response);
    }
}