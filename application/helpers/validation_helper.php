<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('validate_email')) {
    /**
     * Validate email format
     */
    function validate_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

if (!function_exists('validate_password')) {
    /**
     * Validate password strength
     */
    function validate_password($password) {
        // At least 8 characters
        if (strlen($password) < 8) {
            return false;
        }
        
        // At least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        
        // At least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        
        // At least one number
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        
        // At least one special character
        if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
            return false;
        }
        
        return true;
    }
}

if (!function_exists('validate_phone')) {
    /**
     * Validate phone number format
     */
    function validate_phone($phone) {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Check if the phone number is between 10 and 15 digits
        return strlen($phone) >= 10 && strlen($phone) <= 15;
    }
}

if (!function_exists('validate_date')) {
    /**
     * Validate date format
     */
    function validate_date($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}

if (!function_exists('validate_file_type')) {
    /**
     * Validate file type
     */
    function validate_file_type($file, $allowed_types) {
        $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        return in_array($file_type, $allowed_types);
    }
}

if (!function_exists('validate_file_size')) {
    /**
     * Validate file size
     */
    function validate_file_size($file, $max_size) {
        return $file['size'] <= $max_size;
    }
}

if (!function_exists('validate_url')) {
    /**
     * Validate URL format
     */
    function validate_url($url) {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}

if (!function_exists('validate_username')) {
    /**
     * Validate username format
     */
    function validate_username($username) {
        // Username should be 3-20 characters long
        if (strlen($username) < 3 || strlen($username) > 20) {
            return false;
        }
        
        // Username should only contain letters, numbers, and underscores
        return preg_match('/^[a-zA-Z0-9_]+$/', $username);
    }
}

if (!function_exists('validate_zipcode')) {
    /**
     * Validate ZIP code format
     */
    function validate_zipcode($zipcode) {
        return preg_match('/^\d{5}(-\d{4})?$/', $zipcode);
    }
}

if (!function_exists('validate_credit_card')) {
    /**
     * Validate credit card number using Luhn algorithm
     */
    function validate_credit_card($number) {
        // Remove any non-digit characters
        $number = preg_replace('/[^0-9]/', '', $number);
        
        // Check if the number is between 13 and 19 digits
        if (strlen($number) < 13 || strlen($number) > 19) {
            return false;
        }
        
        // Luhn algorithm
        $sum = 0;
        $length = strlen($number);
        $parity = $length % 2;
        
        for ($i = 0; $i < $length; $i++) {
            $digit = $number[$i];
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }
        
        return $sum % 10 == 0;
    }
}

if (!function_exists('validate_image_dimensions')) {
    /**
     * Validate image dimensions
     */
    function validate_image_dimensions($file, $min_width, $min_height, $max_width, $max_height) {
        $image_info = getimagesize($file['tmp_name']);
        if ($image_info === false) {
            return false;
        }
        
        $width = $image_info[0];
        $height = $image_info[1];
        
        return $width >= $min_width && $width <= $max_width &&
               $height >= $min_height && $height <= $max_height;
    }
}

if (!function_exists('sanitize_input')) {
    /**
     * Sanitize input data
     */
    function sanitize_input($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = sanitize_input($value);
            }
        } else {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
} 