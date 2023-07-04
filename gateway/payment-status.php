<?php 
include("includes/header.php");
?>
	
<main class="flex-shrink-0" style="margin-bottom:25rem" > 
    <section class="profile-banner" id="profile">
      <div class="container">
        <div class= "container card shadow-lg" style="margin-top: 5%;">
		<div class="header d-flex align-items-center"> 
		<div class="highlight"> Payment </div> 
		</div>
		<div class="card-body" id="choose_plan">			
			<div class=" row d-flex align-items-center justify-content-center">
			<div class="col-md-5"><img src="./assets/images/profile/profile-astro-img.png" alt="" title=""  class="img-fluid"/>
				</div>
				<div class="col-md-7" >		
                <div class="text-center">
				<?php if($_GET['status']=='success' && $_GET['for']=='tta_'){?>
						<i class="fa fa-circle-check fa-3x text-success"></i>
						<h5 class="py-3">Purchase Successful !</h5>		
						<p>Thank you for your Confirmation ! <br><br>
							Astrologer will call you on 
							<?php echo $_GET['avd']; ?>  -  <?php echo $_GET['avt']; ?> </p>
						<?php }else if($_GET['status']=='success'&&$_GET['for']=='horoscope'){?>
						<i class="fa fa-circle-check fa-3x text-success"></i>
						<h5 class="py-3">Purchase Successful !</h5>
						<p> Product has been purchased successfully </p>	
						<?php }else if($_GET['status']=='success'&&$_GET['for']=='aaq'){?>
							<i class="fa fa-circle-check fa-3x text-success"></i>
						<h5 class="py-3">Purchase Successful !</h5>
						<p> Your query has been submitted we will revert back soon. </p>		
						<?php }else{?>
							<i class="fa fa-circle-xmark fa-3x text-danger"></i>
						<h5 class="py-3">Purchase Failed !</h5>
						<p> Product not purchased </p>				
							<?php }?>

							<?php if($_GET['for']=="horoscope"&&$_GET['status']=='success'){?>
							<a href="horoscope-content.php?product_id=<?php echo $_SESSION['horo_id'];?>&product_name=<?php echo $_SESSION['horo_name'];?>&content_type=<?php echo $_SESSION['horo_type'];?>" class="btn btn-primary btn-lg">Go to Content</a>
							<?php }else{?>
								<a href="home.php" class="btn btn-primary btn-lg">Back to Home</a>
								<?php }?>
					</div>
					
			</div>
			
		</div>
		</div>
     
    </section> 
	
</main>
 <?php include("includes/footer.php");?>
