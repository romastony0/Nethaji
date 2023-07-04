<?php
include("../configuration.php");

$payment_html='';

session_start();

// print_r($_REQUEST);
// exit;
$for = $_REQUEST['for_payment'];
if (isset($_REQUEST[$for . 'user_id'])) {

	$user_id = $_SESSION['user_id'];
	$pay_name = $_REQUEST[$for . 'stuname'];
	$pay_email = $_REQUEST[$for . 'email_address'];
	$pay_mobile = $_REQUEST[$for . 'stutelephone'];
	$pay_pincode = $_REQUEST[$for . 'pincode'];
	$pay_address = $_REQUEST[$for . 'address_1'];
	$pay_city = $_REQUEST[$for . 'city'];
	$pay_state = $_REQUEST[$for . 'state'];
	$pay_country = $_REQUEST[$for . 'countrycode'];
	$pay_landmark = $_REQUEST[$for . 'landmark'];
	$pay_mode = $_REQUEST[$for . 'paymode'];
	$totalamount = $_REQUEST[$for . 'totalamount'];
	$shippingamount = $_REQUEST[$for . 'shippingamount'];
	$signId = $_REQUEST[$for . 'signId'];
	$grandtotalval = $_REQUEST[$for . 'grandtotalval'] = $_SESSION['buy-price'] * 100;
	$pay_currency = (isset($_REQUEST[$for . 'currency'])) ? $_REQUEST[$for . 'currency'] : 'INR';
	$product_ids = $_REQUEST[$for . 'product_ids'];
	$trans_id = $_REQUEST[$for . 'trans_id'];
	$avail_id = $_REQUEST[$for . 'avail_id'];
	$avail_date = $_REQUEST[$for . 'avail_date'];
	$avail_time = $_REQUEST[$for . 'avail_time'];

	//echo "<pre>";print_r($_REQUEST);echo "</pre>";exit();
	// if(isset($pay_name) && !empty($pay_name)  && isset($pay_mode) && !empty($pay_mode) && isset($pay_currency) && !empty($pay_currency) && isset($pay_email) && !empty($pay_email) && isset($pay_mobile) && !empty($pay_mobile) && isset($pay_country) && !empty($pay_country)&& isset($product_ids) && !empty($product_ids) ) {
	if (isset($_REQUEST[$for . 'payment_method']) && $_REQUEST[$for . 'payment_method'] == 'razorpay') {
		$charge_amount = $_POST['lprice'];
		$url = API_URL;
		$post_data = array(
			'oauth' => '7ff7c3ed4e791da7e48e1fbd67dd5b72',
			'action' => 'get_razorpay_orderid',
			'user_id' => $user_id,
			'pay_name' => $pay_name,
			'pay_mobile' => $pay_mobile,
			'source' => 'web',
			'pay_mode' => 'Razorpay',
			'pay_currency' => 'INR',
			'source' => 'web',
			'productId' => $product_ids,
			'amount' => $grandtotalval,
			'signId' => $signId,
			'transId' => $trans_id,
			'for' => $for,
			// 'amount'=> 100,
			'session_code' => $_SESSION['session_code']
		);


		if ($pay_assessment_id != "") {
			$post_data['pay_assessment_id'] = $pay_assessment_id;
		}
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($result, true);
		if ($response['returncode'] != '200') {
			$res = $response['returnmessage'];
			echo '<script>
						alert("' . $res . '");
					</script>';
		} else {
			$result_data = $response['paymentmsg'];
			if (!empty($result_data)) {
				?>
				
				<style>
				.razorpay-payment-button{
					display: none;
				}
				</style>
				<form action="success_page.php" method="POST" id="checkout">
				<script
					src="https://checkout.razorpay.com/v1/checkout.js"
					data-key="rzp_live_gf9KYFvtlMFjXm"
					data-amount=<?php echo $result_data['amount']; ?>
					data-currency=<?php echo $result_data['currency']; ?>
					data-order_id=<?php echo $result_data['orderid']; ?>
					data-buttontext="Pay with Razorpay"
					data-name="astromart"
					data-description=""
					data-image="http://129.154.235.38/mschool/assets/img/logo.png"
					data-prefill.name=<?php echo $pay_name; ?>
					data-prefill.contact=<?php echo $pay_mobile; ?>
                    data-prefill.email=<?php echo $pay_email; ?>
					data-theme.color="#F37254"
				></script>
				<input type="hidden" custom="Hidden Element" name="hidden">
			<input type="hidden" custom="Hidden Element" name="name" value="<?php echo $pay_name; ?>">
			<input type="hidden" custom="Hidden Element" name="email_id" value="<?php echo $pay_email; ?>">
			<input type="hidden" custom="Hidden Element" name="mobile_no" value="<?php echo $pay_mobile; ?>">
			<input type="hidden" custom="Hidden Element" name="product_ids" value="<?php echo $product_ids; ?>">
			<input type="hidden" custom="Hidden Element" name="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" custom="Hidden Element" name="signId" value="<?php echo $signId; ?>">
			<input type="hidden" custom="Hidden Element" name="for" value="<?php echo $for; ?>">
			<input type="hidden" custom="Hidden Element" name="ttaavail_id" value="<?php echo $avail_id; ?>">
			<input type="hidden" custom="Hidden Element" name="ttaavail_date" value="<?php echo $avail_date; ?>">
			<input type="hidden" custom="Hidden Element" name="ttaavail_time" value="<?php echo $avail_time; ?>">
			</form>
			
			<?php } else {
				$payment_html = '';
			}
		}
	} else if (isset($_REQUEST[$for . 'payment_method']) && $_REQUEST[$for . 'payment_method'] == 'dubilling') {
		$url = "http://52.77.82.47/sm_dipl/gateway/astro_sub.php";
		$post_data = array(
			'msisdn' => $pay_country.$pay_mobile,
			'keyword' => 'SUB AM',
			'mode' => 'wap',
			'validity' => '1'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($result, true);
		//console(LOG_LEVEL_INFO,"billing - astro response :" . var_export($response, true));
		// if ($response) {
			$url = API_URL;
			$post_data = array(
				'oauth' => '7ff7c3ed4e791da7e48e1fbd67dd5b72',
				'action' => 'dubilling_payment_update',
				'user_id' => $user_id,
				'for' => $for,
				'product_ids' => $product_ids,
				'signId' => $signId,
				'source' => 'web',
				'billing_response' => $response,
			);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
			$result1 = json_decode($result, true);

			if ($result1['returncode'] == "200") {
				header('Location: ' . APPLICATION_URL . 'gateway/payment-status.php?status=success');
			} else {
				header('Location: ' . APPLICATION_URL . 'gateway/payment-status.php?status=error');
			}
		// }
		// else{
		// 	//header('Location: ' . APPLICATION_URL . 'gateway/payment-status.php?status=error');
		// }
	}
}
// }
	

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
	<?php if (isset($_REQUEST[$for . 'payment_method'])&& $_REQUEST[$for.'payment_method']=='razorpay') {?>
$( document ).ready(function() {
    $('.razorpay-payment-button').click();
});
	<?php }?>

</script>