<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccountController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        $this->load->helper('supabase');
        $this->load->model('User_model');
    }

    public function index()
    {
        $data['title'] = 'Edumate | Account Settings';
        $data['page_name'] = 'Account Settings';
        
        // Get user data from Supabase
        $user_id = $this->session->userdata('user_id');
        $response = supabase_query('users', ['id' => 'eq.' . $user_id]);
        
        if ($response['status'] === 200 && !empty($response['data'])) {
            $data['user'] = (object)$response['data'][0];
        } else {
            $data['user'] = null;
        }

        $var['content'] = $this->load->view('common/account_settings', $data, true);
        $this->load->view('template2022', $var);
    }

    public function updateProfile()
    {
        try {
            $user_id = $this->session->userdata('user_id');
            $data = [
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'bio' => $this->input->post('bio'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $response = supabase_update('users', $user_id, $data);
            
            if ($response['status'] === 204) {
                echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
            } else {
                throw new Exception('Failed to update profile');
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function changePassword()
    {
        try {
            $user_id = $this->session->userdata('user_id');
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');

            // Verify current password
            $response = supabase_query('users', ['id' => 'eq.' . $user_id]);
            if ($response['status'] !== 200 || empty($response['data'])) {
                throw new Exception('User not found');
            }

            // Update password in Supabase Auth
            $auth_response = supabase_update_user_password($user_id, $new_password);
            if ($auth_response['status'] !== 200) {
                throw new Exception('Failed to update password');
            }

            echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateAvatar()
    {
        try {
            if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('No file uploaded or upload error');
            }

            $user_id = $this->session->userdata('user_id');
            $file = $_FILES['avatar'];
            
            // Upload to Supabase Storage
            $upload_result = supabase_upload_file(
                'avatars',
                $user_id . '_' . time() . '_' . $file['name'],
                $file['tmp_name']
            );

            if ($upload_result['status'] !== 200 && $upload_result['status'] !== 201) {
                throw new Exception('Failed to upload avatar');
            }

            // Update user record with new avatar URL
            $response = supabase_update('users', $user_id, [
                'avatar_url' => $upload_result['url'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($response['status'] !== 204) {
                throw new Exception('Failed to update avatar URL');
            }

            echo json_encode(['success' => true, 'message' => 'Avatar updated successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateNotifications()
    {
        try {
            $user_id = $this->session->userdata('user_id');
            $data = [
                'email_notifications' => $this->input->post('email_notifications') === 'on',
                'course_updates' => $this->input->post('course_updates') === 'on',
                'announcements' => $this->input->post('announcements') === 'on',
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $response = supabase_update('users', $user_id, $data);
            
            if ($response['status'] === 204) {
                echo json_encode(['success' => true, 'message' => 'Notification preferences updated successfully']);
            } else {
                throw new Exception('Failed to update notification preferences');
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
} 