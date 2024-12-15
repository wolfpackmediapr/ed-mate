<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require 'vendor/autoload.php';
include_once('vendor/getID3-master/getid3/getid3.php');
// use FFMpeg\FFMpeg;
// use FFMpeg\Media\Video;

class CoursesController extends CI_Controller
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
        $data['title'] = 'Edumate | Courses';
        $data['courses'] = $this->common_model->select_where_ASC_DESC('*', 'courses', array('isDeleted' => 0), 'course_id', 'DESC');

        $data['page_name'] = 'Courses';
        $var['content'] = $this->load->view('courses/courses', $data, true);
        $this->load->view('template2022', $var);
    }

    public function createCourse()
    {
        // Set the title and page name
        $data['title'] = 'Edumate | Create Course';
        $data['page_name'] = 'Create Course';

        // Fetch categories (not deleted) in descending order of category_id
        $data['categories'] = $this->common_model->select_where_ASC_DESC(
            'category_id, category_name', // Only fetch required columns
            'categories',
            array('isDeleted' => 0), // Ensure non-deleted records
            'category_id',
            'DESC'
        );

        // Fetch lessons (not deleted) in descending order of lesson_id
        $data['lessons'] = $this->common_model->select_where_ASC_DESC(
            'lesson_id, lesson_title', // Only fetch required columns
            'lessons',
            array('isDeleted' => 0), // Ensure non-deleted records
            'lesson_id',
            'DESC'
        );

        // Load the 'Create Course' page view
        $var['content'] = $this->load->view('courses/create_course', $data, true);
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
    

    public function uploadResources()
    {
        // Load necessary libraries and models
        $this->load->library('upload');

        $resUploadedFiles = [];
        $lesson_id = $this->input->post('lesson_id'); // Assuming course_id is sent with the request

        // Check if files were uploaded
        if (!isset($_FILES['res_files']) || empty($_FILES['res_files']['name'][0])) {
            echo json_encode(['error' => 'No files uploaded']);
            return;
        }

        // Loop through each file
        $file_count = count($_FILES['res_files']['name']); // Count the total number of files
        for ($i = 0; $i < $file_count; $i++) {

            // Set file details for each file in the array
            $_FILES['single_file']['name'] = $_FILES['res_files']['name'][$i];
            $_FILES['single_file']['type'] = $_FILES['res_files']['type'][$i];
            $_FILES['single_file']['tmp_name'] = $_FILES['res_files']['tmp_name'][$i];
            $_FILES['single_file']['error'] = $_FILES['res_files']['error'][$i];
            $_FILES['single_file']['size'] = $_FILES['res_files']['size'][$i];

            // Upload configuration
            $config['upload_path'] = './uploads/resources/'; // Set upload path
            $config['allowed_types'] = 'mp4|avi|mov|pdf'; // Allowed file types (video & pdf)
            $config['max_size'] = '10240'; // Max size in KB (10MB)
            $config['file_name'] = time() . '_' . $_FILES['single_file']['name']; // Set file name

            // Initialize upload config
            $this->upload->initialize($config);

            // Perform the upload
            if ($this->upload->do_upload('single_file')) {
                $fileData = $this->upload->data();
                $filePath = $fileData['full_path']; // Full path to the file

                // Get file details (video or PDF)
                $fileDetails = $this->getFileDetails($filePath);

                if (isset($fileDetails['error'])) {
                    echo json_encode(['error' => $fileDetails['error']]);
                    return;
                }

                // Prepare data for database insertion
                $post = [
                    'lesson_id' => $lesson_id,
                    'file_size' => $fileDetails['filesize'],
                    'path' => $fileData['file_name'],
                    'resource_type' => $fileDetails['filetype'],
                ];

                // Insert into 'uploads' table
                $upload_id = $this->common_model->insert_array('resources', $post); // Change table name if necessary

                // Append to response array
                $resUploadedFiles[] = [
                    'upload_id' => $upload_id,
                    'filetype' => $fileDetails['filetype'],
                    'name' => $fileData['file_name'],
                    'size' => $fileData['file_size'],
                    'path' => base_url('uploads/resources/' . $fileData['file_name']),
                    'thumbnail' => base_url('uploads/resources/' . $fileData['file_name']), // Placeholder thumbnail
                    'duration' => isset($fileDetails['duration']) ? $fileDetails['duration'] : null, // Video duration or null for PDFs
                    'description' => 'Uploaded successfully'
                ];
            } else {
                // Handle upload errors
                echo json_encode(['error' => $this->upload->display_errors()]);
                return;
            }
        }

        // Return the uploaded files
        echo json_encode(['resUploadedFiles' => $resUploadedFiles]);
    }


    function getFileDetails($filePath)
    {
        // Initialize getID3
        $getID3 = new getID3;

        // Analyze the file
        $file = $getID3->analyze($filePath);

        // Check the file extension/type
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        // print_r($fileExtension);
        // die;
        // If the file is a video, return video-related details
        if (in_array($fileExtension, ['mp4', 'avi', 'mov'])) {
            if (isset($file['playtime_string']) && isset($file['video']['resolution_x']) && isset($file['video']['resolution_y'])) {
                return [
                    'duration' => $file['playtime_string'],
                    'width' => $file['video']['resolution_x'],
                    'height' => $file['video']['resolution_y'],
                    'filesize' => $file['filesize'],
                    'filetype' => $fileExtension,
                ];
            } else {
                // Handle cases where video data is missing
                return [
                    'error' => 'Could not retrieve video details.'
                ];
            }
        }

        // If the file is a PDF, return PDF-specific details
        if ($fileExtension === 'pdf') {
            if (isset($file['filesize'])) {
                return [
                    'filesize' => $file['filesize'],
                    'filetype' => $fileExtension,
                    'message' => 'PDF file uploaded successfully.'
                ];
            } else {
                return [
                    'error' => 'Could not retrieve PDF details.'
                ];
            }
        }

        // If the file type is neither video nor PDF, return an error
        return [
            'error' => 'Unsupported file type.'
        ];
    }



    function getVideoDuration($filePath)
    {
        // Initialize getID3
        $getID3 = new getID3;

        // Analyze the file
        $file = $getID3->analyze($filePath);

        // Check if the necessary data is available
        if (isset($file['playtime_string']) && isset($file['video']['resolution_x']) && isset($file['video']['resolution_y'])) {
            // Return duration and other details
            return [
                'duration' => $file['playtime_string'],
                'width' => $file['video']['resolution_x'],
                'height' => $file['video']['resolution_y'],
                'filesize' => $file['filesize']
            ];
        } else {
            // Handle cases where data is missing
            return [
                'error' => 'Could not retrieve video details.'
            ];
        }
    }

    public function deleteVideo()
    {
        $fileId = $this->input->post('resource_id');
        $file = $this->common_model->select_where_return_row('*', 'resources', array('resource_id' => $fileId));

        // Construct the absolute server path for the file
        $filePath = FCPATH . 'uploads/resources/' . $file->path;

        if ($file && file_exists($filePath)) {
            unlink($filePath); // Delete the file from the server
            $this->common_model->delete_where(array('resource_id' => $fileId), 'resources');

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'File not found.']);
        }
    }

    public function save_quiz()
    {
        $quiz_title = $this->input->post('quiz_title');
        $questions = json_decode($this->input->post('questions'), true);
        $quiz_id = $this->input->post('quiz_id'); // Assuming quiz_id is sent from the frontend if it's an update
        $lesson_id = $this->input->post('lesson_id'); // Assuming quiz_id is sent from the frontend if it's an update
        $course_id = $this->input->post('course_id');
        // Start transaction to ensure atomicity
        $this->db->trans_start();

        // Check if quiz already exists
        if ($quiz_id) {
            // Update quiz details
            $quiz_data = [
                'quiz_title' => $quiz_title,
                'lesson_id' => $lesson_id,
                'course_id' => $course_id,
                'updatedAt' => date('Y-m-d H:i:s'),
            ];
            $this->db->where('id', $quiz_id);
            $this->db->update('quizzes', $quiz_data);

            // Delete existing questions and related choices for this quiz (optional: You may also update them instead)
            $this->db->where('quiz_id', $quiz_id);
            $this->db->delete('quiz_questions');

            // Delete associated quiz choices and answers
            $this->db->where('question_id IN (SELECT id FROM quiz_questions WHERE quiz_id = ' . $quiz_id . ')');
            $this->db->delete('quiz_choices');
            $this->db->where('question_id IN (SELECT id FROM quiz_questions WHERE quiz_id = ' . $quiz_id . ')');
            $this->db->delete('quiz_answers');
        } else {
            // Insert quiz details if it's a new quiz
            $quiz_data = [
                'quiz_title' => $quiz_title,
                'lesson_id' => 1,
                'course_id' => $course_id,
                'createdAt' => date('Y-m-d H:i:s'),
            ];
            $this->db->insert('quizzes', $quiz_data);
            $quiz_id = $this->db->insert_id();
        }

        // Insert/update each question with choices and correct answer
        foreach ($questions as $qIndex => $question) {
            $question_data = [
                'quiz_id' => $quiz_id,
                'question_text' => $question['questionText'],
            ];
            $this->db->insert('quiz_questions', $question_data);
            $question_id = $this->db->insert_id();

            // Insert each choice
            foreach ($question['choices'] as $cIndex => $choiceText) {
                $choice_data = [
                    'question_id' => $question_id,
                    'choice_text' => $choiceText,
                ];
                $this->db->insert('quiz_choices', $choice_data);
                $choice_id = $this->db->insert_id();

                // Save the correct choice based on index
                if ($cIndex == $question['correctAnswer']) {
                    $correct_answer_data = [
                        'question_id' => $question_id,
                        'correct_choice_id' => $choice_id,
                    ];
                    $this->db->insert('quiz_answers', $correct_answer_data);
                }
            }
        }

        // Complete the transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // Transaction failed, rollback
            echo json_encode(['success' => false, 'message' => 'Quiz submission failed.']);
        } else {
            $course_id = $this->input->post('course_id');
            $course = $this->common_model->select_where_return_row('*', 'courses', array('course_id' => $course_id));
            $resources = $this->common_model->select_where_ASC_DESC('*', 'resources', array('lesson_id' =>  $lesson_id), 'resource_id', 'DESC');


            // Success, commit
            echo json_encode(['success' => true, 'message' => 'Quiz submitted successfully.', 'resources' => $resources, 'course' => $course]);
        }
    }

    public function publishCourse()
    {
        $course_id = $this->input->post('course_id');

        $course_data = [
            'is_published' => 1,
        ];

        $this->db->where('course_id', $course_id);
        if ($this->db->update('courses', $course_data)) {
            echo json_encode(['success' => true, 'message' => 'Course published successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update the course.']);
        }
    }

    public function saveTitle() {
        // Get the title and uploadId from the request
        $title = $this->input->post('title');
        $uploadId = $this->input->post('uploadId');

        // Validate the input
        if (empty($title) || empty($uploadId)) {
            echo json_encode(['success' => false, 'message' => 'Title or Upload ID is missing.']);
            return;
        }

        // Save the title to the database
        $result = $this->common_model->updateVideoTitle($uploadId, $title);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Title saved successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save the title.']);
        }
    }
}
