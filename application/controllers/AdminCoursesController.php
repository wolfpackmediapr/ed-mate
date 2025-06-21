<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once('vendor/getID3-master/getid3/getid3.php');

class AdminCoursesController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Ensure the user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        middlewareAdmin();
        $this->load->model('User_model');
        $this->load->model('Course_model');
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        
        $data['title'] = 'My Courses';
        
        // If super admin, show all courses. If teacher, show only their courses
        if ($user->role_id == 1) {
            $data['courses'] = $this->common_model->select_where_ASC_DESC('*', 'courses', array('isDeleted' => 0), 'course_id', 'DESC');
        } else {
            $data['courses'] = $this->common_model->select_where_ASC_DESC('*', 'courses', array('isDeleted' => 0, 'created_by' => $user_id), 'course_id', 'DESC');
        }

        // Add stock images to courses without thumbnails
        $this->load->helper('stock_image');
        foreach ($data['courses'] as $course) {
            if (empty($course->thumbnail_image)) {
                $thumbnailUrl = get_pexels_image('blockchain');
                if ($thumbnailUrl) {
                    $this->common_model->update_array(
                        array('course_id' => $course->course_id),
                        'courses',
                        array('thumbnail_image' => $thumbnailUrl)
                    );
                }
            }
        }

        $data['page_name'] = 'My Courses';
        $var['content'] = $this->load->view('courses/students/my-courses', $data, true);
        $this->load->view('template2022', $var);
    }

    public function mentorCourses()
    {
        $user_id = $this->session->userdata('user_id');
        $data['title'] = 'Mentor Courses';
        $data['courses'] = $this->common_model->select_courses_where_ASC_DESC(
            'courses.*, categories.category_name, users.username',
            'courses',
            array('courses.is_published' => 1,'courses.created_by' => $user_id),
            'courses.course_id',
            'DESC'
        );

        // Add stock images to courses without thumbnails
        $this->load->helper('stock_image');
        foreach ($data['courses'] as $course) {
            if (empty($course->thumbnail_image)) {
                $thumbnailUrl = get_pexels_image('blockchain');
                if ($thumbnailUrl) {
                    $this->common_model->update_array(
                        array('course_id' => $course->course_id),
                        'courses',
                        array('thumbnail_image' => $thumbnailUrl)
                    );
                }
            }
        }

        $data['page_name'] = 'Mentor Courses';
        $var['content'] = $this->load->view('courses/mentors/mentor-courses', $data, true);
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

            // Add stock image if course doesn't have a thumbnail
            if (empty($data['course']->thumbnail_image)) {
                $this->load->helper('stock_image');
                $thumbnailUrl = get_pexels_image('blockchain');
                if ($thumbnailUrl) {
                    $this->common_model->update_array(
                        array('course_id' => $data['course']->course_id),
                        'courses',
                        array('thumbnail_image' => $thumbnailUrl)
                    );
                    $data['course']->thumbnail_image = $thumbnailUrl;
                }
            }

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
            'category_id, category_name',
            'categories',
            array('isDeleted' => 0),
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


    public function storeCourse()
    {
        // Set JSON response header
        header('Content-Type: application/json');
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('course_title', 'Course Title', 'required');
        $this->form_validation->set_rules('category_id', 'Category', 'required');
        $this->form_validation->set_rules('course_level', 'Course Level', 'required');
        $this->form_validation->set_rules('timeline', 'Course Timeline', 'required');
        $this->form_validation->set_rules('description', 'Course Description', 'required');

        if ($this->form_validation->run() === TRUE) {
            $thumbnailUrl = null;
            
            // Handle file upload if present
            if (isset($_FILES['thumbnail_image']) && $_FILES['thumbnail_image']['size'] > 0) {
                $config['upload_path'] = './uploads/courses/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 5120; // 5MB
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('thumbnail_image')) {
                    echo json_encode([
                        'success' => false,
                        'message' => $this->upload->display_errors()
                    ]);
                    return;
                }

                $uploadData = $this->upload->data();
                $thumbnailUrl = 'uploads/courses/' . $uploadData['file_name'];
            } else {
                // Use Pexels API to fetch a stock image if no file is uploaded
                $this->load->helper('stock_image');
                $thumbnailUrl = get_pexels_image('blockchain');
                if (!$thumbnailUrl) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to fetch stock image.'
                    ]);
                    return;
                }
            }

            // Prepare course data
            $courseData = array(
                'course_title' => $this->input->post('course_title'),
                'category_id' => $this->input->post('category_id'),
                'course_level' => $this->input->post('course_level'),
                'timeline' => $this->input->post('timeline'),
                'description' => $this->input->post('description'),
                'thumbnail_image' => $thumbnailUrl,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s'),
                'is_published' => $this->input->post('is_draft') ? 0 : 1,
                'isDeleted' => 0
            );

            // Insert course into database
            $course_id = $this->common_model->insert_array('courses', $courseData);

            if ($course_id) {
                echo json_encode([
                    'success' => true,
                    'course_id' => $course_id,
                    'message' => 'Course created successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to create course'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
        }
    }
    public function uploadVideos()
    {
        $this->load->helper('supabase');
        $uploadedFiles = [];
        $lesson_id = $this->input->post('lesson_id');

        if (!isset($_FILES['videos']) || empty($_FILES['videos']['name'])) {
            echo json_encode(['error' => 'No files uploaded']);
            return;
        }

        foreach ($_FILES['videos']['name'] as $key => $value) {
            $tmpName = $_FILES['videos']['tmp_name'][$key];
            $fileName = time() . '_' . $value;
            $uploadResult = supabase_upload_file('lesson-videos', $fileName, $tmpName);
            if ($uploadResult['status'] === 200 || $uploadResult['status'] === 201) {
                // Optionally, get video details using getID3 if needed
                $videoUrl = $uploadResult['url'];
                $post = [
                    'lesson_id' => $lesson_id,
                    'file_size' => $_FILES['videos']['size'][$key],
                    'file_path' => $videoUrl,
                ];
                $upload_id = $this->common_model->insert_array('uploads', $post);
                $uploadedFiles[] = [
                    'upload_id' => $upload_id,
                    'name' => $fileName,
                    'size' => $_FILES['videos']['size'][$key],
                    'path' => $videoUrl,
                    'thumbnail' => $videoUrl,
                    'description' => 'Uploaded successfully'
                ];
            } else {
                echo json_encode(['error' => 'Video upload failed: ' . json_encode($uploadResult['response'])]);
                return;
            }
        }
        echo json_encode(['uploadedFiles' => $uploadedFiles]);
    }
    public function uploadResources()
    {
        $this->load->helper('supabase');
        $resUploadedFiles = [];
        $course_id = $this->input->post('course_id');

        if (!isset($_FILES['res_files']) || empty($_FILES['res_files']['name'])) {
            echo json_encode(['error' => 'No files uploaded']);
            return;
        }

        foreach ($_FILES['res_files']['name'] as $key => $value) {
            $tmpName = $_FILES['res_files']['tmp_name'][$key];
            $fileName = time() . '_' . $value;
            $uploadResult = supabase_upload_file('resources', $fileName, $tmpName);
            if ($uploadResult['status'] === 200 || $uploadResult['status'] === 201) {
                $resourceUrl = $uploadResult['url'];
                $post = [
                    'course_id' => $course_id,
                    'file_size' => $_FILES['res_files']['size'][$key],
                    'path' => $resourceUrl,
                ];
                $upload_id = $this->common_model->insert_array('videos', $post); // Change table name if needed
                $resUploadedFiles[] = [
                    '_id' => $upload_id,
                    'name' => $fileName,
                    'size' => $_FILES['res_files']['size'][$key],
                    'path' => $resourceUrl,
                    'thumbnail' => $resourceUrl,
                    'description' => 'Uploaded successfully'
                ];
            } else {
                echo json_encode(['error' => 'Resource upload failed: ' . json_encode($uploadResult['response'])]);
                return;
            }
        }
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
        $resourceId = $this->input->post('resource_id');
        $resource   = $this->common_model->select_where_return_row('*', 'resources', array('resource_id' => $resourceId));

        // Construct the absolute server path for the file
        $filePath = FCPATH . 'uploads/resources/' . $resource->path;

        if ($resource && file_exists($filePath)) {
            unlink($filePath); // Delete the file from the server
            $this->common_model->delete_where(array('resource_id' => $resourceId), 'resources');

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

    public function publishCourse($course_id)
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        
        // Check if user has permission to publish this course
        $course = $this->Course_model->get_course_by_id($course_id);
        
        if (!$course || ($user->role_id != 1 && $course->created_by != $user_id)) {
            $response = array(
                'success' => false,
                'message' => 'Unauthorized to publish this course'
            );
        } else {
            // Update course status
            $updateData = array(
                'is_published' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            );
            
            $this->common_model->update_array('courses', $updateData, array('course_id' => $course_id));
            
            $response = array(
                'success' => true,
                'message' => 'Course published successfully'
            );
        }
        
        echo json_encode($response);
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

    public function getMentorCourses()
    {
        try {
            $user_id = $this->session->userdata('user_id');
            if (!$user_id) {
                throw new Exception('User not authenticated');
            }

            $user = $this->User_model->get_user_by_id($user_id);
            if (!$user) {
                throw new Exception('User not found');
            }
            
            // If super admin, show all courses. If teacher, show only their courses
            if ($user->role_id == 1) {
                $courses = $this->common_model->select_where_ASC_DESC('*', 'courses', array('isDeleted' => 0), 'course_id', 'DESC');
            } else {
                $courses = $this->common_model->select_where_ASC_DESC('*', 'courses', array('isDeleted' => 0, 'created_by' => $user_id), 'course_id', 'DESC');
            }

            if (!$courses) {
                $courses = array(); // Return empty array if no courses found
            }

            $formatted_courses = array_map(function($course) use ($user_id) {
                // Get course progress
                $progress = $this->getCourseProgress($course->course_id, $user_id);
                
                // Get course rating
                $rating = $this->getCourseRating($course->course_id);
                
                // Get creator info
                $creator = $this->db->where('user_id', $course->created_by)->get('users')->row();
                
                // Get category info
                $category = $this->db->where('category_id', $course->category_id)->get('categories')->row();

                // Ensure thumbnail URL is properly formatted
                $thumbnail = $course->thumbnail_image;
                if (!empty($thumbnail)) {
                    if (strpos($thumbnail, 'http') === 0) {
                        // Already a full URL
                        $thumbnail_url = $thumbnail;
                    } else {
                        // Relative path, prepend base_url
                        $thumbnail_url = base_url('uploads/courses/' . $thumbnail);
                    }
                } else {
                    $thumbnail_url = base_url('assets/images/thumbs/course-img1.png');
                }

                return [
                    'id' => $course->course_id,
                    'title' => $course->course_title,
                    'description' => $course->description,
                    'category' => $category ? $category->category_name : 'Uncategorized',
                    'thumbnail' => $thumbnail_url,
                    'creator' => $creator ? $creator->username : 'Unknown',
                    'creator_avatar' => $creator && $creator->profile_image ? 
                        (strpos($creator->profile_image, 'http') === 0 ? 
                            $creator->profile_image : 
                            base_url('uploads/profiles/' . $creator->profile_image)
                        ) : 
                        base_url('assets/images/thumbs/user-img.png'),
                    'progress' => $progress,
                    'rating' => [
                        'score' => round($rating->score ?? 0, 1),
                        'count' => $rating->count ?? 0
                    ],
                    'is_published' => $course->is_published,
                    'url' => base_url('course-details/' . $course->course_id)
                ];
            }, $courses);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'data' => $formatted_courses
                ]));

        } catch (Exception $e) {
            log_message('error', 'Error in getMentorCourses: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'An error occurred while fetching courses: ' . $e->getMessage()
                ]));
        }
    }

    private function getCourseProgress($course_id, $user_id) 
    {
        // Get total lessons
        $total_lessons = $this->db
            ->where('course_id', $course_id)
            ->where('isDeleted', 0)
            ->count_all_results('lessons');

        if ($total_lessons === 0) {
            return 0;
        }

        // Get completed lessons
        $completed_lessons = $this->db
            ->where('course_id', $course_id)
            ->where('user_id', $user_id)
            ->where('status', 'completed')
            ->count_all_results('lesson_progress');

        return round(($completed_lessons / $total_lessons) * 100);
    }

    private function getCourseRating($course_id)
    {
        $rating = $this->db
            ->select_avg('rating')
            ->where('course_id', $course_id)
            ->get('course_ratings')
            ->row();

        return (object)[
            'score' => $rating->rating ?? 0,
            'count' => $this->db->where('course_id', $course_id)->count_all_results('course_ratings')
        ];
    }

    public function getCoursePreview($course_id)
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        
        // Check if user has permission to preview this course
        $course = $this->Course_model->get_course_by_id($course_id);
        
        if (!$course || ($user->role_id != 1 && $course->created_by != $user_id)) {
            $response = array(
                'success' => false,
                'message' => 'Unauthorized to preview this course'
            );
        } else {
            // Get course details
            $category = $this->getCategoryById($course->category_id);
            
            // Get lessons
            $lesson_ids = explode(',', $course->lesson_id);
            $lessons = $this->getLessonsByIds($lesson_ids);
            
            // Get quizzes
            $quizzes = $this->getQuizzesByCourseId($course_id);
            
            $response = array(
                'success' => true,
                'course' => $course,
                'category' => $category,
                'lessons' => $lessons,
                'quizzes' => $quizzes
            );
        }
        
        echo json_encode($response);
    }

    private function getQuizzesByCourseId($course_id)
    {
        $this->db->where('course_id', $course_id);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('quizzes');
        return $query->result();
    }
}
