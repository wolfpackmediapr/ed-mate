<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Security {
    private $ci;
    private $rate_limit = 100; // requests per minute
    private $rate_window = 60; // seconds

    public function __construct() {
        $this->ci = &get_instance();
        $this->ci->load->helper('security');
    }

    /**
     * CSRF Protection
     */
    public function csrf_protection() {
        if ($this->ci->input->method() === 'post') {
            if (!$this->ci->input->post($this->ci->security->get_csrf_token_name()) || 
                $this->ci->input->post($this->ci->security->get_csrf_token_name()) !== $this->ci->security->get_csrf_hash()) {
                show_error('The action you have requested is not allowed.', 403);
            }
        }
    }

    /**
     * XSS Filtering
     */
    public function xss_clean($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->xss_clean($value);
            }
        } else {
            $data = $this->ci->security->xss_clean($data);
        }
        return $data;
    }

    /**
     * Rate Limiting
     */
    public function rate_limit($user_id = null) {
        $ip = $this->ci->input->ip_address();
        $key = 'rate_limit:' . ($user_id ? $user_id : $ip);
        
        $current = $this->ci->cache->file->get($key);
        if (!$current) {
            $current = ['count' => 0, 'reset' => time() + $this->rate_window];
        }

        if (time() > $current['reset']) {
            $current = ['count' => 0, 'reset' => time() + $this->rate_window];
        }

        $current['count']++;
        $this->ci->cache->file->save($key, $current, $this->rate_window);

        if ($current['count'] > $this->rate_limit) {
            show_error('Rate limit exceeded. Please try again later.', 429);
        }
    }

    /**
     * Input Validation
     */
    public function validate_input($data, $rules) {
        $this->ci->load->library('form_validation');
        $this->ci->form_validation->set_data($data);
        $this->ci->form_validation->set_rules($rules);
        
        return $this->ci->form_validation->run();
    }

    /**
     * Sanitize Output
     */
    public function sanitize_output($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize_output($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
} 