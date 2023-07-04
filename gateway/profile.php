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
<main class="flex-shrink-0" style="margin-bottom:20rem"> 
    <section class="profile-banner" id="profile">
      <div class="container">
        <div class= "container card shadow-lg ">
		<div class="header d-flex align-items-center"> 
			<div class="highlight"> Profile </div> 				
			<a href="mysubscription.php" class="ms-5"> My Subscription</a> 
		</div>
		<div class="card-body">			
			<div class="row d-flex align-items-center justify-content-center">
				<div class="col-md-5"><img src="./assets/images/profile/profile-astro-img.png" alt="" title=""  class="img-fluid"/>
				</div>
				
				<div class="col-md-7">
					<div class="avatar text-center mb-5">
						<img src="<?php echo $image_path ;?>" alt="" title="" class="avatar-img profile_image" >
						<form class="login100-form validate-form" method="post" enctype="multipart/form-data" id="pro_pic_upload_form">
                            <a href="#"><img src="./assets/images/profile/photo-camera.png" alt="profile">
							<input type="file" id="wizard-picture" name="profileimage"> </a>
							</form>
					
						<h5 class="pt-2"><?php echo $name ;?></h5>
					</div>
					<div class="row g-3">
					  <div class="col">
						<input type="text" class="form-control pob" id="profile_pob" placeholder="Place of Birth" value="<?php echo $birth_place ;?>" disabled >
					  </div>
					  <div class="col">
						<input type="text" class="form-control sign" id="profile_sign" placeholder="Zodiac Sign" value="<?php echo $zodiac_sign ;?>" disabled>
					  </div>					  
					</div>
					
					<div class="row g-3 mt-2">
					  <div class="col">
						<input type="email" class="form-control email" id="profile_email" placeholder="Email" value="<?php echo $email ;?>" disabled>
					  </div>
					  <div class="col">
						<input type="tel" class="form-control msisdn" id="profile_msisdn" placeholder="Mobile Number"  value="<?php echo $msisdn ;?>" disabled>
					  </div>					  
					</div><br> 
					<img src="./assets/images/update_btn.png"  width="150px" style=" display: block;margin-left: auto;margin-right: auto;" id="profile_update_btn">
					
					
					<img src="./assets/images/save_profile_btn.png"  width="150px" style=" display: block;margin-left: auto;margin-right: auto;visibility: hidden;" id="profile_save_btn">
					
				</div>
			</div>
		</div>
		</div>
     
    </section> 
	
</main>
    
	  
	
	
   <?php include("includes/footer.php");?>	
   
	<script type="text/javascript">
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function() {
        'use strict'
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms).forEach(function(form) {
          form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }
            form.classList.add('was-validated')
          }, false)
        })
      })()

    </script>
	
  </body>
</html>