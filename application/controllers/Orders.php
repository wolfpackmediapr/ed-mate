<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		admin_auth();
		$this->load->library("pagination");
		require_once './vendor/autoload.php';

		// Adjust path if necessary
	}

	public function leads()
	{
		$data['title'] = 'Lakecounty Portal';
		$data['page_name'] = 'Leads';

		$response = $this->orderApi();

		if (isset($response['data']) && isset($response['data']['order_data'])) {
			// Extract the order data
			$data['orders'] = $response['data']['order_data'];
			$this->session->set_userdata('order-detail', $response['data']['order_data']);
		} else {
			// Handle missing 'data' or 'order_data'
			// $this->output->set_status_header(500)->set_output(json_encode(array('error' => 'Order data not found in response.')));
			redirect(base_url());
			return; // Exit early if there is an error
		}

		$var['content'] = $this->load->view('pages/orders', $data, true);
		$this->load->view('template2022', $var);
	}

}
