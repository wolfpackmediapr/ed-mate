<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BatchController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('download');
    }

    public function index()
    {
        $data['batches'] = $this->common_model->get_batch_details();
        // fetch API will be integrated
        $this->load->view('pages/batch_view', $data);
    }


    public function download_template()
    {
        $file_path = FCPATH . 'assets/template_file.xlsx'; // Path to your template file
        $data = file_get_contents($file_path);
        $name = 'template_file.xlsx'; // Name of the file for download
        force_download($name, $data);
    }
    private function generate_batch_id($length = 12)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $batchId = '';
        for ($i = 0; $i < $length; $i++) {
            $batchId .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $batchId;
    }

    public function upload_file()
    {        
        $timestamp = date('YmdHis'); // Current date and time in YYYYMMDDHHMMSS format
        $batchId = 'batch_' . $timestamp; // Generate a unique, non-decimal batch ID

        // Get additional data from the POST request and session
        $userName = $this->session->userdata('profile_data')['supplier_name'];
        $dateTime = date('Y-m-d H:i:s'); // Current date and time
        $startedOn = $dateTime; // Date and time when the file upload starts
        $completedOn = null; // To be set after successful upload

        // Configure upload settings
        $config['upload_path'] = './uploads/'; // Make sure this directory exists
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = 2048; // Size in KB (2 MB max)
        $config['file_name'] = $batchId . '_' . $_FILES['excelFile']['name']; // Append batch ID to filename

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('excelFile')) {
            // Handle the error
            $error = $this->upload->display_errors();
            echo json_encode([
                'status' => 'error',
                'message' => $error,
                'batch_id' => $batchId,
                'user_name' => $userName,
                'date_time' => $dateTime,
                'started_on' => $startedOn,
                'completed_on' => $completedOn
            ]);
        } else {
            // Handle the success
            $data = $this->upload->data();
            $completedOn = date('Y-m-d H:i:s'); // Current date and time

            // Create a payload with additional data
            $payload = [
                'status' => 'success',
                'message' => 'File uploaded successfully!',
                'batch_id' => $batchId, // Return the unique batch ID
                'file_info' => $data,
                'user_name' => $userName,
                'date_time' => $dateTime,
                'started_on' => $startedOn,
                'completed_on' => $completedOn
            ];

            // Optionally, you can store or process the additional data here

            echo json_encode($payload);
        }
    }


    // public function upload_file()
    // {
    //     $config['upload_path'] = './uploads/';
    //     $config['allowed_types'] = 'xlsx|xls';
    //     $config['max_size'] = 2048; // Max file size in KB

    //     print_r($_FILES);
    //     die;
    //     $this->upload->initialize($config);

    //     if (!$this->upload->do_upload('excelFile')) {
    //         $error = $this->upload->display_errors();
    //         $this->session->set_flashdata('error', $error);
    //         redirect('BatchController');
    //     } else {
    //         $data = $this->upload->data();
    //         $batch_id = generate_batch_id(); // Generate a unique batch ID

    //         // Prepare the file and data for API
    //         $file_path = $data['full_path'];
    //         $file_data = array(
    //             'batch_id' => $batch_id,
    //             'input_file' => $file_path,
    //             // Add other fields if necessary
    //         );

    //         // Send the file and data to the API
    //         $this->send_to_api($file_path, $file_data);

    //         $this->session->set_flashdata('success', 'File uploaded and sent to API successfully.');
    //         redirect('BatchController');
    //     }
    // }

    private function send_to_api($file_path, $file_data)
    {
        $api_url = $this->config->item('api_url');
        // Prepare file upload
        $post_data = array(
            'batch_id' => $file_data['batch_id'],
            'file' => new CURLFile($file_path),
            // Add other fields if necessary
        );

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute request and get response
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            // Handle cURL error
            log_message('error', 'cURL error: ' . curl_error($ch));
        } else {
            // Log response or handle success
            log_message('info', 'API response: ' . $response);
        }

        // Close cURL
        curl_close($ch);
    }
}
