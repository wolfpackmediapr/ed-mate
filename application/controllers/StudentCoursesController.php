<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once('vendor/getID3-master/getid3/getid3.php');

class StudentCoursesController extends CI_Controller
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
        $data['title'] = 'My Courses';
        $data['courses'] = $this->common_model->select_where_ASC_DESC('*', 'courses', array('isDeleted' => 0), 'course_id', 'DESC');

        $data['page_name'] = 'My Courses';
        $var['content'] = $this->load->view('courses/mentors/my-courses', $data, true);
        $this->load->view('template2022', $var);
    }

    public function studentCourses()
    {
        $user_id = $this->session->userdata('user_id');
        $data['title'] = 'My Courses';

        // Select courses where payment is done (is_paid = 1)
        $data['courses'] = $this->db
            ->select('courses.*, categories.category_name, users.username, course_pricing.is_paid')
            ->from('courses')
            ->join('categories', 'courses.category_id = categories.category_id', 'left')
            ->join('users', 'courses.created_by = users.user_id', 'left')
            ->join('course_pricing', 'course_pricing.course_id = courses.course_id', 'inner') // Ensure course_pricing is joined properly
            ->where('courses.is_published', 1)
            ->where('course_pricing.is_paid', 1)
            ->where('course_pricing.user_id', $user_id)
            ->order_by('courses.course_id', 'DESC')
            ->get()
            ->result();

        $data['page_name'] = 'My Courses';
        $var['content'] = $this->load->view('courses/students/student-courses', $data, true);
        $this->load->view('template2022', $var);
    }


    public function courseDetails($course_id)
    {
        $data['title'] = 'Course Detail';

        // Fetch course details along with user info
        $data['courses'] = $this->common_model->select_courses_where_ASC_DESC(
            'courses.*, users.username',
            'courses',
            array('courses.course_id' => $course_id, 'courses.is_published' => 1),
            'courses.course_id',
            'DESC'
        );

        // Check if courses data is not empty
        if (!empty($data['courses'])) {
            $data['course'] = $data['courses'][0]; // Get the first course

            // Fetch the category based on the category_id from the course
            $data['category'] = $this->getCategoryById($data['course']->category_id);

            // Extract the lesson_id from the course
            $lesson_ids = $data['course']->lesson_id;

            // Split the lesson_id into an array
            $lesson_ids_array = explode(',', $lesson_ids);

            // Fetch lessons based on the lesson IDs
            $data['lessons'] = $this->getLessonsByIds($lesson_ids_array);

            // Fetch resources based on lesson_ids
            $data['resources'] = $this->getResourcesByLessonIds($lesson_ids_array);
        } else {
            $data['course'] = null; // No course found
            $data['category'] = null; // No category found
            $data['lessons'] = []; // No lessons found
            $data['resources'] = []; // No resources found
        }

        // echo "<pre>";
        // print_r($data);
        // die;

        $data['page_name'] = 'Course Detail';
        $var['content'] = $this->load->view('courses/students/course-details', $data, true);
        $this->load->view('template2022', $var);
    }

    // Method to fetch the category based on category_id
    private function getCategoryById($category_id)
    {
        $this->db->where('category_id', $category_id);
        $query = $this->db->get('categories'); // Assuming your categories table is named 'categories'
        return $query->row(); // Return single category object
    }


    // Method to fetch lessons based on an array of IDs
    private function getLessonsByIds($lesson_ids_array)
    {
        // Sanitize the lesson IDs for the query
        $this->db->where_in('lesson_id', $lesson_ids_array);
        $query = $this->db->get('lessons'); // Assuming your lessons table is named 'lessons'
        return $query->result();
    }

    // Method to fetch resources based on an array of lesson IDs
    private function getResourcesByLessonIds($lesson_ids_array)
    {
        // Sanitize the lesson IDs for the query
        $this->db->where_in('lesson_id', $lesson_ids_array);
        $query = $this->db->get('resources'); // Assuming your resources table is named 'resources'
        return $query->result();
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

                $description = $post['description'];
                $post['created_by'] = $this->session->userdata('user_id');
                $post['thumbnail_image'] = $filePath; // Add file path to data

                // Store the course in your database
                $course_id = $this->common_model->insert_array('courses', $post);

                $response = array(
                    'success' => true,
                    'course_id' => $course_id,
                    'description' => $description,
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
    public function uploadVideos()
    {
        // Load necessary libraries and models
        $this->load->library('upload');
        $this->load->model('common_model'); // Assuming you have a common model for DB operations

        $uploadedFiles = [];
        $lesson_id = $this->input->post('lesson_id'); // Assuming lesson_id is sent with the request

        // Check if files were uploaded
        if (!isset($_FILES['videos']) || empty($_FILES['videos']['name'])) {
            echo json_encode(['error' => 'No files uploaded']);
            return;
        }

        // Loop through each file
        foreach ($_FILES['videos']['name'] as $key => $value) {
            $_FILES['video']['name'] = $_FILES['videos']['name'][$key];
            $_FILES['video']['type'] = $_FILES['videos']['type'][$key];
            $_FILES['video']['tmp_name'] = $_FILES['videos']['tmp_name'][$key];
            $_FILES['video']['error'] = $_FILES['videos']['error'][$key];
            $_FILES['video']['size'] = $_FILES['videos']['size'][$key];

            // Upload configuration
            $config['upload_path'] = './uploads/videos/'; // Set upload path
            $config['allowed_types'] = 'mp4|avi|mov'; // Allowed file types
            $config['max_size'] = '10240'; // Max size in KB (10MB)
            $config['file_name'] = time() . '_' . $value; // File name

            $this->upload->initialize($config);

            if ($this->upload->do_upload('video')) {
                $fileData = $this->upload->data();
                // Use the full path for getID3 analysis
                $filePath = $fileData['full_path'];
                $videoDetails = $this->getVideoDuration($filePath);

                if (isset($videoDetails['error'])) {
                    echo json_encode(['error' => $videoDetails['error']]);
                    return;
                }



                // Prepare data for database insertion
                $post = [
                    'lesson_id' => $lesson_id,
                    'file_size' => $fileData['file_size'],
                    'file_path' => $fileData['file_name'],
                ];

                // Insert into 'uploads' table
                $upload_id = $this->common_model->insert_array('uploads', $post);
                $uploadedFiles[] = [
                    'upload_id' => $upload_id,
                    'name' => $fileData['file_name'],
                    'size' => $fileData['file_size'],
                    'path' => base_url('uploads/videos/' . $fileData['file_name']),
                    'thumbnail' => base_url('uploads/videos/' . $fileData['file_name']), // Update this if you have thumbnails
                    'duration' => $videoDetails['duration'], // Update with actual duration
                    'description' => 'Uploaded successfully'
                ];
            } else {
                // Handle errors
                echo json_encode(['error' => $this->upload->display_errors()]);
                return;
            }
        }

        // Return the uploaded files
        echo json_encode(['uploadedFiles' => $uploadedFiles]);
    }
    public function uploadResources()
    {
        // Load necessary libraries and models
        $this->load->library('upload');

        $resUploadedFiles = [];
        $course_id = $this->input->post('course_id'); // Assuming course_id is sent with the request

        // Check if files were uploaded
        if (!isset($_FILES['res_files']) || empty($_FILES['res_files']['name'])) {
            echo json_encode(['error' => 'No files uploaded']);
            return;
        }

        // Loop through each file
        foreach ($_FILES['res_files']['name'] as $key => $value) {
            // Set file details for each file in the array
            $_FILES['res_files']['name'] = $_FILES['res_files']['name'][$key];
            $_FILES['res_files']['type'] = $_FILES['res_files']['type'][$key];
            $_FILES['res_files']['tmp_name'] = $_FILES['res_files']['tmp_name'][$key];
            $_FILES['res_files']['error'] = $_FILES['res_files']['error'][$key];
            $_FILES['res_files']['size'] = $_FILES['res_files']['size'][$key];

            // Upload configuration
            $config['upload_path'] = './uploads/resources/'; // Set upload path
            $config['allowed_types'] = 'mp4|avi|mov|pdf'; // Allowed file types (video & pdf)
            $config['max_size'] = '10240'; // Max size in KB (10MB)
            $config['file_name'] = time() . '_' . $value; // Set file name

            // Initialize upload config
            $this->upload->initialize($config);

            // Perform the upload
            if ($this->upload->do_upload('res_files')) {
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
                    'course_id' => $course_id,
                    'file_size' => $fileDetails['filesize'],
                    'path' => $fileData['file_name'],
                ];

                // Insert into 'uploads' table
                $upload_id = $this->common_model->insert_array('videos', $post); // Change table name if necessary

                // Append to response array
                $resUploadedFiles[] = [
                    '_id' => $upload_id,
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

        // If the file is a video, return video-related details
        if (in_array($fileExtension, ['mp4', 'avi', 'mov'])) {
            if (isset($file['playtime_string']) && isset($file['video']['resolution_x']) && isset($file['video']['resolution_y'])) {
                return [
                    'duration' => $file['playtime_string'],
                    'width' => $file['video']['resolution_x'],
                    'height' => $file['video']['resolution_y'],
                    'filesize' => $file['filesize']
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
        $fileId = $this->input->post('upload_id');
        $file = $this->common_model->select_where_return_row('*', 'uploads', array('upload_id' => $fileId));

        // Construct the absolute server path for the file
        $filePath = FCPATH . 'uploads/videos/' . $file->file_path;

        if ($file && file_exists($filePath)) {
            unlink($filePath); // Delete the file from the server
            $this->common_model->delete_where(array('upload_id' => $fileId), 'uploads');

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

        // Start transaction to ensure atomicity
        $this->db->trans_start();

        // Check if quiz already exists
        if ($quiz_id) {
            // Update quiz details
            $quiz_data = [
                'quiz_title' => $quiz_title,
                'lesson_id' => 1,
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
            $videos = $this->common_model->select_where_ASC_DESC('*', 'videos', array('course_id' =>  $course_id), 'id', 'DESC');


            // Success, commit
            echo json_encode(['success' => true, 'message' => 'Quiz submitted successfully.', 'resources' => $videos, 'course' => $course]);
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

    function renderVideo()
    {

        $this->form_validation->set_rules('resource_id', 'Resource Id', 'required');

        if ($this->form_validation->run() === TRUE) {
            $resource_id = $this->input->post('resource_id');
            $course_id = $this->input->post('course_id');
            $lesson_id = $this->input->post('lesson_id');

            $course = $this->common_model->select_where_return_row('*', 'courses', array('course_id' => $course_id));
            $category = $this->getCategoryById($course->category_id);
            $createdBY = $this->common_model->select_where_return_row('*', 'users', array('user_id' => $course->created_by));
            $course = $this->common_model->select_where_return_row('*', 'courses', array('course_id' => $course_id));
            $resource = $this->common_model->select_where_return_row('*', 'resources', array('resource_id' => $resource_id));
            $lesson = $this->common_model->select_where_return_row('*', 'lessons', array('lesson_id' => $lesson_id));

            // print_r($course);
            // die;

            if (!$course) {
                echo json_encode(array('success' => false, 'message' => 'not found'));
            } else {
                echo json_encode(array('success' => true, 'course' => $course, 'category' => $category, 'createdBY' => $createdBY, 'lesson' => $lesson, 'resource' => $resource));
            }

            // Send an AJAX response
            // echo json_encode(array('success' => true, 'message' => 'Lesson stored successfully', 'lesson_id' => $lesson_id));
        } else {
            // Send an AJAX response with errors
            echo json_encode(array('success' => false, 'message' => validation_errors()));
        }
    }


    public function updateVideoProgress()
    {
        // Load your models if not autoloaded

        // Get the input from the AJAX request
        $resource_id = $this->input->post('resource_id');
        $course_id = $this->input->post('course_id');
        $lesson_id = $this->input->post('lesson_id');
        $percent_watched = $this->input->post('percent_watched');

        // Save or update the progress in the database
        $this->common_model->save_video_progress($resource_id, $course_id, $lesson_id, $percent_watched);

        echo json_encode(['status' => 'success']);
    }
}
