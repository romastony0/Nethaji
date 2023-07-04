<?php 
include("includes/header.php");
?>
	
<main class="flex-shrink-0" style="margin-bottom:25rem" > 
    <section class="profile-banner" id="profile">
      <div class="container">
        <div class= "container card shadow-lg">
		<div class="header d-flex align-items-center"> 
		<div class="highlight"> Change Password </div> 
		</div>
		<div class="card-body" id="choose_plan">			
			<div class=" row d-flex align-items-center justify-content-center">
			<div class="col-md-5"><img src="./assets/images/profile/profile-astro-img.png" alt="" title=""  class="img-fluid"/>
				</div>
				<div class="col-md-7" >		
					<form class="row g-3 needs-validation" novalidate id="subscription">
					  <div>
						<label for="reset-password" class="form-label">Password</label>
						<input type="password" class="form-control" id="reset-password" required>
						<div class="invalid-feedback"> Enter valid password .</div>
					  </div> 
					  <div>
						<label for="reset-confirm-password" class="form-label">Confirm Password</label>
						<input type="password" class="form-control" id="reset-confirm-password" required>
						<div class="invalid-feedback"> Enter valid password .</div>
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
		</div>
     
    </section> 
	
</main>
 <?php include("includes/footer.php");?>
