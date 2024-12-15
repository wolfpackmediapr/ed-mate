<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->helper('security');
	}
	
public function storeLeads()
{
    $this->load->database();

    // Allow CORS from any origin (you can restrict it to specific domains if needed)
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    // Handle the preflight OPTIONS request
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit;
    }

    // Retrieve input data
    $leadId = $this->input->post('lead_id', TRUE);
    $userName = $this->input->post('user_name');
    $userEmail = $this->input->post('user_email', TRUE);
    $userPhone = $this->input->post('user_phone', TRUE);
    $preferredDate = $this->input->post('preferred_date', TRUE);
    $preferredTime = $this->input->post('preferred_time', TRUE);
    $source = $this->input->post('source', TRUE);
    $comments = $this->input->post('comments', TRUE);

    // Prepare data array
    $data = array(
        'user_name' => $userName,
        'user_email' => $userEmail,
        'user_phone' => $userPhone,
        'preferred_date' => $preferredDate,
        'preferred_time' => $preferredTime,
        'source' => $source,
        'comment' => $comments
    );
    
    // print_r($data);
    // die;

    if ($leadId == '0') {
        // Insert new lead and get the ID
        $this->db->insert('leads', $data);
        $newLeadId = $this->db->insert_id();

        $response = array(
            'status' => 'success',
            'message' => "New lead added successfully.",
            'lead_id' => $newLeadId
        );
    } else {
        // Update existing lead
        $this->db->where('lead_id', $leadId);
        $this->db->update('leads', $data);

        $response = array(
            'status' => 'success',
            'message' => "Lead updated successfully."
        );
    }

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}




	public function index()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');


		if ($this->form_validation->run() === FALSE) {
			$this->load->view('pages/login');
		} else {
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$query = $this->adminmodel->login($username, $password);

			if ($query->num_rows() > 0) {
				$row = $query->row();

				if ($row->role_id == 1 && $row->status == 1) {

					$this->session->set_userdata('admin_login', $row->admin_id);
					$this->session->set_userdata('role_id', $row->role_id);
					$this->session->set_userdata('user_id', $row->user_id);

					$this->session->set_userdata('user_name', $row->first_name . ' ' . $row->last_name);
					redirect('dashboard');
				} else {
					$this->session->set_userdata(array('msg' => 'You do not have access, Please Contact With Support!', 'class' => 'alert alert-danger'));
					redirect(base_url());
				}
			} else {
				$this->session->set_userdata(array('msg' => 'Username Or Password is wrong!', 'class' => 'alert alert-danger'));
				redirect(base_url());
			}
		}
	}

	function logout()
	{
		$this->session->unset_userdata('token');
		// $this->session->unset_userdata('role_id');
		redirect(base_url());
	}

	/***************** Change Password ********************/

	function change_pass()
	{

		$this->form_validation->set_rules('admin_old_password', 'Old Password', 'trim|required');
		$this->form_validation->set_rules('admin_new_password', 'New Password', 'trim|required');

		if ($this->form_validation->run() === FALSE) {

			$data['page_name']		=	"Manage Change Password";
			$data['page_sub_name']	=	"Change Password";


			$res['content'] = $this->load->view('wl-administrative/sitesettings/change_password', $data, true);
			$this->load->view('wl-administrative/template', $res);
		} else {

			$old_password = $this->input->post('admin_old_password');
			$new_password = $this->input->post('admin_new_password');


			$data1['admin_password'] = $old_password;
			$data['admin_password'] = $new_password;

			$af_row = $this->Adminmodel->change_pass('wl_admin', $data, $data1);
			if ($af_row > 0) {
				$this->session->set_userdata('msg_succ', 'Password Changed Successfully.');
				redirect('wl-administrative/main/change_pass');
			} else {
				$this->session->set_userdata('msg', 'Your Old Password Does Not Match.');
				redirect('wl-administrative/main/change_pass');
			}
		}
	}

	function addRole()
	{

		$data['title'] = 'Add Role';
		$data['page_name'] = 'Add Role';
		$data['f_icon'] = asset_url() . 'dist/img/fav-icons/user-add.png';
		$data['websites'] = $this->common_model->select_where_ASC_DESC('*', 'our_websites', array('status' => 1), 'id', 'DESC');

		$var['content'] = $this->load->view('users/roleView', $data, true);
		$this->load->view('template2022', $var);
	}

	function insertRole()
	{
		// echo '<pre>';
		// print_r($_POST);

		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('roles', 'Roles', 'trim|required');
		$this->form_validation->set_rules('admin_password', 'Password', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');


		if ($this->input->post('rate_per_page')) {
			$this->form_validation->set_rules('rate_per_page', 'Rate per page', 'trim|required');
			$data['rate_per_page'] = $this->input->post('rate_per_page');
		} else {
			$data['rate_per_page'] = 0;
		}

		if ($this->input->post('region')) {
			$this->form_validation->set_rules('region', 'region', 'trim|required');
			$data['region'] = $this->input->post('region');
		} else {
			$data['region'] = 0;
		}

		if ($this->input->post('allowed_orders')) {
			$this->form_validation->set_rules('allowed_orders', 'allowed orders', 'trim|required');
			$data['allowed_orders'] = $this->input->post('allowed_orders');
			$data['available_limit'] = $this->input->post('allowed_orders');
		} else {
			$data['allowed_orders'] = 0;
		}


		// bank details 

		if ($this->input->post('bank_name')) {
			$this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
			$data['bank_name'] = $this->input->post('bank_name');
		}

		if ($this->input->post('phone')) {
			$this->form_validation->set_rules('phone', 'phone', 'trim|required');
			$data['phone'] = $this->input->post('phone');
		}

		if (isset($_POST['branch_code'])) {
			// $this->form_validation->set_rules('branch_code', 'branch code', 'trim|required');
			$data['branch_code'] = $this->input->post('branch_code');
		}

		if ($this->input->post('account_number')) {
			$this->form_validation->set_rules('account_number', 'account number', 'trim|required');
			$data['account_number'] =  $this->input->post('account_number');
		}

		if ($this->input->post('account_title')) {
			$this->form_validation->set_rules('account_title', 'account title', 'trim|required');
			$data['account_title'] = $this->input->post('account_title');
		}

		if ($this->input->post('iban_number')) {
			$this->form_validation->set_rules('iban_number', 'iban number', 'trim|required');
			$data['iban_number'] = $this->input->post('iban_number');
		}

		// end bank details 

		if ($this->form_validation->run() === FALSE) {
			$this->addRole();
		} else {
			$data['first_name'] = $this->input->post('first_name');
			$data['last_name'] = $this->input->post('last_name');
			$data['admin_password'] = $this->input->post('admin_password');
			$data['status'] = $this->input->post('status');
			$data['role_id'] = $this->input->post('roles');
			$data['email'] = $this->input->post('email', TRUE);
			$data['phone'] = $this->input->post('phone');
			// print_r($data); exit;

			$is_available = $this->common_model->select_where_return_row('*', 'admin', array('email' => $this->input->post('email', TRUE)));
			if (!$is_available) {
				$is_inserted = $this->common_model->insert_array('admin', $data);
				if ($is_inserted) {
					$site_ids = $this->input->post('site_ids');
					if ($site_ids) {
						foreach ($site_ids as $site_name) {
							$webNames[] = array(
								'user_id'	=>	$is_inserted,
								'site_name'	=> $site_name
							);
						}
						$this->db->insert_batch('web_access', $webNames);
					}
					$this->session->set_flashdata('msg', '<strong>success: User Added Successfully!</strong>');
					redirect('manage-roles');
				} else {
					$this->session->set_flashdata('msg', '<strong>failed: this email is already exist!</strong>');
					redirect('add-role');
				}
			} else {
				$this->session->set_flashdata('msg', '<strong>failed: this email is already exist..</strong>');
				redirect('add-role');
			}
		}
	}

	function validate($fieldName, $msg, $rules)
	{
		$this->form_validation->set_rules($fieldName, $msg, $rules);
	}

	function usersList()
	{

		$data['title'] = 'Registered Clients';
		$data['page_name'] = 'Registered Clients';
		$data['f_icon'] = asset_url() . 'dist/img/fav-icons/check-icon.png';

		$data['users'] = $this->common_model->select_where_ASC_DESC('*', 'users', array(), 'user_id', 'DESC');
		$var['content'] = $this->load->view('users/usersListView', $data, true);
		$this->load->view('template2022', $var);
	}

	function deleteUser()
	{

		$user_id = $this->input->post('user_id');
		$table_name = $this->input->post('table_name');
		if ($table_name == 'admin') {
			$key = 'admin_id';
		} else {
			$key = 'user_id';
		}

		$isDeleted = $this->common_model->update_array(array($key => $user_id), $table_name, array('is_deleted' => 1));

		if ($isDeleted) {
			$data = array('msg' => 'success');
		} else {
			$data = array('msg' => 'failed');
		}
		echo json_encode($data);
	}

	function manageRoles()
	{

		$data['title'] = 'Manage Roles';
		$data['page_name'] = 'Manage Roles';
		$data['f_icon'] = asset_url() . 'dist/img/fav-icons/settings-icon.png';

		$data['users'] = $this->common_model->select_where('*', 'admin', array('is_deleted' => 0, 'role_id !=' => 1));

		$var['content'] = $this->load->view('users/manageRoles', $data, true);
		$this->load->view('template2022', $var);
	}

	function editProfile()
	{

		$data['title'] = 'Manage Roles';
		$data['page_name'] = 'Manage Roles';
		$data['f_icon'] = asset_url() . 'dist/img/fav-icons/edit-user-icon.webp';

		$data['users'] = $this->common_model->select_where('*', 'admin', array('is_deleted' => 0));

		$var['content'] = $this->load->view('users/manageRoles', $data, true);
		$this->load->view('template2022', $var);
	}

	function editRole($id)
	{
		if ($_SERVER['REQUEST_METHOD']  == 'POST') {
			// echo '<pre>';print_r($this->input->post()); die;
			$data = $this->input->post();

			if (empty($data['admin_password'])) {
				$password = $this->common_model->select_where_return_row('admin_password', 'admin', array('admin_id' => $id));
				$data['admin_password'] = $password->admin_password;
			} else {
				$data['admin_password'] = $data['admin_password'];
			}
			if (!empty($data['rate_per_page'])) {
				$rpp = $this->common_model->select_where_return_row('rate_per_page', 'admin', array('admin_id' => $id));
				$data['updated_rate_per_page'] = $data['rate_per_page'];
				$data['rate_per_page'] = $rpp->rate_per_page;
			}
			if (!empty($data['region'])) {
				if (count($data['region']) === 1) {
					$data['region'] = $data['region'][0];
				} else {
					$data['region'] = implode(",", $data['region']);
				}
			} else {
				$data['region'] = 0;
			}
			if (!empty($data['bank_name'])) {
				$data['bank_name'] = $data['bank_name'];
			}

			if (isset($data['phone']) && !empty($data['phone'])) {
				$data['phone'] = $data['phone'];
			}

			if (!empty($data['account_number'])) {
				$data['account_number'] = $data['account_number'];
			}

			if (!empty($data['branch_code'])) {
				$data['branch_code'] = $data['branch_code'];
			}

			if (!empty($data['account_title'])) {
				$data['account_title'] = $data['account_title'];
			}

			if (!empty($data['iban_number'])) {
				$data['iban_number'] = $data['iban_number'];
			}
			if (isset($data['allowed_orders'])) {
				$data['available_limit'] = $data['allowed_orders'];
			}

			if (isset($data['show_from_date'])) {
				$data['show_from_date'] = $data['show_from_date'];
			}
			// echo '<pre>';print_r($data);exit;
			$is_updated = $this->common_model->update_array(array('admin_id' => $id), 'admin', $data);

			if ($is_updated) {
				$this->session->set_flashdata('success', '<strong>Success: Profile Updated Successfully.</strong>');
				redirect('edit-role/' . $id);
			} else {
				$this->session->set_flashdata('error', '<strong>Error: Not Updated.</strong>');
				redirect('edit-role/' . $id);
			}
		} else {
			$data['title'] = 'Edit Role';
			$data['f_icon'] = asset_url() . 'dist/img/fav-icons/edit-user-icon.webp';
			$data['page_name'] = 'Edit Role';
			$data['websites'] = $this->common_model->select_where_ASC_DESC('*', 'our_websites', array('status' => 1), 'id', 'DESC');
			$data['u_id'] = $id;
			$data['user'] = $this->common_model->select_where_return_row('*', 'admin', array('admin_id' => $id));
			$data['f_icon'] = asset_url() . 'dist/img/fav-icons/edit-user-icon.webp';
			$var['content'] = $this->load->view('users/edit_role', $data, true);
			$this->load->view('template2022', $var);
		}
	}

	function updateProfile()
	{

		$data['admin_username'] = $this->input->post('admin_username');
		$data['email'] = $this->input->post('email');
		$data['phone'] = $this->input->post('phone');
		$user_id = $this->session->userdata('id');

		$is_updated = $this->common_model->update_array(array('user_id' => $user_id), ' users', $data);

		if ($is_updated) {
			$this->session->set_flashdata('msg_address', '<strong>Success: Profile Updated Successfully.</strong>');
			redirect('my-profile');
		} else {
			$this->session->set_flashdata('msg_address', '<strong>Error: Not Updated.</strong>');
			redirect('my-profile');
		}
	}

	function blockUnblockUser()
	{
		$admin_id = $this->input->post('admin_id');
		$status = $this->input->post('status');
		$data = array();

		$check = $this->common_model->update_array(array('admin_id' => $admin_id), 'admin', array('status' => $status));
		if ($check) {
			$data = array('msg' => 'success', 'des' => 'Successfully Done!');
		} else {
			$data = array('msg' => 'failed', 'des' => 'Failed');
		}
		echo json_encode($data);
	}

	function isPaid()
	{
		$order_id = $this->input->post('order_id');
		$url = $this->input->post('url');
		$status = $this->changeToPaid($order_id);
		if ($url == 'unpaid') {
			$arr = array('msg' => 'success', 'url' => 'unpaid-orders');
		} else {
			$arr = array('msg' => 'success', 'url' => 'master-search');
		}


		if ($status) {
			echo json_encode($arr);
			exit();
		}
	}
	function sendmail($array)
	{
		$to = $array->user_email;
		$subject = "Order Placed";
		$data['data'] = $array;

		$message = $this->load->view('emails/change-to-paid', $data, TRUE);
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <info@paperly.net>' . "\r\n";
		if (mail($to, $subject, $message, $headers)) {
			return 1;
		} else {
			return 0;
		}
	}
	// function sendPaidMail($array){
	// 	require 'PHPMailer/src/Exception.php';
	//     require 'PHPMailer/src/PHPMailer.php';
	//     require 'PHPMailer/src/SMTP.php';

	// 	 $mail = new PHPMailer;
	//     $mail->isMail();                                      // Set mailer to use SMTP
	//     $mail->Host = 'localhost';  // Specify main and backup SMTP servers
	//     $mail->SMTPAuth = true;                               // Enable SMTP authentication
	//     $mail->Username = 'no-reply@paperly.net';                 // SMTP username
	//     $mail->Password = 'allStarTech@123';                           // SMTP password
	//     $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	//     $mail->Port = 587;                                    // TCP port to connect to

	//     $mail->From = 'no-reply@paperly.net';
	//     $mail->FromName = 'Paperly';
	//     //$mail->addaddress('joe@example.net', 'Joe User');     // Add a recipient
	//     $mail->addAddress($array->user_email, $array->user_name);               // Name is optional

	//     //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments

	//     $mail->isHTML(true);                                  // Set email format to HTML

	//     $mail->Subject = 'Thanks for Order';
	//    	$data['data'] = $array;
	// 	$body = $this->load->view('emails/change-to-paid',$data,TRUE);
	//     $mail->Body = $body;


	//     $mail->AltBody = 'You are using basic web browser ';

	//     if(!$mail->send()) {
	//         echo 'Message could not be sent.';
	//         echo 'Mailer Error: ' . $mail->ErrorInfo;
	//     } else {
	//      return 1;
	//     }
	// }


	function fetchUsers()
	{
		$role = $this->input->post('role');
		if ($role == '') {
			$array = $this->common_model->select_where('*', 'admin', array('is_deleted' => 0, 'role_id !=' => 1));
		} else {
			$array = $this->common_model->select_where('*', 'admin', array('is_deleted' => 0, 'role_id' => $role));
		}
		echo json_encode(array('users' => $array));
	}

	function websiteList()
	{

		$data['title'] = 'Our Sites';
		$data['page_name'] = 'Our Sites';
		$data['f_icon'] = asset_url() . 'dist/img/fav-icons/global.png';

		$data['websites'] = $this->common_model->select_where_ASC_DESC('*', 'our_websites', array('status' => 1), 'id', 'DESC');
		$var['content'] = $this->load->view('websites/manage_sites', $data, true);
		$this->load->view('template2022', $var);
	}

	function addWeb()
	{

		$data['title'] = 'Add Website';
		$data['page_name'] = 'Add Website';
		$data['f_icon'] = asset_url() . 'dist/img/fav-icons/check-icon.webp';
		$var['content'] = $this->load->view('websites/add_website', $data, true);
		$this->load->view('template2022', $var);
	}

	function editWeb($id)
	{
		if ($_SERVER['REQUEST_METHOD']  == 'POST') {

			$data = $this->input->post();
			$is_updated = $this->common_model->update_array(array('id' => $id), 'our_websites', $data);

			if ($is_updated) {
				$this->session->set_flashdata('success', '<strong>Success: Info Updated Successfully.</strong>');
				redirect('edit-web/' . $id);
			} else {
				$this->session->set_flashdata('error', '<strong>Error: Not Updated.</strong>');
				redirect('edit-web/' . $id);
			}
		} else {
			$data['title'] = 'Edit Web';
			$data['f_icon'] = asset_url() . 'dist/img/fav-icons/edit-user-icon.webp';
			$data['page_name'] = 'Edit Web';
			$data['web_id'] = $id;

			$data['website'] = $this->common_model->select_where_return_row('*', 'our_websites', array('id' => $id));
			$data['f_icon'] = asset_url() . 'dist/img/fav-icons/edit-user-icon.webp';
			$var['content'] = $this->load->view('websites/edit_website', $data, true);
			$this->load->view('template2022', $var);
		}
	}

	function insertWeb()
	{
		$this->form_validation->set_rules('website_name', 'Web Name', 'trim|required');
		$this->form_validation->set_rules('website_url', 'Web Url', 'trim|required');
		$this->form_validation->set_rules('category', 'Category', 'trim|required');
		// end bank details 

		if ($this->form_validation->run() === FALSE) {
			$this->addRole();
		} else {
			$data['website_name'] = $this->input->post('website_name');
			$data['website_url'] = $this->input->post('website_url');
			$data['category'] = $this->input->post('category');
			$data['slug'] = $this->input->post('slug');
			$data['api_url'] = $this->input->post('api_url');
			$is_available = $this->common_model->select_where_return_row('*', 'our_websites', array('website_url' => $data['website_url']));

			if (!$is_available) {

				$is_inserted = $this->common_model->insert_array('our_websites', $data);
				if ($is_inserted) {
					$this->session->set_flashdata('msg', '<strong>success: Added Successfully!</strong>');
					redirect('web-list');
				} else {
					redirect('add-web');
				}
			} else {
				$this->session->set_flashdata('msg', '<strong>Failed: already exist</strong>');
				redirect('add-web');
			}
		}
	}

	function changeWebAccess()
	{
		$data['is_accessible'] = $_POST['is_accessible'];
		$website_name = $_POST['website_name'];
		$user_id = $_POST['user_id'];
		$arr = array();

		$is_available = $this->common_model->select_where_return_row('site_name', 'web_access', array('user_id' => $user_id, 'site_name' => $website_name));

		if ($is_available) {
			$status = $this->common_model->update_data_array(array('user_id' => $user_id, 'site_name' => $website_name), 'web_access', $data);
			if ($status) {
				$arr = array("msg" => "success");
			} else {
				$arr = array("msg" => "failed");
			}
		} else {
			$webData['site_name'] = $_POST['website_name'];
			$webData['user_id'] = $_POST['user_id'];
			$webData['is_accessible'] = $_POST['is_accessible'];
			$this->db->insert('web_access', $webData);
			$lead_id = $this->db->insert_id();
			if ($lead_id) {
				$arr = array("msg" => "success");
			} else {
				$arr = array("msg" => "failed");
			}
		}





		echo json_encode($arr);
	}

	function setDomains()
	{
		$category =  $_POST['id'];
		if ($category !== '') {
			$websites = $this->common_model->select_where('*', 'our_websites', array('category' => $category));
		} else {
			$websites = $this->common_model->select_where('*', 'our_websites', array());
		}
		$data2 = '';
		$succesError = '';
		$data2 .= '<option value="">Select Domain</option>';
		foreach ($websites as $website) {
			$data2 .= '<option value="' . $website->website_name . '">' . $website->website_url . '</option>';
		}
		if ($websites) {
			$succesError = 200;
		} else {
			$succesError = 404;
		}
		echo json_encode(array('data' => $data2, 'msg' => $succesError));
	}

	function delete($id)
	{
		$result = $this->common_model->delete_where(array('id' => $id), 'our_websites');

		if ($result) {
			$this->session->set_flashdata('msg', '<strong>success: Deleted Successfully!</strong>');
			redirect('web-list');
		} else {
			redirect('web-list');
		}
	}
	public function searchUser()
	{


		$searchSpecific = '';

		$domain_name = '';

		if ($this->input->post('searchSpecific') !== '') {
			$searchSpecific = $this->input->post('searchSpecific');
			if (strpos($searchSpecific, "-")) {
				$array = explode("-", $searchSpecific);
				$searchSpecific = $array[1];
			}
		}

		if ($this->input->post('domain_name') !== '') {
			$domain_name = $this->input->post('domain_name');
		}
		$leads = $this->general_model->like_search('*', 'users', $searchSpecific, 'user_id', 'desc', $domain_name);

		$data =  $this->setUsers(array(), $leads);

		echo $data;
	}
}
