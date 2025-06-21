<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CourseModel extends CI_Model {
    private $table = 'courses';
    private $table_categories = 'categories';
    private $table_lessons = 'lessons';
    private $table_enrollments = 'enrollments';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all courses with optional filters
     */
    public function get_courses($filters = [], $limit = null, $offset = null) {
        $this->db->select('c.*, cat.name as category_name');
        $this->db->from($this->table . ' c');
        $this->db->join($this->table_categories . ' cat', 'cat.id = c.category_id', 'left');

        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if ($value !== null) {
                    $this->db->where($key, $value);
                }
            }
        }

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('c.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Get a single course by ID
     */
    public function get_course($id) {
        $this->db->select('c.*, cat.name as category_name');
        $this->db->from($this->table . ' c');
        $this->db->join($this->table_categories . ' cat', 'cat.id = c.category_id', 'left');
        $this->db->where('c.id', $id);
        return $this->db->get()->row();
    }

    /**
     * Create a new course
     */
    public function create_course($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update an existing course
     */
    public function update_course($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete a course
     */
    public function delete_course($id) {
        // First, delete all related lessons
        $this->db->where('course_id', $id);
        $this->db->delete($this->table_lessons);

        // Then delete the course
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Get course lessons
     */
    public function get_course_lessons($course_id) {
        $this->db->where('course_id', $course_id);
        $this->db->order_by('order', 'ASC');
        return $this->db->get($this->table_lessons)->result();
    }

    /**
     * Get enrolled students for a course
     */
    public function get_enrolled_students($course_id) {
        $this->db->select('u.*, e.enrolled_at, e.status');
        $this->db->from('users u');
        $this->db->join($this->table_enrollments . ' e', 'e.user_id = u.id');
        $this->db->where('e.course_id', $course_id);
        return $this->db->get()->result();
    }

    /**
     * Check if user is enrolled in course
     */
    public function is_enrolled($user_id, $course_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('course_id', $course_id);
        $this->db->where('status', 'active');
        return $this->db->get($this->table_enrollments)->num_rows() > 0;
    }

    /**
     * Enroll user in course
     */
    public function enroll_user($user_id, $course_id) {
        $data = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'status' => 'active',
            'enrolled_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->insert($this->table_enrollments, $data);
    }

    /**
     * Get course progress for a user
     */
    public function get_course_progress($user_id, $course_id) {
        // Get total lessons
        $this->db->where('course_id', $course_id);
        $total_lessons = $this->db->get($this->table_lessons)->num_rows();

        // Get completed lessons
        $this->db->where('user_id', $user_id);
        $this->db->where('course_id', $course_id);
        $this->db->where('status', 'completed');
        $completed_lessons = $this->db->get('lesson_progress')->num_rows();

        return [
            'total_lessons' => $total_lessons,
            'completed_lessons' => $completed_lessons,
            'progress_percentage' => $total_lessons > 0 ? ($completed_lessons / $total_lessons) * 100 : 0
        ];
    }
} 