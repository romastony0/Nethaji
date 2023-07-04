<?php 
include("includes/header.php");


$post_data = array(
			'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
			'user_id' 		=> $_SESSION['user_id'],
			'action' 		=> "get_astrologerbooked_details");
$response 		= curl_hit($post_data);
$get_astro_booking_details = $response['returndata'];

?>
<main class="flex-shrink-0" style="margin-bottom:25rem" > 
    <section class="profile-banner" id="profile">
      <div class="container">
        <div class= "container card shadow-lg">
		<div class="header d-flex align-items-center"> 
				<a href="my-astro-answer.php" class="me-5">My Astro Answers</a> 
				 <div class="highlight"> Talk to Astrologer </div> 
		</div>
		<div class="card-body">			
			<div class="row d-flex align-items-center justify-content-center">
			<?php if($get_astro_booking_details=='nodata'){?>
			<div class="col-md-5"><img src="./assets/images/profile/profile-astro-img.png" alt="" title=""  class="img-fluid"/>
			</div><?php }?>
				<?php if($response['returncode']==200 && $get_astro_booking_details!='nodata'){ ?>				
				<div class="col-md-12" id="subscription" style="overflow:scroll;height:400px;">
					<div class="row">
					<?php foreach($get_astro_booking_details as $val){?>
					 
						<div class="card">
						  <div class="card-body bg1 d-flex justify-content-between">						 
							<small class="text-end"><?php echo $val['ast_name'];?> </small>	
							<small class="text-end"> Requested on <?php echo $val['date'];?> </small>								
						  	<!--<small class="badge bg-success"><i class="fa fa-check" ></i>  JOIN </small>-->
							<a class="badge bg-success" href="https://meetings.astromarts.com:3008/?type=join&ast=<?php echo $val['ast_id'];?>"> JOIN </a>
						  </div>
						</div>
					<?php }?>
					</div>
				</div>
				
				<?php }else{ ?>
							<div class="col-md-7" id="subscription">
							<div class="row"><h3 style="color:#4C08A4;">Your yet to book your appointment!</h3>
							</div>
						</div>
					<?php } ?>
			</div>
		</div>
		</div>
     
    </section> 
	
</main>
<?php include("includes/footer.php");?>