<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once('vendor/getID3-master/getid3/getid3.php');
//Function starts here
function admin_auth()
{
	$CIH = &get_instance();

	$admin_id = $CIH->session->userdata('admin_login');
	if ($admin_id == '') {
		redirect(base_url());
	}
}

function check_role($role)
{
	$CI = &get_instance();
	$user_role = $CI->session->userdata('role_id');
	if ($user_role != $role) {
		redirect('pages/auth/unauthorized');
	}
}

function customMiddleware()
{
	$CIH = &get_instance();
	$role_name = $CIH->user_model->get_role_by_user_id($CIH->session->userdata('user_id'));
	return $role_name;
}

function getRoleName()
{
	$CIH = &get_instance();
	$role_name = $CIH->user_model->get_role_by_user_id($CIH->session->userdata('user_id'));
	return $role_name;
}
function middlewareAdmin()
{
    if (customMiddleware() != 'Mentor' && customMiddleware() != 'Super Admin' && customMiddleware() != 'Admin for Teachers') {
        redirect('AccessDenied');
    }
}

function middlewareStudents()
{
    if (customMiddleware() != 'Student') {
        redirect('AccessDenied');
    }
}
include_once('vendor/getID3-master/getid3/getid3.php');

function get_video_details($filePath) {
    // Check if the file exists
    if (!file_exists($filePath)) {
        return ['error' => 'File does not exist: ' . $filePath];
    }

    // Initialize getID3
    $getID3 = new getID3;

    // Analyze the file
    $file = $getID3->analyze($filePath);

    // Check if the analysis was successful
    if (empty($file)) {
        return ['error' => 'getID3 could not analyze the file.'];
    }

    // Check the file extension/type
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
    if (!in_array($fileExtension, ['mp4', 'avi', 'mov'])) {
        return ['error' => 'Unsupported file type: ' . $fileExtension];
    }

    // If the file is a video, return video-related details
    if (isset($file['playtime_string']) && isset($file['video']['resolution_x']) && isset($file['video']['resolution_y'])) {
        return [
            'duration' => $file['playtime_string'],
            'width' => $file['video']['resolution_x'],
            'height' => $file['video']['resolution_y'],
            'filesize' => $file['filesize']
        ];
    } else {
        // Handle cases where video data is missing
        return ['error' => 'Could not retrieve video details.'];
    }
}



function encode($value)
{
	if (!$value)
		return false;
	$ci = &get_instance();
	return strtr($ci->encryption->encrypt($value), array('+' => '--1', '=' => '--2', '/' => '--3'));
}

function decode($value)
{
	if (!$value)
		return false;
	$ci = &get_instance();
	return $ci->encryption->decrypt(strtr($value, array('--1' => '+', '--2' => '=', '--3' => '/')));
}
