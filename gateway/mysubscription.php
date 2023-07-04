<?php 
	include("includes/header.php");
	
	if(isset($_SESSION['user_id']) && $_SESSION['user_id']!=''){

	$post_data456 = array(
				'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
				'action' 		=> "get_mysubs",
				'user_id'		=> $_SESSION['user_id']
				);
	$mysub_response 		= curl_hit($post_data456);
	$mysub_data = $mysub_response['returndata'];
	// print_r($_SESSION);
	//echo '<pre>';print_r($response123);exit;
}
	
	
?>
	
	<input type="hidden" name="user_id" id="unsub_user_id" value="<?php echo $_SESSION['user_id']?>">
<main class="flex-shrink-0" style="margin-bottom:20rem"> 
    <section class="profile-banner" id="profile">
      <div class="container">
        <div class= "container card shadow-lg">
		<div class="header d-flex align-items-center"> 
				<a href="profile.php" class="me-5"> Profile </a> 
				<div class="highlight">My Subscription</div> 
			</div>
		<div class="card-body">			
			<div class="row d-flex align-items-center justify-content-center">
				<div class="col-md-5"><img src="./assets/images/profile/profile-astro-img.png" alt="" title=""  class="img-fluid"/>
				</div>
				<?php if($mysub_response['returncode']=='200'){ ?>
				<div class="col-md-7" id="subscription" style="overflow:scroll;height:400px;">
					<div class="row">
					<?php foreach($mysub_data as $val){ 
							$bg = 'bg3';
						if($val['product_category'] == 'zodiac'){
							$category = 'Horoscope';
							$bg = 'bg1';
						}elseif($val['product_category'] == 'aaq'){
							$category = 'Ask a Question';
							$bg = 'bg2';
						}
					?>
					<?php if($val['date_diff']>=0){ ?>
					  <div class="col-sm-6">
						<div class="card">
						  <div class="card-body <?php echo $bg; ?>">
							<h5 class="card-title"><?php echo $category.' '.$val['product_name'].' '.$val['content_type']; ?></h5>
							<p class="card-text">Valid Remaining days</p>
							<a class="btn btn-primary"><?php echo $val['date_diff']; ?> Days</a>
							<button class="btn btn-danger unsub_purchase" data-pur_id="<?php echo $val['purchase_id']; ?>">UnSub</button>
						  </div>
						</div>
					  </div>					  
					<?php } ?>
					<?php } ?>
					</div>
					
					<!--<div class="row">
					 
					  <div class="col-sm-6">
						<div class="card">
						  <div class="card-body bg2">
							<h5 class="card-title">Ask a Question</h5>
							<p class="card-text">Valid Remaning  days</p>
							<a href="#" class="btn btn-primary">5 Days</a>
						  </div>
						</div>
					  </div>
					</div>

					<div class="row">
					  <div class="col-sm-6">
						<div class="card">
						  <div class="card-body bg3">
							
							<p class="card-text">Valid Remaning days</p>
							<a href="#" class="btn btn-primary">11 Days</a>
						  </div>
						</div>
					  </div>					
					</div>	-->					
					
				</div>
				</div>
				<?php }else{ ?>
					<div class="col-md-7" id="subscription">
					<div class="row"><h3 style="color:#4C08A4;">Yet to subscribe</h3>
					</div>
				<?php } ?>
			
		</div>
		</div>
     
    </section> 
	
</main>
    
<?php 
	include("includes/footer.php");
?>