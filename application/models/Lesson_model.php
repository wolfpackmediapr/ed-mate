<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lesson_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('supabase');
    }

    /**
     * Create a new lesson
     */
    public function create_lesson($data)
    {
        try {
            // Ensure required fields
            if (!isset($data['course_id']) || !isset($data['title'])) {
                throw new Exception('Missing required fields');
            }

            // Prepare lesson data
            $lesson_data = [
                'course_id' => $data['course_id'],
                'title' => $data['title'],
                'content' => $data['content'] ?? null,
                'video_url' => $data['video_url'] ?? null,
                'order_index' => $data['order_index'] ?? 0,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insert into Supabase
            $result = supabase_insert('lessons', $lesson_data);
            
            if ($result['status'] === 201 && !empty($result['data'][0]['id'])) {
                return [
                    'success' => true,
                    'lesson_id' => $result['data'][0]['id']
                ];
            } else {
                throw new Exception('Failed to create lesson: ' . json_encode($result['data']));
            }
        } catch (Exception $e) {
            log_message('error', 'Lesson creation error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Update an existing lesson
     */
    public function update_lesson($lesson_id, $data)
    {
        try {
            // Remove any fields that shouldn't be updated
            unset($data['id'], $data['created_at'], $data['course_id']);
            $data['updated_at'] = date('Y-m-d H:i:s');

            $result = supabase_update('lessons', $lesson_id, $data);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Lesson update error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get lesson by ID
     */
    public function get_lesson($lesson_id)
    {
        try {
            $result = supabase_query('lessons', ['id' => 'eq.' . $lesson_id]);
            
            if ($result['status'] === 200 && !empty($result['data'])) {
                return [
                    'success' => true,
                    'data' => $result['data'][0]
                ];
            } else {
                throw new Exception('Lesson not found');
            }
        } catch (Exception $e) {
            log_message('error', 'Lesson fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get all lessons for a course
     */
    public function get_course_lessons($course_id)
    {
        try {
            $result = supabase_query('lessons', [
                'course_id' => 'eq.' . $course_id,
                'order' => 'order_index.asc'
            ]);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Course lessons fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete a lesson
     */
    public function delete_lesson($lesson_id)
    {
        try {
            $result = supabase_delete('lessons', $lesson_id);

            return [
                'success' => $result['status'] === 204,
                'message' => 'Lesson deleted successfully'
            ];
        } catch (Exception $e) {
            log_message('error', 'Lesson deletion error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Reorder lessons
     */
    public function reorder_lessons($course_id, $lesson_order)
    {
        try {
            $success = true;
            $errors = [];

            foreach ($lesson_order as $index => $lesson_id) {
                $result = supabase_update('lessons', $lesson_id, [
                    'order_index' => $index,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                if ($result['status'] !== 200) {
                    $success = false;
                    $errors[] = "Failed to update lesson order for lesson ID: $lesson_id";
                }
            }

            return [
                'success' => $success,
                'errors' => $errors
            ];
        } catch (Exception $e) {
            log_message('error', 'Lesson reorder error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Add resource to lesson
     */
    public function add_resource($lesson_id, $resource_data)
    {
        try {
            // Ensure required fields
            if (!isset($resource_data['title']) || !isset($resource_data['file_url'])) {
                throw new Exception('Missing required resource fields');
            }

            $resource = [
                'lesson_id' => $lesson_id,
                'title' => $resource_data['title'],
                'file_url' => $resource_data['file_url'],
                'file_type' => $resource_data['file_type'] ?? null,
                'file_size' => $resource_data['file_size'] ?? null,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $result = supabase_insert('resources', $resource);

            return [
                'success' => $result['status'] === 201,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Resource addition error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get lesson resources
     */
    public function get_lesson_resources($lesson_id)
    {
        try {
            $result = supabase_query('resources', ['lesson_id' => 'eq.' . $lesson_id]);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Lesson resources fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get all resources (optionally with lesson and course info)
     */
    public function get_all_resources()
    {
        try {
            // Fetch all resources with lesson and course info using Supabase
            $result = supabase_query('resources');
            if ($result['status'] === 200 && is_array($result['data'])) {
                return [
                    'success' => true,
                    'data' => $result['data']
                ];
            } else {
                throw new Exception('Failed to fetch resources');
            }
        } catch (Exception $e) {
            log_message('error', 'All resources fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
} 