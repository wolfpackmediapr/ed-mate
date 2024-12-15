<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends MY_Controller
{


	function __construct()
	{
		parent::__construct();
		admin_auth();
		$this->load->library("pagination");
	}


	public function index()
	{
		$data['title'] = 'Leads Portal';
		$data['page_name'] = 'Leads';

		$data['leads'] = $this->common_model->select_where_ASC_DESC('*', 'leads', array(), 'lead_id', 'DESC');
		$var['content'] = $this->load->view('pages/index', $data, true);
		$this->load->view('template2022', $var);
	}

	public function get_leads()
	{
		$this->load->model('common_model');

		// Fetch filter values
		$user_email = $this->input->post('user_email');
		$user_name = $this->input->post('user_name');
		$source = $this->input->post('source');
		$searchByFromdate = $this->input->post('searchByFromdate');
		$searchByTodate = $this->input->post('searchByTodate');
		// echo $searchByFromdate;
		// die;

		// Fetch parameters from DataTables
		$draw = $this->input->post('draw');
		$start = $this->input->post('start');
		$length = $this->input->post('length');
		$orderColumn = $this->input->post('order')[0]['column'];
		$orderDir = $this->input->post('order')[0]['dir'];
		$searchValue = $this->input->post('search')['value'];

		$conditions = array();


		// Count total records without filtering
		$totalRecords = $this->common_model->count_all('leads');

		// Count filtered records based on search and additional filters
		$recordsFiltered = $this->common_model->count_filtered('leads', $searchValue, $user_email, $user_name, $source, $searchByFromdate, $searchByTodate, $conditions);

		// Fetch records with filtering and ordering
		$leads = $this->common_model->fetch_leads('leads', $searchValue, $start, $length, $orderColumn, $orderDir, $user_email, $user_name, $source, $searchByFromdate, $searchByTodate, $conditions);

		$data = [];
		foreach ($leads as $lead) {
			$data[] = [
				"lead_id" => 'LC-' . $lead->lead_id,
				"client_info" => '<div class="media"><div class="media-body"><p class="mb-0 template-inverse">' . $lead->user_name . '</p><p class="text-template-primary-light">' . $lead->user_phone . '</p><p class="text-template-primary-light">' . $lead->user_email . '</p></div></div>',
				"preferred_date" => $lead->preferred_date,
				"preferred_time" => $lead->preferred_time,
				"createdAt" => $lead->createdAt,
				"source" => $lead->source,
				"comment" => '<p style="font-size: smaller;">' . $lead->comment . '</p>',
				"disposition" => '<select class="form-control lead-status" onchange="changeLeadStatus(this, ' . $lead->lead_id . ')" data-lead-id="' . $lead->lead_id . '">
                            <option value="0">Select</option>
                            <option value="1" ' . ($lead->status == 1 ? 'selected' : '') . '>Lead Closed</option>
                            <option value="2" ' . ($lead->status == 2 ? 'selected' : '') . '>In Contact</option>
                            <option value="3" ' . ($lead->status == 3 ? 'selected' : '') . '>No Response</option>
                            <option value="4" ' . ($lead->status == 4 ? 'selected' : '') . '>Awaiting Response</option>
                        </select>',
				"actions" => '<a onclick="deleteData(' . $lead->lead_id . ')" class="" href="javascript:void(0)">
            <i class="material-icons">delete</i> 
        </a>'
			];
		}

		$response = [
			"draw" => intval($draw),
			"recordsTotal" => $totalRecords,
			"recordsFiltered" => $recordsFiltered,
			"data" => $data
		];

		echo json_encode($response);
		die;
	}

	public function customers()
	{
		$data['title'] = 'Customers | Portal';
		$data['page_name'] = 'Customers';

		$data['leads'] = $this->common_model->select_where_ASC_DESC('*', 'leads', array('status' => 1, 'isDeleted' => 0), 'lead_id', 'DESC');
		$var['content'] = $this->load->view('pages/customers', $data, true);
		$this->load->view('template2022', $var);
	}

	public function get_customers_data()
	{
		$this->load->model('common_model');

		// Fetch POST parameters
		$user_email = $this->input->post('user_email');
		$user_name = $this->input->post('user_name');
		$source = $this->input->post('source');
		$searchByFromdate = $this->input->post('searchByFromdate');
		$searchByTodate = $this->input->post('searchByTodate');
		$draw = $this->input->post('draw');
		$start = intval($this->input->post('start')); // Ensure integer
		$length = intval($this->input->post('length')); // Ensure integer
		$orderColumnIndex = intval($this->input->post('order')[0]['column']); // Ensure integer
		$orderDir = $this->input->post('order')[0]['dir'];
		$searchValue = $this->input->post('search')['value'];

		// Map DataTables columns index to database columns
		$columns = [
			'lead_id',
			'client_info',
			'preferred_date',
			'preferred_time',
			'source',
			'comment',
		];

		// Ensure order column is valid
		$orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'lead_id';

		// Conditions
		$conditions = ['status' => 1, 'isDeleted' => 0];

		// Count total records without filtering
		$totalRecords = $this->common_model->count_all('leads', $conditions);

		// Count filtered records
		$recordsFiltered = $this->common_model->count_filtered(
			'leads',
			$searchValue,
			$user_email,
			$user_name,
			$source,
			$searchByFromdate,
			$searchByTodate,
			$conditions
		);

		// Fetch records with filtering, ordering, and pagination
		$leads = $this->common_model->fetch_leads(
			'leads',
			$searchValue,
			$start,
			$length,
			$orderColumn,
			$orderDir,
			$user_email,
			$user_name,
			$source,
			$searchByFromdate,
			$searchByTodate,
			$conditions
		);

		// Format data for DataTables
		$data = [];
		foreach ($leads as $lead) {
			$data[] = [
				"lead_id" => 'LC-' . $lead->lead_id,
				"client_info" => '<div class="media"><div class="media-body"><p class="mb-0 template-inverse">' . htmlspecialchars($lead->user_name, ENT_QUOTES, 'UTF-8') . '</p><p class="text-template-primary-light">' . htmlspecialchars($lead->user_phone, ENT_QUOTES, 'UTF-8') . '</p><p class="text-template-primary-light">' . htmlspecialchars($lead->user_email, ENT_QUOTES, 'UTF-8') . '</p></div></div>',
				"preferred_date" => $lead->preferred_date,
				"preferred_time" => $lead->preferred_time,
				"source" => $lead->source,
				"comment" => '<p style="font-size: smaller;">' . htmlspecialchars($lead->comment, ENT_QUOTES, 'UTF-8') . '</p>',
				"actions" => '<a onclick="deleteData(' . intval($lead->lead_id) . ')" class="" href="javascript:void(0)"><i class="material-icons">delete</i></a>'
			];
		}

		// Send JSON response
		$response = [
			"draw" => intval($draw),
			"recordsTotal" => $totalRecords,
			"recordsFiltered" => $recordsFiltered,
			"data" => $data
		];

		echo json_encode($response);
		die;
	}

	public function update_status()
	{
		// Get POST data
		$lead_id = $this->input->post('lead_id');
		$status = $this->input->post('status');
		$comment = $this->input->post('comment');

		if ($lead_id && $status !== null) {
			$where = array('lead_id' => $lead_id);
			$data = array(
				'status' => $status,
			);

			// Update status and comment in the database
			$updated = $this->common_model->update_array($where, 'leads', $data);

			if ($updated) {
				$comments_data = array(
					'lead_id' => $lead_id,
					'content' => $comment
				);
				$this->common_model->insert_array('lead_comments', $comments_data);
				echo json_encode(['status' => 'success']);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
		}
	}


	public function deleteIt()
	{
		// Get POST data
		$lead_id = $this->input->post('lead_id');
		$status = $this->input->post('status');

		if ($lead_id && $status !== null) {
			$where = array('lead_id' => $lead_id);
			if ($status == 1) {
				$data = array(
					'isDeleted' => 1
				);
				$updated = $this->common_model->update_array($where, 'leads', $data);
			} else {
				$updated = $this->common_model->delete_where($where, 'leads');
			}
			if ($updated) {
				echo json_encode(['status' => 'success']);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Failed to delete']);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
		}
	}
}
