<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('supabase');
    }

    /**
     * Get all messages for a user
     */
    public function get_user_messages($user_id)
    {
        try {
            $result = supabase_query('messages', [
                'or' => [
                    'sender_id.eq.' . $user_id,
                    'receiver_id.eq.' . $user_id
                ],
                'order' => 'created_at.desc'
            ]);
            
            return [
                'success' => $result['status'] === 200,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Messages fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send a new message
     */
    public function send_message($data)
    {
        try {
            // Ensure required fields
            if (!isset($data['sender_id']) || !isset($data['receiver_id']) || !isset($data['content'])) {
                throw new Exception('Missing required message fields');
            }

            $message_data = [
                'sender_id' => $data['sender_id'],
                'receiver_id' => $data['receiver_id'],
                'content' => $data['content'],
                'created_at' => date('Y-m-d H:i:s'),
                'is_read' => false
            ];

            $result = supabase_insert('messages', $message_data);
            
            return [
                'success' => $result['status'] === 201,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            log_message('error', 'Message send error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Mark messages as read
     */
    public function mark_as_read($message_ids)
    {
        try {
            $success = true;
            $errors = [];

            foreach ($message_ids as $message_id) {
                $result = supabase_update('messages', $message_id, [
                    'is_read' => true,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                if ($result['status'] !== 200) {
                    $success = false;
                    $errors[] = "Failed to mark message as read: $message_id";
                }
            }

            return [
                'success' => $success,
                'errors' => $errors
            ];
        } catch (Exception $e) {
            log_message('error', 'Mark messages as read error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get unread message count for a user
     */
    public function get_unread_count($user_id)
    {
        try {
            $result = supabase_query('messages', [
                'receiver_id' => 'eq.' . $user_id,
                'is_read' => 'eq.false'
            ]);
            
            return [
                'success' => $result['status'] === 200,
                'count' => count($result['data'])
            ];
        } catch (Exception $e) {
            log_message('error', 'Unread count fetch error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
} 