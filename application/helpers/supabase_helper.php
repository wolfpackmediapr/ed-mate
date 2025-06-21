<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

if (!function_exists('supabase_signup')) {
    function supabase_signup($email, $password) {
        $CI =& get_instance();
        $CI->load->config('supabase');
        $client = new Client();
        $url = $CI->config->item('supabase_url') . '/auth/v1/signup';
        $headers = [
            'apikey' => $CI->config->item('supabase_key'),
            'Content-Type' => 'application/json',
        ];
        $body = json_encode(['email' => $email, 'password' => $password]);
        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'body' => $body
            ]);
            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}

if (!function_exists('supabase_signin')) {
    function supabase_signin($email, $password) {
        $CI =& get_instance();
        $CI->load->config('supabase');
        $client = new Client();
        $url = $CI->config->item('supabase_url') . '/auth/v1/token?grant_type=password';
        $headers = [
            'apikey' => $CI->config->item('supabase_key'),
            'Content-Type' => 'application/json',
        ];
        $body = json_encode(['email' => $email, 'password' => $password]);
        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'body' => $body
            ]);
            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}

if (!function_exists('supabase_forgot_password')) {
    function supabase_forgot_password($email) {
        $CI =& get_instance();
        $CI->load->config('supabase');
        $client = new Client();
        $url = $CI->config->item('supabase_url') . '/auth/v1/recover';
        $headers = [
            'apikey' => $CI->config->item('supabase_key'),
            'Content-Type' => 'application/json',
        ];
        $body = json_encode(['email' => $email]);
        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'body' => $body
            ]);
            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}

if (!function_exists('supabase_client')) {
    function supabase_client() {
        $CI =& get_instance();
        $CI->load->config('supabase');
        
        require_once APPPATH . 'third_party/supabase-php/vendor/autoload.php';
        
        $supabase = new \Supabase\Client(
            $CI->config->item('supabase_url'),
            $CI->config->item('supabase_key')
        );
        
        return $supabase;
    }
}

if (!function_exists('supabase_request')) {
    function supabase_request($endpoint, $method = 'GET', $data = null) {
        $CI =& get_instance();
        $CI->load->config('supabase');
        
        $url = $CI->config->item('supabase_url') . '/rest/v1/' . $endpoint;
        $headers = [
            'apikey: ' . $CI->config->item('supabase_key'),
            'Authorization: Bearer ' . $CI->config->item('supabase_key'),
            'Content-Type: application/json',
            'Prefer: return=representation'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'status' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }
}

if (!function_exists('supabase_query')) {
    function supabase_query($table, $query = []) {
        $endpoint = $table;
        if (!empty($query)) {
            $endpoint .= '?' . http_build_query($query);
        }
        return supabase_request($endpoint);
    }
}

if (!function_exists('supabase_insert')) {
    function supabase_insert($table, $data) {
        return supabase_request($table, 'POST', $data);
    }
}

if (!function_exists('supabase_update')) {
    function supabase_update($table, $id, $data) {
        $endpoint = $table . '?id=eq.' . $id;
        return supabase_request($endpoint, 'PATCH', $data);
    }
}

if (!function_exists('supabase_delete')) {
    function supabase_delete($table, $id) {
        $endpoint = $table . '?id=eq.' . $id;
        return supabase_request($endpoint, 'DELETE');
    }
}

if (!function_exists('supabase_upload_file')) {
    function supabase_upload_file($bucket, $filePath, $fileTmpName, $supabaseFileName = null) {
        $CI =& get_instance();
        $CI->load->config('supabase');
        
        // Get Supabase credentials
        $supabaseUrl = $CI->config->item('supabase_url');
        $supabaseKey = $CI->config->item('supabase_key');

        // Generate a unique filename if not provided
        if (!$supabaseFileName) {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $supabaseFileName = uniqid() . '_' . time() . '.' . $extension;
        }

        // Clean the filename to be URL-safe
        $supabaseFileName = preg_replace('/[^a-zA-Z0-9._-]/', '', $supabaseFileName);

        // Build the upload URL
        $url = $supabaseUrl . "/storage/v1/object/$bucket/$supabaseFileName";

        try {
            // Read file contents
            if (!file_exists($fileTmpName)) {
                throw new Exception("File not found: $fileTmpName");
            }
            
            $fileData = file_get_contents($fileTmpName);
            if ($fileData === false) {
                throw new Exception("Failed to read file: $fileTmpName");
            }

            // Set up cURL
            $ch = curl_init($url);
            if ($ch === false) {
                throw new Exception("Failed to initialize cURL");
            }

            // Set cURL options
            curl_setopt_array($ch, [
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => $fileData,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer $supabaseKey",
                    "apikey: $supabaseKey",
                    "Content-Type: application/octet-stream",
                    "x-upsert: true" // Handle duplicate filenames
                ]
            ]);

            // Execute the request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            // Check for cURL errors
            if (curl_errno($ch)) {
                throw new Exception("cURL Error: " . curl_error($ch));
            }

            curl_close($ch);

            // Log the response for debugging
            log_message('debug', "Supabase Upload Response: $response");
            log_message('debug', "Supabase Upload HTTP Code: $httpCode");

            // Check if upload was successful
            if ($httpCode !== 200 && $httpCode !== 201) {
                throw new Exception("Upload failed with HTTP code $httpCode: $response");
            }

            // Return success response
            return [
                'status' => $httpCode,
                'response' => json_decode($response, true),
                'url' => $supabaseUrl . "/storage/v1/object/public/$bucket/$supabaseFileName"
            ];

        } catch (Exception $e) {
            log_message('error', "Supabase Upload Error: " . $e->getMessage());
            return [
                'status' => 500,
                'error' => $e->getMessage(),
                'response' => null,
                'url' => null
            ];
        }
    }
}

if (!function_exists('supabase_update_user_password')) {
    function supabase_update_user_password($user_id, $new_password) {
        $CI =& get_instance();
        $CI->load->config('supabase');
        
        $supabaseUrl = $CI->config->item('supabase_url');
        $supabaseKey = $CI->config->item('supabase_key');
        
        $ch = curl_init($supabaseUrl . '/auth/v1/admin/users/' . $user_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'password' => $new_password
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $supabaseKey,
            'Authorization: Bearer ' . $supabaseKey,
            'Content-Type: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'status' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }
} 