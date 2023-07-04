<?php
error_reporting(0);
session_start();
date_default_timezone_set("Asia/Kolkata");

include("head.php");
include("./configuration.php");

$post_data = array(
	'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
	'user_id' 		=> $_SESSION['user_id'],
	'action' 		=> "get_zodiac_signs"
);
$response 		= curl_hit($post_data);
$zodiac_signs = $response['returndata'];
$current_page =  basename($_SERVER['PHP_SELF']);
if ($_SESSION['user_role'] == 'astrologer') {
	$home_href = "";
} else {
	$home_href = "home.php";
}

?>
<script>
	var API_URL = '<?php echo API_URL; ?>';
	var APPLICATION_URL = '<?php echo APPLICATION_URL; ?>';
</script>

<body class="d-flex flex-column h-100">
	<?php
	$for = $_REQUEST['for_payment'];
	//print_r($_REQUEST);
	// if(isset($_REQUEST[$for.'payment_method'])&&$_REQUEST[$for.'payment_method']=='razorpay'){

	if (isset($_REQUEST[$for . 'payment_method'])) {
		$payment_html = '';
		if (isset($_REQUEST[$for . 'user_id'])) {

			$user_id = $_SESSION['user_id'];
			$pay_name = $_REQUEST[$for . 'stuname'];
			$pay_email = $_REQUEST[$for . 'email_address'];
			//$pay_mobile = $_REQUEST[$for . 'stutelephone'].$_REQUEST[$for . 'countrycode'];
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
							.razorpay-payment-button {
								display: none;
							}
						</style>
						<form action="<?php echo APPLICATION_URL; ?>gateway/includes/success_page.php" method="POST" id="checkout">
							<script src="https://checkout.razorpay.com/v1/checkout.js" data-key="rzp_live_gf9KYFvtlMFjXm" data-amount=<?php echo $result_data['amount']; ?> data-currency=<?php echo $result_data['currency']; ?> data-order_id=<?php echo $result_data['orderid']; ?> data-buttontext="Pay with Razorpay" data-name="Astromart" data-description="" data-image="https://astromarts.com/astromart/gateway/assets/images/logo-astro-new.png" data-prefill.name=<?php echo $pay_name; ?> data-prefill.contact=<?php echo $pay_mobile; ?> data-prefill.email=<?php echo $pay_email; ?> data-theme.color="#F37254"></script>
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
				if ($product_ids == 'weekly' || $product_ids == 'prev week' || $product_ids == 'next week') {
					$validity = 7;
				} else if ($product_ids == 'monthly' || $product_ids == 'prev month' || $product_ids == 'next month') {
					$validity = 30;
				} else if ($product_ids == 'daily' || $product_ids == 'prev day' || $product_ids == 'next day') {
					$validity = 1;
				}
				$post_data = array(
					'msisdn' => '971' . $pay_mobile,
					'keyword' => 'SUB AM',
					'mode' => 'WEB',
					'validity' => $validity,
				);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				curl_close($ch);
				/* echo 'mNisha watch';
				echo $result;
				echo 'mNisha close';
				exit(); */
				$response = json_decode($result, true);
				//console(LOG_LEVEL_INFO,"billing - astro response :" . var_export($response, true));
				//	if ($response['returncode']==200) {
				$url = API_URL;
				$horo_array = array("Aries" => "1", "Taurus" => "2", "Gemini" => "3", "Cancer" => "4", "Leo" => "5", "Virgo" => "6", "Libra" => "7", "Scorpio" => "8", "Sagittarius" => "9", "Capricornus" => "10", "Aquarius" => "11", "Pisces" => "12");
				$_SESSION['horo_id'] = $horo_array[$signId];
				$_SESSION['horo_name'] = $signId;
				$_SESSION['horo_type'] = $product_ids;
				$_SESSION['avd'] = $avail_date;
				$_SESSION['avt'] = $avail_time;
				$post_data = array(
					'oauth' => '7ff7c3ed4e791da7e48e1fbd67dd5b72',
					'action' => 'dubilling_payment_update',
					'user_id' => $user_id,
					'for' => $for,
					'product_ids' => $product_ids,
					'signId' => $signId,
					'source' => 'web',
					'billing_response' => $response,
					'avail_date' => $avail_date,
					'avail_time' => $avail_time,
					'avail_id' => $avail_id,
					'trans_id' => $trans_id
				);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				curl_close($ch);
				$result1 = json_decode($result, true);

				if ($result1['returncode'] == 200) {
					if ($for == 'tta_') {
						//header('Location: '.APPLICATION_URL.'gateway/talk.php?status=success&for=tta_&avd='.$_REQUEST['ttaavail_date'].'&avt='.$_REQUEST['ttaavail_time'].'');
						header('Location: ' . APPLICATION_URL . 'gateway/talk.php?status=success');
					} else if ($for == 'horo_') {
						header('Location: ' . APPLICATION_URL . 'gateway/payment-status.php?status=success&for=horoscope');
					} else if ($for == 'aaq_') {
						header('Location: ' . APPLICATION_URL . 'gateway/payment-status.php?status=success&for=aaq');
					}
				} else {
					header('Location: ' . APPLICATION_URL . 'gateway/payment-status.php?status=error');
				}
				/* }
				 else{
				     header('Location: ' . APPLICATION_URL . 'gateway/payment-status.php?status=error');
				} */
			}
		}
	} ?>
	<?php if (isset($_REQUEST[$for . 'payment_method']) && $_REQUEST[$for . 'payment_method'] == 'razorpay') { ?>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script>
			$(document).ready(function() {
				$('.razorpay-payment-button').click();
			});
		</script>
	<?php } ?>
	<!-- Header Start -->
	<header>
		<nav class="navbar navbar-expand-sm navbar-dark bg-primary">
			<div class="container">
				<a class="navbar-brand" href="<?php echo $home_href; ?>">
					<img src="./assets/images/logo.jpg" alt="" title="">
				</a>
				<?php if ($_SESSION['user_role'] != '' && $_SESSION['user_role'] == 'astrologer') { ?>

					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse justify-content-md-end navbar-collapse" id="mynavbar">
						<ul class="navbar-nav">
							<li class="nav-item">
								<a class="nav-link" href="astro-profile.php">My Profile</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="astro-appoinments.php">My Appoinments</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="astro-change-password.php">Change Password</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="logout.php">Logout</a>
							</li>
						<?php } else { ?>
							<?php if (!empty($_SESSION['session_code'])) { ?>

								<!-- Profile Dropdown -->

								<div class="dropdown profile-icon">
									<a class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
										<img src="<?php if ($_SESSION['image_path'] == "")
														echo "./assets/images/profile/avatar.png";
													else {
														echo $_SESSION['image_path'];
													} ?>" alt="mdo" width="32" height="32" class="rounded-circle profile_image">
									</a>
									<ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
										<li><a class="dropdown-item" href="profile.php">My Profile</a></li>
										<li><a class="dropdown-item" href="mysubscription.php">My Subscription</a></li>
										<li><a class="dropdown-item" href="my-astro-answer.php">My astro Answers</a></li>
										<li><a class="dropdown-item" href="talk_to_astrologer.php">Talk to Astrologer</a></li>
										<li><a class="dropdown-item" href="#">Premium Plans</a></li>
										<li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
										<li>
											<hr class="dropdown-divider">
										</li>
										<li><a class="dropdown-item" href="logout.php">Sign out</a></li>
									</ul>
								</div> <!-- Profile Dropdown // -->

							<?php } ?>
							<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
								<span class="navbar-toggler-icon"></span>
							</button>
							<div class="collapse justify-content-md-end navbar-collapse" id="mynavbar">
								<ul class="navbar-nav">
									<li class="nav-item">
										<a class="nav-link" href="horoscope.php">Horoscope</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="aaq.php">Ask a Question</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="talk.php">Talk to Astrologer</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="tarot.php">Tarot</a>
									</li>
									<?php if (empty($_SESSION['session_code'])) { ?>
										<li class="nav-item">
											<a class="nav-link signin_btn" data-bs-toggle="modal" data-bs-dismiss="modal">Signin</a>
										</li>
										<li class="nav-item">
											<a class="nav-link text-danger register_btn" data-bs-toggle="modal" href="#exampleModalToggle">Join Astromart</a>
										</li>
								<?php }
								} ?>

								</ul>
							</div>
					</div>
		</nav>
	</header>
	<!-- Header End-->

	<?php if ($current_page != 'my-astro-answer.php' && $current_page != 'payment-status.php' && $current_page != 'change_password.php'  && $current_page != 'profile.php' && $current_page != 'mysubscription.php'  && $current_page != 'talk_to_astrologer.php' && $current_page != 'privacy_policy.php' && $current_page != 'terms_of_use.php') { ?>
		<!-- Hero Banner Start -->
		<section class="hero-banner" id="hero">
			<div class="container">
				<div class="row align-items-center justify-content-center">
					<div class="col-md-3">
						<img src="./assets/images/home/astro-circle.png" alt="" title="" class="img-fluid astro-circle">
					</div>
					<div class="col-md-6 text-white text-center single-item">
						<!--<div>
		  <h4> 25% Discount will End on 30th Nov 2022 </h4>
		  <h2 class="text-warning"> only On Personalised Yearly Prediction 2023 </h2>
		  <h4>A report to must have with you </h4>
		</div>
		<div>
		  <h4> 25% Discount will End on 30th Nov 2022 </h4>
		  <h2 class="text-warning"> only On Personalised Yearly Prediction 2023 </h2>
		  <h4>A report to must have with you </h4>
		</div>-->
						<div>
							<h4 class="text-warning"> Find out what your zodiac sign has for you and bring a positive change to your life with a personalised prediction! </h4>
						</div>
						<div>
							<h4 class="text-warning"> Find out what your zodiac sign has for you and bring a positive change to your life with a personalised prediction! </h4>
						</div>
					</div>
					<!-- Calender part Start -->
					<?php if ($current_page == 'home.php') { ?>
						<div class="col-md-3">
							<div class="toggle-calendar mt-3"></div>
							<div class="box"></div>
						</div>
					<?php } ?>
					<!-- Calender End -->

				</div>
			</div>
		</section>
		<!-- Hero Banner  End-->
	<?php } ?>

	<!-- zodiac Scroller-->
	<?php if ($current_page == 'home.php' || $current_page == 'horoscope.php') { ?>
		<h3 class="text-center pt-3">Select Your <span class="spl-text"> Zodiac </span> Sign </h3>
		<div id="Zodiac" class="slide-section pb-3">
			<div class="container">
				<section class="zodiac slider">
					<?php foreach ($zodiac_signs as $val) { ?>
						<div><a href="horoscope-content.php?product_id=<?php echo $val['product_id']; ?>&product_name=<?php echo $val['product_name']; ?>#daily_auto"><img src="./assets/images/home/astro-icons/<?php echo $val['image_name']; ?>" alt="" class="img-fluid"></a></div>
					<?php } ?>
				</section>
			</div>
		</div>
	<?php } ?>