<?php 
session_start();

include("includes/header.php");
$post_data = array(
'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
'user_id' 		=> $_SESSION['user_id'],
'action' 		=> "get_profile",
'session_code' 	=> $_SESSION['session_code']
);

$response = curl_hit($post_data);

$name		= $response['returndata']['user_name'];
$birth_place		= $response['returndata']['birth_place'];
$zodiac_sign 		= $response['returndata']['zodiac_sign'];
$email 		= $response['returndata']['email_id'];
$msisdn 		= $response['returndata']['msisdn'];
$image_path = $response['returndata']['image_path'];
$image_path = $image_path == "" ? "./assets/images/profile/avatar.png" : $image_path;

?>
<style>
#profile .card .card-body {
	padding:1rem !important;
}
</style>
	
		<!-- Main  -->
		 <main class="flex-shrink-0 container mb-5">
		   <h3 class=" py-3">Change Password</h3>
		   
		   <section id="change-password" class="pt-3">
			 <div class="container">
				<div class="col-md-6" >		
					<form class="row g-3 needs-validation" novalidate id="subscription">					  
					  <div>
						<label for="reset-password" class="form-label">New Password</label>
						<input type="password" class="form-control" id="reset-password" required>
						<div class="invalid-feedback"> Enter new password .</div>
					  </div> 
					  <div>
						<label for="reset-confirm-password" class="form-label">Confirm Password</label>
						<input type="password" class="form-control" id="reset-confirm-password" required>
						<div class="invalid-feedback"> confirm new password .</div>
					  </div> 
					  <small>Password is case sensitive, it should be with 5-10 alphanumeric characters</small>
					  <div class="text-start">
						<button class="btn btn-primary btn-lg reset_password_btn" type="submit" id="chg-password">Submit</button>
					  </div>
					</form>					
					<div class="text-center pass-success">
						<i class="fa fa-circle-check fa-3x text-success"></i>
						<h5 class="py-3">Password Changed !</h5>
						<p> Your Password hasbeen changed Successfully </p>				
						<a href="index.html" class="btn btn-primary btn-lg">Back to Home</a>
					</div>
				</div>			
			 </div>
		   </section>
		 </main>

	<!-- Main End //-->
	
    <!-- FOOTER -->
    <?php include("includes/footer.php");?>	