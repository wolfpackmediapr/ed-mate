<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('supabase');
    }

    /**
     * Create a new course
     */
    public function create_course($data)
    {
        try {
            // Ensure required fields
            if (!isset($data['title']) || !isset($data['created_by'])) {
                throw new Exception('Missing required fields');
            }

            // Prepare course data
            $course_data = [
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'] ?? null,
                'thumbnail_url' => $data['thumbnail_url'] ?? null,
                'created_by' => $data['created_by'],
                'status' => 'draft',
                'is_published' => false,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insert into Supabase
            $result = supabase_insert('courses', $course_data);
            
            if ($result['status'] === 201 && !empty($result['data'][0]['id'])) {
                return [
                    'success' => true,
                    'course_id' => $result['data'][0]['id']
                ];
            } else {
                throw new Exception('Failed to create course: ' . json_encode($result['data']));
            }
        } catch (Exception $e) {
            log_message('error', 'Course creation error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Update an existing course
     */
    public function update_course($course_id, $data)
    {
        try {
            // Remove any fields that shouldn't be updated
            unset($data['id'], $data['created_at'], $data['created_by']);
            $data['updated_at'] = date('Y-m-d H:i:s');

            $result = supabase_update('courses', $course_id, $data);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Course update error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get course by ID
     */
    public function get_course($course_id)
    {
        try {
            $result = supabase_query('courses', ['id' => 'eq.' . $course_id]);
            
            if ($result['status'] === 200 && !empty($result['data'])) {
                return [
                    'success' => true,
                    'data' => $result['data'][0]
                ];
            } else {
                throw new Exception('Course not found');
            }
        } catch (Exception $e) {
            log_message('error', 'Course fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get all courses for a user
     */
    public function get_user_courses($user_id, $status = null)
    {
        try {
            $query = ['created_by' => 'eq.' . $user_id];
            if ($status) {
                $query['status'] = 'eq.' . $status;
            }
            
            $result = supabase_query('courses', $query);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'User courses fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Publish a course
     */
    public function publish_course($course_id, $user_id)
    {
        try {
            // First verify the user owns the course
            $course = $this->get_course($course_id);
            if (!$course['success'] || $course['data']['created_by'] !== $user_id) {
                throw new Exception('Unauthorized to publish this course');
            }

            // Update course status
            $result = supabase_update('courses', $course_id, [
                'status' => 'published',
                'is_published' => true,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Course publish error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Save course as draft
     */
    public function save_draft($course_id, $user_id, $data)
    {
        try {
            // First verify the user owns the course
            $course = $this->get_course($course_id);
            if (!$course['success'] || $course['data']['created_by'] !== $user_id) {
                throw new Exception('Unauthorized to modify this course');
            }

            // Update course data
            $data['status'] = 'draft';
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            $result = supabase_update('courses', $course_id, $data);

            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Course draft save error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete a course
     */
    public function delete_course($course_id, $user_id)
    {
        try {
            // First verify the user owns the course
            $course = $this->get_course($course_id);
            if (!$course['success'] || $course['data']['created_by'] !== $user_id) {
                throw new Exception('Unauthorized to delete this course');
            }

            $result = supabase_delete('courses', $course_id);

            return [
                'success' => $result['status'] === 204,
                'message' => 'Course deleted successfully'
            ];
        } catch (Exception $e) {
            log_message('error', 'Course deletion error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function get_course_with_details($course_id)
    {
        $this->db->select('courses.*, categories.category_name, users.username as creator_name');
        $this->db->from('courses');
        $this->db->join('categories', 'categories.category_id = courses.category_id');
        $this->db->join('users', 'users.user_id = courses.created_by');
        $this->db->where('courses.course_id', $course_id);
        $this->db->where('courses.isDeleted', 0);
        $query = $this->db->get();
        return $query->row();
    }
} 