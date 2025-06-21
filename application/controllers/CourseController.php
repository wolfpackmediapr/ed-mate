<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CourseController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Course_model');
        $this->load->model('Lesson_model');
        $this->load->model('Quiz_model');
        $this->load->helper('supabase');
        
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    /**
     * Show course creation form
     */
    public function create() {
        $data = [
            'page_title' => 'Create Course',
            'page_css' => ['create-course.css'],
            'page_js' => ['create-course.js']
        ];
        
        $this->load->view('template2022', [
            'content' => $this->load->view('create_course', $data, true)
        ]);
    }

    /**
     * Handle course details submission (Step 1)
     */
    public function save_course_details() {
        try {
            $user_id = $this->session->userdata('user_id');
            
            // Validate input
            $this->load->library('form_validation');
            $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
            $this->form_validation->set_rules('category', 'Category', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');

            if ($this->form_validation->run() === FALSE) {
                throw new Exception(validation_errors());
            }

            // Handle thumbnail upload if present
            $thumbnail_url = null;
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['size'] > 0) {
                $upload_result = supabase_upload_file(
                    'course-thumbnails',
                    $_FILES['thumbnail']['name'],
                    $_FILES['thumbnail']['tmp_name']
                );
                
                if ($upload_result['status'] === 200 || $upload_result['status'] === 201) {
                    $thumbnail_url = $upload_result['url'];
                } else {
                    throw new Exception('Failed to upload thumbnail');
                }
            }

            // Prepare course data
            $course_data = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'category_id' => $this->input->post('category'),
                'thumbnail_url' => $thumbnail_url,
                'created_by' => $user_id
            ];

            // Save course
            $result = $this->Course_model->create_course($course_data);
            
            if (!$result['success']) {
                throw new Exception($result['error']);
            }

            echo json_encode([
                'success' => true,
                'course_id' => $result['course_id'],
                'message' => 'Course details saved successfully'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle lessons submission (Step 2)
     */
    public function save_lessons() {
        try {
            $course_id = $this->input->post('course_id');
            $user_id = $this->session->userdata('user_id');
            
            // Verify course ownership
            $course = $this->Course_model->get_course($course_id);
            if (!$course['success'] || $course['data']['created_by'] !== $user_id) {
                throw new Exception('Unauthorized to modify this course');
            }

            $lessons = json_decode($this->input->post('lessons'), true);
            if (!$lessons) {
                throw new Exception('No lessons provided');
            }

            $saved_lessons = [];
            foreach ($lessons as $lesson) {
                // Handle video upload if present
                $video_url = null;
                if (isset($_FILES["video_{$lesson['temp_id']}"]) && $_FILES["video_{$lesson['temp_id']}"]['size'] > 0) {
                    $upload_result = supabase_upload_file(
                        'lesson-videos',
                        $_FILES["video_{$lesson['temp_id']}"]['name'],
                        $_FILES["video_{$lesson['temp_id']}"]['tmp_name']
                    );
                    
                    if ($upload_result['status'] === 200 || $upload_result['status'] === 201) {
                        $video_url = $upload_result['url'];
                    } else {
                        throw new Exception("Failed to upload video for lesson: {$lesson['title']}");
                    }
                }

                // Save lesson
                $lesson_data = [
                    'course_id' => $course_id,
                    'title' => $lesson['title'],
                    'content' => $lesson['content'],
                    'video_url' => $video_url,
                    'order_index' => $lesson['order_index'] ?? 0
                ];

                $result = $this->Lesson_model->create_lesson($lesson_data);
                if (!$result['success']) {
                    throw new Exception("Failed to save lesson: {$lesson['title']}");
                }

                // Handle resources if present
                if (isset($lesson['resources']) && !empty($lesson['resources'])) {
                    foreach ($lesson['resources'] as $resource) {
                        if (isset($_FILES["resource_{$resource['temp_id']}"]) && $_FILES["resource_{$resource['temp_id']}"]['size'] > 0) {
                            $upload_result = supabase_upload_file(
                                'resources',
                                $_FILES["resource_{$resource['temp_id']}"]['name'],
                                $_FILES["resource_{$resource['temp_id']}"]['tmp_name']
                            );
                            
                            if ($upload_result['status'] === 200 || $upload_result['status'] === 201) {
                                $resource_data = [
                                    'title' => $resource['title'],
                                    'file_url' => $upload_result['url'],
                                    'file_type' => pathinfo($_FILES["resource_{$resource['temp_id']}"]['name'], PATHINFO_EXTENSION),
                                    'file_size' => $_FILES["resource_{$resource['temp_id']}"]['size']
                                ];
                                
                                $this->Lesson_model->add_resource($result['lesson_id'], $resource_data);
                            }
                        }
                    }
                }

                $saved_lessons[] = $result['lesson_id'];
            }

            echo json_encode([
                'success' => true,
                'lesson_ids' => $saved_lessons,
                'message' => 'Lessons saved successfully'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle quiz submission (Step 3)
     */
    public function save_quizzes() {
        try {
            $course_id = $this->input->post('course_id');
            $user_id = $this->session->userdata('user_id');
            
            // Verify course ownership
            $course = $this->Course_model->get_course($course_id);
            if (!$course['success'] || $course['data']['created_by'] !== $user_id) {
                throw new Exception('Unauthorized to modify this course');
            }

            $quizzes = json_decode($this->input->post('quizzes'), true);
            if (!$quizzes) {
                throw new Exception('No quizzes provided');
            }

            $saved_quizzes = [];
            foreach ($quizzes as $quiz) {
                // Save quiz
                $quiz_data = [
                    'course_id' => $course_id,
                    'title' => $quiz['title'],
                    'description' => $quiz['description'],
                    'passing_score' => $quiz['passing_score'] ?? 70
                ];

                $result = $this->Quiz_model->create_quiz($quiz_data);
                if (!$result['success']) {
                    throw new Exception("Failed to save quiz: {$quiz['title']}");
                }

                // Save questions
                if (isset($quiz['questions']) && !empty($quiz['questions'])) {
                    foreach ($quiz['questions'] as $question) {
                        $question_data = [
                            'question_text' => $question['text'],
                            'question_type' => $question['type'],
                            'options' => $question['options'],
                            'correct_answer' => $question['correct_answer'],
                            'points' => $question['points'] ?? 1
                        ];
                        
                        $this->Quiz_model->add_question($result['quiz_id'], $question_data);
                    }
                }

                $saved_quizzes[] = $result['quiz_id'];
            }

            echo json_encode([
                'success' => true,
                'quiz_ids' => $saved_quizzes,
                'message' => 'Quizzes saved successfully'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle course publishing (Step 4)
     */
    public function publish_course() {
        try {
            $course_id = $this->input->post('course_id');
            $user_id = $this->session->userdata('user_id');
            
            $result = $this->Course_model->publish_course($course_id, $user_id);
            
            if (!$result['success']) {
                throw new Exception($result['error']);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Course published successfully'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Save course as draft
     */
    public function save_draft() {
        try {
            $course_id = $this->input->post('course_id');
            $user_id = $this->session->userdata('user_id');
            
            $data = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'category_id' => $this->input->post('category')
            ];

            $result = $this->Course_model->save_draft($course_id, $user_id, $data);
            
            if (!$result['success']) {
                throw new Exception($result['error']);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Course saved as draft'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get course preview data
     */
    public function get_preview_data($course_id) {
        try {
            $user_id = $this->session->userdata('user_id');
            
            // Get course details
            $course = $this->Course_model->get_course($course_id);
            if (!$course['success'] || $course['data']['created_by'] !== $user_id) {
                throw new Exception('Unauthorized to view this course');
            }

            // Get lessons
            $lessons = $this->Lesson_model->get_course_lessons($course_id);
            
            // Get quizzes
            $quizzes = $this->Quiz_model->get_course_quizzes($course_id);

            echo json_encode([
                'success' => true,
                'data' => [
                    'course' => $course['data'],
                    'lessons' => $lessons['success'] ? $lessons['data'] : [],
                    'quizzes' => $quizzes['success'] ? $quizzes['data'] : []
                ]
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
} 