<?php
include("../configuration.php");

session_start();
if($_REQUEST['razorpay_payment_id'] !="" && $_REQUEST['razorpay_order_id'] !="" && $_REQUEST['razorpay_signature'] !="" ){
	
	$_REQUEST['source'] 	=	'web';
	$_REQUEST['oauth'] 	= 	'7ff7c3ed4e791da7e48e1fbd67dd5b72';
	$_REQUEST['action'] 		= 	'razor_payment_confirm';
	$_REQUEST['session_code'] = $_SESSION['session_code'];
	$_SESSION['avd'] = $_REQUEST['ttaavail_date'];
	$_SESSION['avt'] = $_REQUEST['ttaavail_time'];
	$horo_array = array("Aries" => "1", "Taurus" => "2", "Gemini" => "3", "Cancer" => "4", "Leo" => "5", "Virgo" => "6","Libra" => "7", "Scorpio" => "8", "Sagittarius" => "9", "Capricornus" => "10", "Aquarius" => "11", "Pisces" => "12");
	$_SESSION['horo_id'] = $horo_array[$_REQUEST['signId']];
	$_SESSION['horo_name'] = $_REQUEST['signId'];
	$_SESSION['horo_type'] = $_REQUEST['product_ids'];
	
$ch = curl_init( API_URL);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($_REQUEST) );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$result = curl_exec($ch);
	curl_close($ch);
	$response = json_decode($result, true);
	
	print_r($response);
	//alert($response['returncode']);
	if($response['returncode'] == '200'){
		$_SESSION['paid_type'] = 'paid';
		echo "paid";
		if($_REQUEST['for']=='tta_'){
			//header('Location: '.APPLICATION_URL.'gateway/talk.php?status=success&for=tta_&avd='.$_REQUEST['ttaavail_date'].'&avt='.$_REQUEST['ttaavail_time'].'');
			header('Location: '.APPLICATION_URL.'gateway/talk.php?status=success');
		}
		else if($_REQUEST['for']=='horo_')
		{
		header('Location: '.APPLICATION_URL.'gateway/payment-status.php?status=success&for=horoscope');
		}
		else if($_REQUEST['for']=='aaq_')
		{
		header('Location: '.APPLICATION_URL.'gateway/payment-status.php?status=success&for=aaq');
		}
	}else{
		echo "not_paid";
		//header('Location: ../../home.php');
		header('Location: '.APPLICATION_URL.'gateway/payment-status.php?status=error');
	}
}else{
	echo "Something went wrong";
}
?>