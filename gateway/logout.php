<?php
	session_start();
	include_once("configuration.php");
	$post_data = array(
		'oauth'	=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
		'user_id' => $_SESSION['user_id'],
		'action' => "log_out",
		'session_code' => $_SESSION['session_code']
	);
	$ch = curl_init( API_URL);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($post_data) );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$result = curl_exec($ch);
	curl_close($ch);
	$response	= json_decode($result,true);
	// print_r($response);
	// exit;
	session_destroy();
	header("Location: home.php");
	
?>
