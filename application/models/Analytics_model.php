<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Analytics_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('supabase');
    }

    /**
     * Get course enrollment statistics
     */
    public function get_course_enrollments()
    {
        try {
            $result = supabase_query('course_pricing', [
                'select' => 'course_id,count',
                'is_paid' => 'eq.true'
            ]);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Course enrollments fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get revenue statistics
     */
    public function get_revenue_stats()
    {
        try {
            $result = supabase_query('payments', [
                'select' => 'amount,created_at',
                'status' => 'eq.completed'
            ]);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Revenue stats fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get user growth statistics
     */
    public function get_user_growth()
    {
        try {
            $result = supabase_query('users', [
                'select' => 'created_at',
                'order' => 'created_at.asc'
            ]);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'User growth fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get course completion rates
     */
    public function get_course_completion_rates()
    {
        try {
            $result = supabase_query('course_progress', [
                'select' => 'course_id,completed_lessons,total_lessons'
            ]);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Course completion rates fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get active users in last 30 days
     */
    public function get_active_users()
    {
        try {
            $thirty_days_ago = date('Y-m-d H:i:s', strtotime('-30 days'));
            $result = supabase_query('user_activity', [
                'select' => 'user_id,last_active',
                'last_active' => 'gte.' . $thirty_days_ago
            ]);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Active users fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
} 