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
        $this->load->helper('supabase');
        $this->load->model('Lesson_model');
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

    public function storeLesson()
    {
        try {
            $course_id = $this->input->post('course_id');
            $lesson_titles = $this->input->post('lesson_title');
            $lesson_descriptions = $this->input->post('lesson_description');
            
            if (!$course_id || !$lesson_titles) {
                throw new Exception('Missing required fields');
            }

            $response = ['status' => 'success', 'message' => 'Lessons created successfully'];
            
            // Process each lesson
            foreach ($lesson_titles as $index => $title) {
                // Create lesson record for Supabase
                $lesson_data = [
                    'course_id' => $course_id,
                    'lesson_title' => $title,
                    'lesson_description' => $lesson_descriptions[$index] ?? '',
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $supabaseLesson = supabase_insert('lessons', [$lesson_data]);
                if ($supabaseLesson['status'] === 201 && !empty($supabaseLesson['data'][0]['id'])) {
                    $lesson_id = $supabaseLesson['data'][0]['id'];
                } else {
                    throw new Exception('Failed to create lesson in Supabase: ' . json_encode($supabaseLesson['data']));
                }

                // Handle video upload
                if (isset($_FILES['lesson_video']['name'][$index]) && $_FILES['lesson_video']['size'][$index] > 0) {
                    $video_file = $_FILES['lesson_video']['tmp_name'][$index];
                    $video_name = time() . '_' . $_FILES['lesson_video']['name'][$index];
                    $upload_result = supabase_upload_file('lesson-videos', $video_name, $video_file);
                    if ($upload_result['status'] === 200 || $upload_result['status'] === 201) {
                        // Save video URL to lesson record in Supabase
                        supabase_update('lessons', $lesson_id, [
                            'video_url' => $upload_result['url']
                        ]);
                    }
                }

                // Handle resource uploads
                if (isset($_FILES['lesson_resources']['name'][$index])) {
                    $resource_files = $_FILES['lesson_resources']['tmp_name'][$index];
                    $resource_names = $_FILES['lesson_resources']['name'][$index];
                    if (is_array($resource_files)) {
                        foreach ($resource_files as $r_index => $resource_file) {
                            if ($resource_file && $_FILES['lesson_resources']['size'][$index][$r_index] > 0) {
                                $resource_name = time() . '_' . $resource_names[$r_index];
                                $upload_result = supabase_upload_file('resources', $resource_name, $resource_file);
                                if ($upload_result['status'] === 200 || $upload_result['status'] === 201) {
                                    // Save resource record in Supabase
                                    supabase_insert('resources', [[
                                        'lesson_id' => $lesson_id,
                                        'resource_name' => $resource_names[$r_index],
                                        'resource_url' => $upload_result['url'],
                                        'resource_type' => pathinfo($resource_names[$r_index], PATHINFO_EXTENSION)
                                    ]]);
                                }
                            }
                        }
                    }
                }
            }

            $thumbnailUrl = null;
            if (isset($_FILES['thumbnail_image']) && $_FILES['thumbnail_image']['size'] > 0) {
                $this->load->helper('supabase');
                $uploadResult = supabase_upload_file(
                    'lesson-thumbnails',
                    $_FILES['thumbnail_image']['name'],
                    $_FILES['thumbnail_image']['tmp_name']
                );
                if ($uploadResult['status'] === 200 || $uploadResult['status'] === 201) {
                    $thumbnailUrl = $uploadResult['url'];
                } else {
                    $response = array(
                        'success' => false,
                        'message' => 'Thumbnail upload failed: ' . json_encode($uploadResult['response'])
                    );
                    echo json_encode($response);
                    return;
                }
            } else {
                // Use Pexels API to fetch a stock image if no file is uploaded
                $this->load->helper('stock_image');
                $thumbnailUrl = get_pexels_image('blockchain');
                if (!$thumbnailUrl) {
                    $response = array(
                        'success' => false,
                        'message' => 'Failed to fetch stock image.'
                    );
                    echo json_encode($response);
                    return;
                }
            }

            echo json_encode($response);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getLessons($courseId)
    {
        try {
            $response = supabase_query('lessons', ['course_id' => 'eq.' . $courseId]);
            
            if ($response['status'] === 200) {
                echo json_encode(['status' => 'success', 'data' => $response['data']]);
            } else {
                throw new Exception('Failed to fetch lessons');
            }
        } catch (Exception $e) {
            log_message('error', 'Error fetching lessons: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error fetching lessons: ' . $e->getMessage()]);
        }
    }

    public function updateLesson($lessonId)
    {
        try {
            $lessonData = [
                'lesson_title' => $this->input->post('lesson_title'),
                'lesson_description' => $this->input->post('lesson_description')
            ];
            
            $response = supabase_update('lessons', $lessonId, $lessonData);
            
            if ($response['status'] === 204) {
                echo json_encode(['status' => 'success', 'message' => 'Lesson updated successfully']);
            } else {
                throw new Exception('Failed to update lesson');
            }
        } catch (Exception $e) {
            log_message('error', 'Error updating lesson: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error updating lesson: ' . $e->getMessage()]);
        }
    }

    public function deleteLesson($lessonId)
    {
        try {
            $response = supabase_delete('lessons', $lessonId);
            
            if ($response['status'] === 204) {
                echo json_encode(['status' => 'success', 'message' => 'Lesson deleted successfully']);
            } else {
                throw new Exception('Failed to delete lesson');
            }
        } catch (Exception $e) {
            log_message('error', 'Error deleting lesson: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error deleting lesson: ' . $e->getMessage()]);
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

    public function getLesson($lessonId)
    {
        try {
            $response = supabase_query('lessons', ['id' => 'eq.' . $lessonId]);
            if ($response['status'] === 200 && !empty($response['data'])) {
                echo json_encode(['status' => 'success', 'data' => $response['data'][0]]);
            } else {
                throw new Exception('Lesson not found');
            }
        } catch (Exception $e) {
            log_message('error', 'Error fetching lesson: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error fetching lesson: ' . $e->getMessage()]);
        }
    }
}
