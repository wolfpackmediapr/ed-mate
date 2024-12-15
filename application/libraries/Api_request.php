<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_request
{

    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
        // No need to load a cURL library since we are using native PHP cURL functions
    }

    /**
     * Perform a GET request to a given URL.
     *
     * @param string $url The URL to send the GET request to.
     * @param array $params Optional query parameters.
     * @param string $token Optional authorization token.
     * @return array The response data and status.
     */
    public function get($url, $params = array(), $token = null)
    {
        $ch = curl_init();

        // Append parameters to URL if any
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($token) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
        }

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $this->process_response($response, $http_status);
    }

    /**
     * Perform a POST request to a given URL.
     *
     * @param string $url The URL to send the POST request to.
     * @param array $data The data to post.
     * @param string $token Optional authorization token.
     * @return array The response data and status.
     */
    public function post($url, $data = array(), $token = null)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($token) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
        }

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $this->process_response($response, $http_status);
    }

    /**
     * Process the response from cURL.
     *
     * @param string $response The raw response data from cURL.
     * @param int $http_status The HTTP status code.
     * @return array The processed response data and status.
     */
    private function process_response($response, $http_status)
    {
        if ($http_status >= 200 && $http_status < 300) {
            $responseArray = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return array(
                    'status' => 'success',
                    'data' => $responseArray
                );
            } else {
                return array(
                    'status' => 'error',
                    'data' => $response
                );
            }
        } else {
            return array(
                'status' => 'error',
                'data' => $response
            );
        }
    }
}
