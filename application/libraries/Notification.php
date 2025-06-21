<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification {
    private $ci;
    private $table = 'notifications';

    public function __construct() {
        $this->ci = &get_instance();
        $this->ci->load->database();
        $this->ci->load->library('email');
    }

    /**
     * Create a new notification
     */
    public function create($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['is_read'] = 0;
        
        $this->ci->db->insert($this->table, $data);
        return $this->ci->db->insert_id();
    }

    /**
     * Send email notification
     */
    public function send_email($to, $subject, $message, $template = 'default') {
        $this->ci->email->from($this->ci->config->item('smtp_user'), $this->ci->config->item('site_name'));
        $this->ci->email->to($to);
        $this->ci->email->subject($subject);
        
        // Load email template
        $template_data = [
            'message' => $message,
            'site_name' => $this->ci->config->item('site_name'),
            'site_url' => $this->ci->config->item('base_url')
        ];
        
        $email_content = $this->ci->load->view('email_templates/' . $template, $template_data, true);
        $this->ci->email->message($email_content);
        
        return $this->ci->email->send();
    }

    /**
     * Get user notifications
     */
    public function get_user_notifications($user_id, $limit = 10, $offset = 0) {
        $this->ci->db->where('user_id', $user_id);
        $this->ci->db->order_by('created_at', 'DESC');
        $this->ci->db->limit($limit, $offset);
        return $this->ci->db->get($this->table)->result();
    }

    /**
     * Mark notification as read
     */
    public function mark_as_read($notification_id) {
        $this->ci->db->where('id', $notification_id);
        return $this->ci->db->update($this->table, ['is_read' => 1]);
    }

    /**
     * Mark all notifications as read
     */
    public function mark_all_as_read($user_id) {
        $this->ci->db->where('user_id', $user_id);
        $this->ci->db->where('is_read', 0);
        return $this->ci->db->update($this->table, ['is_read' => 1]);
    }

    /**
     * Get unread notification count
     */
    public function get_unread_count($user_id) {
        $this->ci->db->where('user_id', $user_id);
        $this->ci->db->where('is_read', 0);
        return $this->ci->db->count_all_results($this->table);
    }

    /**
     * Delete notification
     */
    public function delete($notification_id) {
        $this->ci->db->where('id', $notification_id);
        return $this->ci->db->delete($this->table);
    }

    /**
     * Send course enrollment notification
     */
    public function send_enrollment_notification($user_id, $course_id) {
        $user = $this->ci->db->get_where('users', ['id' => $user_id])->row();
        $course = $this->ci->db->get_where('courses', ['id' => $course_id])->row();
        
        if ($user && $course) {
            // Create notification record
            $notification_data = [
                'user_id' => $user_id,
                'type' => 'enrollment',
                'title' => 'Course Enrollment',
                'message' => "You have been enrolled in the course: {$course->title}",
                'related_id' => $course_id
            ];
            $this->create($notification_data);
            
            // Send email
            $email_subject = "Welcome to {$course->title}";
            $email_message = "Dear {$user->first_name},\n\n";
            $email_message .= "You have been successfully enrolled in the course: {$course->title}.\n";
            $email_message .= "You can start learning now by accessing the course content.\n\n";
            $email_message .= "Best regards,\n";
            $email_message .= $this->ci->config->item('site_name');
            
            $this->send_email($user->email, $email_subject, $email_message, 'enrollment');
        }
    }

    /**
     * Send course completion notification
     */
    public function send_completion_notification($user_id, $course_id) {
        $user = $this->ci->db->get_where('users', ['id' => $user_id])->row();
        $course = $this->ci->db->get_where('courses', ['id' => $course_id])->row();
        
        if ($user && $course) {
            // Create notification record
            $notification_data = [
                'user_id' => $user_id,
                'type' => 'completion',
                'title' => 'Course Completed',
                'message' => "Congratulations! You have completed the course: {$course->title}",
                'related_id' => $course_id
            ];
            $this->create($notification_data);
            
            // Send email
            $email_subject = "Course Completed: {$course->title}";
            $email_message = "Dear {$user->first_name},\n\n";
            $email_message .= "Congratulations! You have successfully completed the course: {$course->title}.\n";
            $email_message .= "You can now download your certificate of completion.\n\n";
            $email_message .= "Best regards,\n";
            $email_message .= $this->ci->config->item('site_name');
            
            $this->send_email($user->email, $email_subject, $email_message, 'completion');
        }
    }
} 