<?php
// ini_set("display_errors", "1");
// error_reporting(E_ALL);
session_start();
include("configuration.php");
$user_id	= $_SESSION['user_id'];
$profilepob	= $_POST['profile_pob'];
$profilesign	= $_POST['profile_sign'];
$profileemail	= $_POST['profile_email'];
 $profilemsisdn	= $_POST['profile_msisdn'];

if(isset($profilemsisdn)) {

	$post_data = array(
		'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
		'action'		=> 'profile_submit',
		'user_id'		=> $user_id,
		'pob'			=> $profilepob,
		'sign'			=> $profilesign,
		'email'			=> $profileemail,
		'msisdn'			=> $profilemsisdn,
		'session_code'	=> $_SESSION["session_code"],
		'source'		=> 'WEB'
	);

	$result_profile = curl_hit($post_data);
	echo $response = json_encode($result_profile, true);
	
} else {
	if(isset($_POST['onlypropic']) && $_POST['onlypropic'] == '1') {
		 if(!empty($_FILES['profileimage']['name'])) {		
				 $filename  = $_FILES['profileimage']['tmp_name'];
	if(file_exists($filename)){
		$handle    = fopen($filename, "r");
		$data      = fread($handle, filesize($filename));
	}
		
		$post_data = array(
		'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
		'action'		=> 'profile_submit',
		'user_id'		=> $user_id,
		'profile_img'	=> base64_encode($data),
		'onlypropic'	=> 1,
		'session_code'	=> $_SESSION["session_code"],
		'source'		=> 'WEB'
	);	
			
			
		$result = curl_hit($post_data);
		echo  json_encode($result);
	}
	}else{
		header('Location: settings.php?tab_val=2&error=yes&message=Name+and+MobileNo+is+mandatory');
	}
}
?>