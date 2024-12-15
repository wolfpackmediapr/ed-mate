<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	function __construct()
	{
		// Initialization of class
		parent::__construct();
		$this->load->library('email');
		// $this->load->config();
	}

	function login_api($username, $password)
	{
		$url = 'https://ffu.virventures.com/vendorportal/loginWrapper.php';
		$method = 'POST';

		$data = array(
			'username' => $username,
			'password' => $password,
		);

		$curl = curl_init();

		$headers = array(
			'Content-Type: multipart/form-data',
		);

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => $headers,
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return array('error' => $err);
		} else {
			return json_decode($response, true);
		}
	}
	function profileApi()
	{
		$vendorId = $this->session->userdata('profile_data')['id'];
		$token = $this->session->userdata('token');

		$url = 'https://ffu.virventures.com/vendorportal/profileWrapper.php?vendorId='.$vendorId.'&token='.$token;
		$method = 'GET';

		// $data = array(
		// 	'vendorId' => $vendorId,
		// 	'token' => $token,
		// );

		$curl = curl_init();

		$headers = array(
			'Content-Type: multipart/form-data',
		);

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			// CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => $headers,
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return array('error' => $err);
		} else {
			return json_decode($response, true);
		}
	}
	function orderApi()
	{
		$vendorId = $this->session->userdata('profile_data')['id'];
		$token = $this->session->userdata('token');

		$url = 'https://ffu.virventures.com/vendorportal/orderWrapper.php?vendorId='.$vendorId.'&token='.$token;
		$method = 'GET';
		$curl = curl_init();

		$headers = array(
			'Content-Type: multipart/form-data',
		);

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			// CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => $headers,
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return array('error' => $err);
		} else {
			return json_decode($response, true);
		}
	}


}
