<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Message_model');
        
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            $this->output->set_status_header(401);
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit;
        }
    }

    /**
     * Get messages between current user and another user
     */
    public function get($user_id) {
        $current_user = $this->session->userdata('user_id');
        
        // Get messages
        $result = $this->Message_model->get_user_messages($current_user);
        
        if ($result['success']) {
            // Filter messages for this conversation
            $messages = array_filter($result['data'], function($message) use ($current_user, $user_id) {
                return ($message['sender_id'] == $current_user && $message['receiver_id'] == $user_id) ||
                       ($message['sender_id'] == $user_id && $message['receiver_id'] == $current_user);
            });
            
            // Mark unread messages as read
            $unread_messages = array_filter($messages, function($message) use ($current_user) {
                return $message['receiver_id'] == $current_user && !$message['is_read'];
            });
            
            if (!empty($unread_messages)) {
                $this->Message_model->mark_as_read(array_column($unread_messages, 'id'));
            }
            
            echo json_encode([
                'success' => true,
                'data' => array_values($messages)
            ]);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['success' => false, 'error' => 'Failed to fetch messages']);
        }
    }

    /**
     * Send a new message
     */
    public function send() {
        $current_user = $this->session->userdata('user_id');
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['receiver_id']) || !isset($data['content'])) {
            $this->output->set_status_header(400);
            echo json_encode(['success' => false, 'error' => 'Missing required fields']);
            return;
        }
        
        $message_data = [
            'sender_id' => $current_user,
            'receiver_id' => $data['receiver_id'],
            'content' => $data['content']
        ];
        
        $result = $this->Message_model->send_message($message_data);
        
        if ($result['success']) {
            echo json_encode(['success' => true, 'data' => $result['data']]);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['success' => false, 'error' => 'Failed to send message']);
        }
    }
} 