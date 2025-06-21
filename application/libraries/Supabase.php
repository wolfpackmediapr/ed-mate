<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supabase {
    private $supabase_url;
    private $supabase_key;
    private $ci;

    public function __construct() {
        $this->ci = &get_instance();
        $this->supabase_url = 'https://xutaafaoclbrhhuhtwlm.supabase.co';
        $this->supabase_key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Inh1dGFhZmFvY2xicmhodWh0d2xtIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDc0MzU4NjEsImV4cCI6MjA2MzAxMTg2MX0.QvO37GyfN3LNS3LElkzwlS0sfpfgHNWaI0EYOQ5Mcqw';
    }

    public function request($endpoint, $method = 'GET', $data = null) {
        $url = $this->supabase_url . $endpoint;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $this->supabase_key,
            'Authorization: Bearer ' . $this->supabase_key,
            'Content-Type: application/json'
        ]);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            log_message('error', 'Supabase API Error: ' . curl_error($ch));
            return false;
        }
        curl_close($ch);
        return json_decode($response, true);
    }

    public function get($endpoint) {
        return $this->request($endpoint, 'GET');
    }

    public function post($endpoint, $data) {
        return $this->request($endpoint, 'POST', $data);
    }

    public function put($endpoint, $data) {
        return $this->request($endpoint, 'PUT', $data);
    }

    public function delete($endpoint) {
        return $this->request($endpoint, 'DELETE');
    }
} 