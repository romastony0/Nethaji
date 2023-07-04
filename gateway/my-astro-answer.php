<?php include("includes/header.php");


if(isset($_SESSION['user_id']) && $_SESSION['user_id']!=''){

	$post_data123 = array(
				'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
				'action' 		=> "get_doubts",
				'user_id'		=> $_SESSION['user_id']
				);
	$response123 		= curl_hit($post_data123);
	$aad_ans_data = $response123['returndata'];
	//echo '<pre>';print_r($aad_ans_data);exit;
}
//echo '<pre>';print_r($response123);echo '<pre>';print_r($aad_ans_data);exit;
?>
	
	
<main class="flex-shrink-0" style="margin-bottom:25rem" > 
	<section class="profile-banner" id="profile">
	<div class="container">
		<div class= "container card shadow-lg">
			<div class="header d-flex align-items-center"> 
			<div class="highlight">My Astro Answers </div> 
			<a href="talk_to_astrologer.php" class="ms-5">Talk to Astrologer</a>
			</div>
			
			<div class="card-body">			
				<div class="row d-flex align-items-center justify-content-center">
					<div class="col-md-5"><img src="./assets/images/profile/profile-astro-img.png" alt="" title=""  class="img-fluid"/>
					</div>
					<?php if($response123['returncode']==200 && $aad_ans_data!='nodata'){ ?>
					<div class="col-md-7" id="subscription" style="overflow:scroll;height:400px;">
						<div class="row">
							<?php foreach($aad_ans_data as $ans){  ?>
							<div class="card">
								<?php if($ans['ques_status']=='published'){ ?>
								<div class="card-body bg1">
									<div class="d-flex justify-content-between">
										<small class="text-end"> Posted On: <?php echo date("Y/m/d",strtotime($ans['post_date'])); ?>  </small>
										<small class="badge bg-success"><i class="fa fa-check" ></i>  Answered </small>
									</div>							
									<h5 class="card-title"><?php echo $ans['question_title']; ?></h5>						
									<hr/>							
									<p><?php 
									
									echo $ans['answer_content'];
									/* if(strlen($ans['answer_content'])<235){
										echo $ans['answer_content']; 
									}else{
										echo substr($ans['answer_content'],0,235);
									} */
									?></p>
									<p class="moretext">   </p>
									<button class="moreless-button btn btn-primary">Read more</button>

								</div>
								<?php }elseif($ans['ques_status']=='') { ?>

								<div class="card-body bg3">
									<div class="d-flex justify-content-between">
										<small class="text-end"> Posted On:  <?php echo date("Y/m/d",strtotime($ans['post_date'])); ?>  </small>			  
										<small class="badge bg-info"><i class="fa fa-check" ></i>  Awaiting </small>
									</div>							
									<h6 class="card-title pt-2"><?php echo $ans['question_title']; ?></h6>
								</div>

								<?php } ?>
							</div>

							<?php } ?>			
						</div>


					</div>
					<?php }else{ ?>
						<div class="col-md-7" id="subscription">
							<div class="row"><h3 style="color:#4C08A4;">Yet to raise questions</h3>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	</section> 

</main>
    
	  
	
<?php include("includes/footer.php");?>

<script type="text/javascript">
	
/***More less*******/
	 $('.moreless-button').click(function() {
	  $('.moretext').slideToggle();
	  if ($('.moreless-button').text() == "Read more") {
		$(this).text("Read less")
	  } else {
		$(this).text("Read more")
	  }
	});
</script>