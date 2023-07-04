<?php
ob_start();
function curl_hit($data_json, $api_url='')
{
	$data_json['oauth'] = OAUTH_STRING;
	$url = ($api_url == '' ) ? API_URL : $api_url;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data_json,true));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output=curl_exec($ch);
	if(curl_errno($ch))
	{
		$Code = curl_getinfo($ch);
	}
	curl_close($ch);
	$response = json_decode($output,true);

	if($response['returncode'] == 202 && $response['returnmessage'] == "Oops! Looks like your user id is used in another device.. Logging out !")
	{
		session_destroy();
		header('Location:index.php?error_code=202');
		exit;
	}
	return $response;
}

/* function print_data($data)
{
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}
 */
?>