<input type="hidden" name="mobile_no" id="mobile_no" value="<?php echo $_SESSION['mobile_no']; ?>">
<input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id']; ?>">
<input type="hidden" name="for" id="for">

<!-- Register Modal Start -->
<div class="modal fade" data-bs-backdrop="static" id="register" aria-hidden="true" aria-labelledby="registerLabel" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="registerLabel">Join AstroMart</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="g-3 needs-validation" id="register_form" novalidate autocomplete="off">
					<div class="row">
						<div class="col">
							<label for="nameId" class="form-label">Name </label>
							<input type="text" class="form-control nameId" id="nameId" onkeydown="return /[a-z]/i.test(event.key)">
							<span style="color:#dc3545" class="reg-name-error-message cd-error-message"></span>
						</div>
						<div class="col">
							<label for="emaiId" class="form-label">Email </label>
							<input type="email" class="form-control emaiId" id="emaiId1">
							<span style="color:#dc3545" class="reg-email-error-message cd-error-message"></span>
						</div>
					</div>
					<div class="row my-2">
						<div class="col-4 col-md-2 mb-3">
							<select name="countrycode" id="countrycode" class="form-select selectpicker mt-inputs countrycode" style="margin-top: 34px;">
								<option value="" disabled>Select</option>
								<option value="91" selected>+91</option>
								<option value="971">+971</option>
							</select>
						</div>
						<div class="col-8 col-md-5">
							<label for="mobileId" class="form-label">Phone<span style="color:red;">*</span></label>
							<input type="tel" class="form-control mobileId" id="mobileId1" maxlength="10" onkeypress="return onlyNumberKey(event)">
							<span style="color:#dc3545" class="reg-mobileno-error-message cd-error-message"></span>
						</div>
						<div class="col col-md-5">
							<label for="passwordId" class="form-label">Password</label>
							<input type="password" class="form-control passwordId" id="passwordId1" required />
							<small>
								Password is case sensitive, it should be within 5 -10 alphanumeric characters.
								No SPL characters allowed.
							</small>
							<span style="color:#dc3545" class="reg-password-error-message cd-error-message"></span>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col">
							<label for="date" class="form-label">Date of Birth</label>
							<input class="form-control dobId" id="date" name="date" placeholder="MM/DD/YYY" type="text" data-max_date="<?php echo date('Y-m-d'); ?>" />
							<span style="color:#dc3545" class="reg-dob-error-message cd-error-message"></span>
						</div>
						<div class="col">
							<label for="placeId" class="form-label">Place of Birth </label>
							<div class="input-group">
								<div class="input-group-text">
									<i class="fa fa-map-marker"></i>
								</div>
								<input type="text" class="form-control placeId" id="placeId1">
							</div>
							<span style="color:#dc3545" class="reg-pob-error-message cd-error-message"></span>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col">
							<label for="genderId" class="form-label">Gender</label>
							<select class="form-select genderId" id="genderId1" required>
								<option selected disabled>Choose...</option>
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
							<span style="color:#dc3545" class="reg-gender-error-message cd-error-message"></span>
						</div>
						<div class="col">
							<label for="signId" class="form-label">Sign</label>
							<select class="form-select signId" id="signId1" required>
								<option selected disabled>Choose...</option>
								<option value="Aries">Aries</option>
								<option value="Taurus">Taurus</option>
								<option value="Gemini">Gemini</option>
								<option value="Cancer">Cancer</option>
								<option value="Leo">Leo</option>
								<option value="Virgo">Virgo</option>
								<option value="Libra">Libra</option>
								<option value="Scorpio">Scorpio</option>
								<option value="Sagittarius">Sagittarius</option>
								<option value="Capricornus">Capricornus</option>
								<option value="Aquarius">Aquarius</option>
								<option value="Pisces">Pisces</option>
							</select>
							<span style="color:#dc3545" class="reg-sign-error-message cd-error-message"></span>
						</div>
					</div>
					<div class="row mt-3">
						<div class="d-grid">
							<button class="btn btn-primary btn-block btn-lg register_button" data-for="" id="register_button" type="submit">Click to Join</button>
						</div>
						<div class="text-center pt-2">
							Already have an account?
							<a href="#" data-bs-target="" data-bs-toggle="modal" data-bs-dismiss="modal" class="signin_btn"> <strong> SignIn </strong>
							</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Register Modal End -->

<!--OTP Modal Start-->
<div class="modal fade" data-bs-backdrop="static" id="otp_form" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalToggleLabel3">Verify OTP</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- OTP part -->
				<div class="loginform otp" id="otp" for="register">
					<h5 class="text-center pt-3">Enter the OTP sent to your Mobile Number <br /> <span id="mobilenumber"></span> </h5>
					<form class="row mt-5 gy-2 gx-3 align-items-center" autocomplete="off">
						<input type="text" class="form-control otp" id="otp1" maxlength="1" onkeypress="return onlyNumberKey(event)">
						<input type="text" class="form-control otp" id="otp2" maxlength="1" onkeypress="return onlyNumberKey(event)">
						<input type="text" class="form-control otp" id="otp3" maxlength="1" onkeypress="return onlyNumberKey(event)">
						<input type="text" class="form-control otp" id="otp4" maxlength="1" onkeypress="return onlyNumberKey(event)">
						<button type="submit" class="btn btn-primary btn-block btn-lg mt-4 otp_submit">Verify</button>
						<div class="resend" id="resend_proceed"> Time Left : <span id="time" class="btn">00</span>
							<a class="resend_otp" id="resend_otp_click" href="">Resend</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!--OTP Modal end-->

<!-- Sign In modal Start -->
<div class="modal fade" data-bs-backdrop="static" id="signin" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalToggleLabel2">SignIn</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class=" needs-validation" novalidate autocomplete="off">
				
					<div class="row my-3">
						<label for="mobileId" class="form-label">Phone </label>
						<div class="col-4">
							<select name="countrycode" id="countrycode" class="form-select selectpicker mt-inputs countrycode_signin">
								<option value="" disabled>Select</option>
								<option value="91" selected>+91</option>
								<option value="971">+971</option>
							</select>
						</div>
						<div class="col-8">

							<input type="tel" class="form-control mobileId" id="moboremailId" maxlength="10" onkeypress="return onlyNumberKey(event)">
							<span style="color:#dc3545" class="reg-mobileno-error-message cd-error-message"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label for="passwordId" class="form-label">Password</label>
							<input type="password" class="form-control" id="idpassword" required>
							<span style="color:#dc3545" class="signin-pwd-error-message cd-error-message"></span>
						</div>
					</div>




					<div class="py-2">
						<input type="checkbox" class="form-check-input" id="remember-me">
						<label class="form-check-label " id="remember-me" for="check">Remember me</label>
					</div>
					<div class="d-grid">
						<button class="btn btn-primary btn-block btn-lg sign_btn" type="submit">SignIn</button>
					</div>
					<div class="text-center pt-2">
						<a href="#" class="text-decoration-underline signin_forgot_pwd"> Forgot password ? </a>
						<br />
						Don't have an account?
						<a href="#" data-bs-target="" data-bs-toggle="modal" data-bs-dismiss="modal" class="register_btn"> <strong>SignUp </strong>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Sign In modal End -->

<!-- Forgot Password Modal Start
<div class="modal fade" id="forgot_password" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
	  <div class="modal-content" >
		<div class="modal-header">
		  <h5 class="modal-title" id="forgot">Forgot Password</h5>
		  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
		  <form class="row g-3 needs-validation" novalidate>
			<div>
			  <label for="emaiId" class="form-label">Mobile / Email</label>
			 
			  <input type="email" class="form-control"  id="reset_mobno" required>
			  <span  style="color:#dc3545" class="reset-mob-error-message cd-error-message"></span>
			</div>
			<div class="d-grid">
			  <button class="btn btn-primary btn-block btn-lg forgot_password_btn" type="submit">Submit</button>
			</div>
		  </form>
		</div>
	  </div>
	</div>
</div>-->
<!-- Forgot Password Modal End-->
<!-- Forgot Password Modal Start-->
<div class="modal fade" data-bs-backdrop="static" id="forgot_password" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="forgot">Forgot Password</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="row g-3 needs-validation" novalidate autocomplete="off">
					<div class="row">
						<label for="emaiId" class="form-label">Mobile / Email</label>
						<div class="col-sm-3">
							<select name="countrycode" id="countrycode" class="form-select selectpicker mt-inputs countrycode_forgot">
								<option value="" disabled>Select</option>
								<option value="91" selected>+91</option>
								<option value="971">+971</option>
							</select>
						</div>
						<div class="col-md-9">

							<input type="email" class="form-control" id="reset_mobno" maxlength="10">
							<span style="color:#dc3545" class="reset-mob-error-message cd-error-message"></span>
						</div>
					</div>
					<div class="d-grid">
						<button class="btn btn-primary btn-block btn-lg forgot_password_btn" type="button">Get OTP</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Forgot Password Modal End-->
<!-- ==== 3 RESET PASSWORD  ======== -->
<div class="modal fade " data-bs-backdrop="static" id="reset_password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="forgot">Reset Password</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="row g-3 needs-validation" novalidate autocomplete="off">
					<div>
						<label for="reset-password" class="form-label">Password</label>
						<input type="password" class="form-control" id="reset-password" required>
						<span class="reset-pwd-error-message cd-error-message"></span>
					</div>
					<div>
						<label for="reset-confirm-password" class="form-label">Confirm Password</label>
						<input type="password" class="form-control" id="reset-confirm-password" required>
						<span class="reset-confirm-pwd-error-message cd-error-message"></span>
					</div>
					<p>Password is case sensitive, it should be with 5-10 alphanumeric characters</p>
					<div class="d-grid">
						<button class="btn btn-primary  btn-lg reset_password_btn" type="button">Reset Password</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- ==== 3 RESET PASSWORD  end ======== -->


<!--Panchangam modal -->
<div class="modal fade" data-bs-backdrop="static" id="panchang_date" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"> Panchangam </h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="row gy-2 gx-3 align-items-center" autocomplete="off">
					<label for="colFormLabelSm" class="col-form-label col-form-label-sm">Date</label>
					<input class="form-control" id="panchagam_date" name="date_panchang" placeholder="MM/DD/YYY" type="text" />
					<div class="col-auto">
						<input class="btn btn-warning submit-btn" type="button" onclick="get_panchang_details();" value="GET PANCHANGAM">
					</div>
				</form>
			</div>
		</div>
	</div>
</div> <!-- panchang //  -->


<!--Panchangam modal -->
<div class="modal fade" data-bs-backdrop="static" id="panchang_location" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"> Panchang </h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="row gy-2 gx-3 align-items-center" autocomplete="off">
					<div class="col-sm-12 mb-3 mt-3 ">
						<label for="colFormLabelSm" class="col-form-label col-form-label-sm">Location</label>
						<div class="mt-2">
							<div class="input-group">
								<div class="input-group-text">
									<i class="fa fa-map-marker"></i>
								</div>
								<select class="form-select" id="panchang_location" required>
									<option selected disabled>Choose...</option>
									<?php foreach ($panchang_location_data as $val) { ?>
										<option value="<?php echo $val['language_name']; ?> " data-location_name="<?php echo $val['location_name']; ?>"><?php echo $val['location_name']; ?> </option>
									<?php } ?>

								</select>
							</div>
						</div>
					</div>
					<div class="col-auto">
						<input class="btn btn-warning submit-btn " type="button" value="GET PANCHANG" onclick="get_panchang_details();">
					</div>
				</form>
			</div>
		</div>
	</div>
</div> <!-- panchang //  -->
<!-- ==== password change SUCCESS Message ======== -->
<div class="modal fade" data-bs-backdrop="static" id="forgot_password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header" style="border:none;">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body text-center">
				<div class="text-center"> <i class="fa fa-circle-check fa-3x text-success"></i></div>
				<h5 class="py-3">Password Changed !</h5>
				<p> Your Password hasbeen changed Successfully </p>
				<div class="d-grid">
					<button class="btn btn-primary btn-block btn-lg" type="submit">Back to Home</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- ==== password change error Message ======== -->

<!-- Choose Your Plan start -->
<form action="#" class="horo_payment_details register_form" name="horo_payment_details" method="post" autocomplete="off">
	<input type="hidden" name="action" value="process">
	<input type="hidden" name="horo_guest" value="guest">
	<input type="hidden" id="horo_user_id1" name="horo_user_id" class="" value="<?php echo $_SESSION['user_id']; ?>">
	<?php $_SESSION['buy-price'] = 1; ?>
	<input type="hidden" name="horo_discountval" id="discountval" value="">
	<input type="hidden" name="horo_branch" value="<?php echo ''; ?>">
	<input type="hidden" name="horo_payment_method" class="paymethod">
	<input type="hidden" name="horo_pincode" value="<?php echo ''; ?>">
	<input type="hidden" name="horo_address_2" value="<?php echo ''; ?>">
	<input type="hidden" name="horo_address_1" value="<?php echo ''; ?>">
	<input type="hidden" name="horo_city" value="<?php echo ''; ?>">
	<input type="hidden" name="horo_country" value="<?php echo 'India'; ?>">
	<input type="hidden" name="horo_state" value="<?php echo ''; ?>">
	<input type="hidden" name="horo_landmark" value="<?php echo ''; ?>">
	<input type="hidden" name="horo_currency" value="<?php echo 'INR'; ?>">
	<input type="hidden" name="horo_grandtotalval" id="grandtotalval" value="1">
	<input type="hidden" name="horo_inside" value="YES">
	<input type="hidden" name="for_payment" id="for_payment">
	<input type="hidden" name="horo_enc_id" value="cHlSQXczODVweUQrUDVDZzhTbW16UT09">
	<div class="modal fade" data-bs-backdrop="static" id="choosePlan" aria-hidden="true" aria-labelledby="choosePlanleLabel" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="choosePlanleLabel">Choose Your Plan</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body" id="choose_plan">
					<form class="g-3 needs-validation" novalidate autocomplete="off">
						<div class="row mt-3">
							<div class="col">
								<label for="genderId" class="form-label">Choose Plan</label>
								<select class="form-select horo_planId planId" id="planId" name="horo_product_ids" required>
									<option selected disabled value="">-Select One-</option>
									<option value="weekly">Weekly</option>
									<option value="monthly">Monthly</option>
									<option value="yearly">Yearly</option>
								</select>
								<span style="color:#dc3545" class="reg-plan-error-message cd-error-message"></span>
							</div>
							<div class="col">
								<label for="signId" class="form-label">Sign</label>
								<select class="form-select horo_signId signId" id="signId" name="horo_signId" required>
									<option selected disabled value="">-Select One-</option>
									<option value="Aries">Aries</option>
									<option value="Taurus">Taurus</option>
									<option value="Gemini">Gemini</option>
									<option value="Cancer">Cancer</option>
									<option value="Leo">Leo</option>
									<option value="Virgo">Virgo</option>
									<option value="Libra">Libra</option>
									<option value="Scorpio">Scorpio</option>
									<option value="Sagittarius">Sagittarius</option>
									<option value="Capricornus">Capricornus</option>
									<option value="Aquarius">Aquarius</option>
									<option value="Pisces">Pisces</option>
								</select>
								<span style="color:#dc3545" class="reg-sign-error-message cd-error-message"></span>
							</div>
						</div>
						<div class="row mt-3">
							<div class="col">
								<label for="nameId" class="form-label">Name </label>
								<input type="text" name="horo_stuname" class="form-control horo_nameId" id="nnnn" required>
								<span style="color:#dc3545" class="reg-name-error-message cd-error-message"></span>
							</div>
							<div class="col">
								<label for="emaiId" class="form-label">Email </label>
								<input type="email" name="horo_email_address" class="form-control horo_emaiId emaiId" id="horo_emaiId" required>
								<span style="color:#dc3545" class="reg-email-error-message cd-error-message"></span>
							</div>
						</div>

						<div class="row mt-3">
							<div class="col">
								<label for="dob" class="form-label">Birth Date</label>
								<div class="input-group">
									<input class="form-control horo_dobId" id="dobId" name="horo_date_of_birth" data-max_date=<?php echo date("Y-m-d"); ?> placeholder="yyyy-mm-dd" type="text" />
								</div>
								<span style="color:#dc3545" class="reg-dob-error-message cd-error-message"></span>
							</div>

							<div class="col">
								<label for="dob" class="form-label">Birth Time</label>
								<input class="form-control horo_tobId" type="time" id="tob3" name="time">
								<!-- <input class="form-control" type="text" id="tob" placeholder="hh:mm:ss"/> -->
								<!--<div class="input-group">				    
					  <select class="form-select me-2" id="hh" required>
						<option selected disabled value="">HH</option>
						<option value="1">1</option>
						<option value="2">2</option>
					  </select>
					  <select class="form-select me-2" id="mm" required>
						<option selected disabled value="">MM</option>
						<option value="1">1</option>
						<option value="2">2</option>
					  </select>
					  <select class="form-select me-2" id="year" required>
						<option selected disabled value="">SS</option>
						<option value="1">00</option>
						<option value="2">01</option>
					  </select>
					  <select class="form-select" id="year" required>
						<option selected disabled value="">AM</option>
						<option value="am">AM</option>
						<option value="pm">PM</option>
					  </select>
					  <div class="invalid-feedback"> Please provide a valid birth time.</div>
					</div>-->
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-md-6">
								<div class="row">
									<div class="col">
										<label for="placeId" class="form-label">Place of Birth </label>
										<div class="input-group">
											<div class="input-group-text"><i class="fa fa-map-marker"></i></div>
											<input type="text" class="form-control horo_placeId placeId" name="horo_pob" id="placeId" value="Chennai, Tamil Nadu, India">
										</div>
										<span style="color:#dc3545" class="reg-pob-error-message cd-error-message"></span>
									</div>
									<div class="col">
										<label for="gradeid" class="form-label">Gender </label>
										<div class="input-group">
											<select class="form-select horo_genderId genderId" id="gender" name="horo_gender" required>
												<option selected disabled value="">Gender</option>
												<option value="Male">Male</option>
												<option value="Female">Female</option>
											</select>
										</div>
										<span style="color:#dc3545" class="reg-gender-error-message cd-error-message"></span>
									</div>
								</div>
							</div>
							<div class="col" id="country_code">
								<div class="row">
									<div class="col">
										<label for="phoneId" class="form-label">Phone </label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-5 col-4">
										<select name="horo_countrycode" id="countrycode" class="form-select selectpicker mt-inputs horo_countrycode">
											<option value="" disabled>Select</option>
											<option value="91" selected>+91</option>
											<option value="971">+971</option>
										</select>
									</div>
									<div class="col-md-7 col-8">
										<input type="tel" class="form-control horo_mobileId mobileId" name="horo_stutelephone" required maxlength="10" onkeypress="return onlyNumberKey(event)">
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div style="color:#dc3545" class="reg-mobileno-error-message cd-error-message"></div>
									</div>
								</div>

							</div>
						</div>
						<div class="text-center">
							<button class="btn btn-primary btn-block btn-lg mt-3" type="button" data-for="horo_" id="payment-btn">Subscribe Now</button>
						</div>
					</form>
</form>
</div>
</div>
</div>
</div>
</div>

<!-- Choose Your Plan end-->
<!-- Choose a plan PayMent start-->

<div class="modal fade" data-bs-backdrop="static" id="choosePlanpayment" aria-hidden="true" aria-labelledby="choosePlanpayment" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="choosePlanleLabel">Choose Your Plan</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body text-center" id="plan_payment">
				<h5 class="py-3"> Your Astromart subscription is on the way !</h5>
				<div class="paln_info d-flex align-items-center justify-content-between">
					<h3 class="text-white package">Horoscope Monthly </h3>
					<h5 class="text-primary pricepoint">@AED1/Day+vat</h5>
				</div>

				<div class=" d-flex align-items-center justify-content-between my-3" id="payment_option">
					<div class="payment-button card" data-paymethod="razorpay">
						<a><img src="assets/images/Razorpay-logo.png" alt="" class="img-fluid"></a>
					</div>
					<div class="payment-button card ms-3" data-paymethod="dubilling">
						<a> <img src="assets/images/du-payment-logo.png" alt="" class="img-fluid"></a>
					</div>
				</div>
				<button class="btn btn-primary btn-lg billing_redirection"> Proceed to Pay</button>
			</div>
		</div>
	</div>


</div>
</div>
</div>
<!-- Choose a plan PayMent end-->




<!-- Sign In modal Start -->
<div class="modal fade" data-bs-backdrop="static" id="non_register" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<i class="fa-solid fa-arrow-left close_password"></i>
				<h5 class="modal-title">&nbsp SignIn</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="row g-3 needs-validation" novalidate autocomplete="off">
					<div>
						<label for="passwordId" class="form-label">Password</label>
						<input type="password" class="form-control popup_password" id="passwordId" required>
						<small>
							Password is case sensitive, it should be within 5 -10 alphanumeric characters.
							No SPL characters allowed.
						</small>
						<span style="color:#dc3545" class="reg-password-error-message cd-error-message"></span>
					</div>
					<div class="d-grid">
						<button class="btn btn-primary btn-block btn-lg register_button" id="register_button12" data-for="" type="button">Register</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Sign In modal End -->

<!--====== Astro Book an appoinment modal ======-->
<div class="astro_book_appoinment">
	<div class="modal fade" id="astroAppoinment" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="astroAppoinment" tabindex="-1">
		<div class="modal-dialog modal-dialog-top modal-xl ">
			<div class="modal-content bg-white">
				<div class="modal-header border-0">
					<h4 class="modal-title book_app" id="astroAppoinment"><i class="fa fa-phone"> </i> Book an Appoinment</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

					<!--== Astro Details ==-->

					<div class="astroAppoinment_info_parent">
						<div class="astroAppoinment_info">
						</div>
						<div class="slot_info_parent">
							<div class="slot_info">
								<h5 class="pt-3"> Available Timings : </h5>
								<hr />
								<div class="py-4">
									<div class="pb-3 morning_show" style="display:none">
										<p>Morning : </p>
										<div class="morning"></div>
									</div>
									<div class="py-3 evening_show" style="display:none">
										<p>Evening :</p>
										<div class="evening"></div>
									</div>
								</div>
								<div class="text-center"><button class="btn btn-primary btn-lg book_appoinment">Book an Appoinment </button></div>
							</div>
						</div>
					</div>


					<div id="astroAppoinment-confirm" class="text-center">
						<div class="card bg-info p-5">
							<h4 class="book_message"> </h4>
						</div>
						<div class="text-center py-3">
							<button class="btn btn-light btn-lg">Cancel</button>
							<button class="btn btn-primary btn-lg book_app_confirm">Confirm</button>
						</div>
					</div>


					<div id="astroAppoinment-thankyou" class="text-center">
						<div class="card bg-primary text-white p-5">
							<h4> Thank you for your Confirmation ! </h4>
							<h5>Astrologer will call you on</h5>
							<h4 class="pt-2"> <mark> <?php echo $_SESSION['avd']; ?> - <?php echo $_SESSION['avt']; ?> </mark> </h4>
						</div>

					</div>

				</div>
			</div>
		</div>
	</div>
</div><!-- End Astro Book an appoinment //-->