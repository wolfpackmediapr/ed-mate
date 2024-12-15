<?php
function asset_url()
{
   return base_url() . 'assets/';
}

function createActivityMessage($activity_title, $vendor_id, $module_id)
{
   $OBJ = &get_instance();
   // Retrieve the token from session
   $token = $OBJ->session->userdata('token');
   $postData = array(
      'activity_title' => $activity_title,
      'user_id' => $vendor_id,
      'module_id' => $module_id
   );
   // $OBJ->common_model->insert_array('activity_log_tbl', $postData);
   // Send data to external URL
   $url = $OBJ->ci->config->item('api_url');
   $OBJ->api_request->post($url, $postData, $token);
}

function generate_batch_id()
{
   $date_time = date('YmdHis'); // Format: YYYYMMDDHHMMSS
   $random_number = mt_rand(1000, 9999); // Add a random number for additional uniqueness
   return $date_time . $random_number;
}
