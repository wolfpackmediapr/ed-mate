<?php
if (!function_exists('supabaseSignIn')) {
    function supabaseSignIn($email, $password) {
        $CI =& get_instance();
        $CI->load->config('supabase');
        
        $supabaseUrl = $CI->config->item('supabase_url');
        $supabaseKey = $CI->config->item('supabase_key');
        
        $ch = curl_init($supabaseUrl . '/auth/v1/token?grant_type=password');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'email' => $email,
            'password' => $password
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $supabaseKey,
            'Content-Type: application/json'
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $result = json_decode($response, true);
        if ($httpCode !== 200) {
            return ['error' => $result['error_description'] ?? $result['error'] ?? 'Authentication failed'];
        }
        return $result;
    }
} 