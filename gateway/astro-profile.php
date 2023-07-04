<?php 
session_start();

include("includes/header.php");
$post_data = array(
'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
'astro_id' 		=> $_SESSION['my_astro_id'],
'action' 		=> "fetch_astro_available_slots",
'avl_date' 		=> date('Y-m-d'),
'session_code' 	=> $_SESSION['session_code']
);

$response = curl_hit($post_data);

$returndata = $response['returndata'];

$astroprofile_post_data = array(
'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
'user_id' 		=> $_SESSION['user_id'],
'action' 		=> "get_profile",
'session_code' 	=> $_SESSION['session_code']
);

$astroprofile_response = curl_hit($astroprofile_post_data);

if($astroprofile_response['returndata']['role'] == 'astrologer'){
$name		= $astroprofile_response['returndata']['ast_name'];
$birth_place		= $astroprofile_response['returndata']['birth_place'];
$zodiac_sign 		= $astroprofile_response['returndata']['zodiac_sign'];
$email 		= $astroprofile_response['returndata']['ast_email'];
$msisdn 		= $astroprofile_response['returndata']['ast_msisdn'];
$image_path = $astroprofile_response['returndata']['image_path'];
$image_path = $image_path == "" ? "./assets/images/profile/avatar.png" : $image_path;
//echo '<pre>';print_r($_SESSION);echo '<pre>';print_r($astroprofile_response);

$main_lang = array('Tamil','English','Hindi','Telugu','Bengali');
$language = explode(',',$astroprofile_response['returndata']['language']);
$method 		= $astroprofile_response['returndata']['method'];
$experience 		= $astroprofile_response['returndata']['experience'];

foreach($main_lang as $val){
	$ast_lan[$val]['status']='unknown';
	foreach($language as $lval){		
		if($val == trim($lval)){
			$ast_lan[$val]['status']='known';
		}
	}
}

//echo '<pre>';print_r($ast_lan);exit;
}

?>
<style>
#profile .card .card-body {
	padding:1rem !important;
}

a.disabled {
  pointer-events: none;
  cursor: default;
}
</style>
	
		<!-- Main  -->
		 <main class="flex-shrink-0 container mb-5">
			<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
			  <li class="nav-item" role="presentation">
				<button class="nav-link active" id="pills-profile" data-bs-toggle="pill" data-bs-target="#pills-profile-tab" type="button" role="tab" aria-controls="pills-profile" aria-selected="true"> <h5> My Profile</h5></button>
			  </li>
			  <li class="nav-item" role="presentation">
				<button class="nav-link" id="pills-availability" data-bs-toggle="pill" data-bs-target="#pills-availability-tab" type="button" role="tab" aria-controls="pills-availability" aria-selected="false"><h5> My Availablity </h5> </button>
			  </li>
			</ul>
		   <section class="pt-3">
			 <div class="container">
			 
		
		
		<div class="tab-content" id="pills-tabContent">
				<!-- Astro profile start-->
			<div class="tab-pane fade show active pt-5" id="pills-profile-tab" aria-labelledby="pills-profile">
			   <div class="row">
		
				 <div class="col-md-4" id="astro-profile">
				  <div class="text-center pb-3 avatar">
						 <img src="<?php echo $image_path ;?>" width="200px" height="200px" alt="Astrologer consultation online" class="rounded-circle shadow border border-5">
						 <form class="login100-form validate-form" method="post" enctype="multipart/form-data" id="pro_pic_upload_form">
                            <a href="#"><img src="./assets/images/profile/photo-camera.png" alt="profile">
							<input type="file" id="wizard-picture" name="profileimage"> </a>
							</form>
							
						 <h4> Astro <?php echo $name; ?> </h4>
					   </div>
				 </div>
				 
				 <div class="col-md-8">
					<div class="d-flex align-items-center flex-wrap lang-group">					
						<div> <i class="fa fa-language fa-2x me-3"></i> </div>
						
						<?php foreach($ast_lan as $lkey=>$lval){ 
						
						if($lval['status']=='known'){
							$cls='btn-primary';
						}else{
							$cls='btn-light';
						}
							
						?>
							<a class="btn <?php echo $cls; ?> btn-sm me-3 astro_lang disabled" data-ast_lng="<?php echo $lkey; ?>" > <?php echo $lkey; ?></a>
						
								
						<?php 
						} ?>
						
						
					</div>
					
					<div class="py-5">
						<div class="row g-3">						
						  <div class="col-auto">
							<div class="input-group">
							  <div class="input-group-text"><i class="fa fa-asterisk"></i>  </div>
							  <input type="text" class="form-control astr_method" id="astro_method" placeholder="Vedic, Numerology" value="<?php echo $method; ?>" disabled>
							</div>	
						</div>	
						<div class="col-auto">
							<div class="input-group">
							  <div class="input-group-text"><i class="fa fa-graduation-cap"></i>  </div>
							  <input type="text" class="form-control astr_exp" id="astro_exp" placeholder="Five Years" value="<?php echo $experience; ?>" disabled>
							</div>
						</div>						  					  
						</div>
						
						<div class="row g-3 mt-2">
							<div class="col-auto">
								<div class="input-group">
								  <div class="input-group-text"><i class="fa fa-envelope"></i>  </div>
								  <input type="email" class="form-control astr_email" id="astro_email" placeholder="Email" value="<?php echo $email; ?>" disabled>
								</div>
							</div>						
							<div class="col-auto">
								<div class="input-group">
								  <div class="input-group-text"><i class="fa fa-phone"></i>  </div>
								  <input type="tel" class="form-control astr_msisdn" id="astro_msisdn" placeholder="Mobile Number" value="<?php echo $msisdn; ?>" disabled>
								</div>
							</div>						 				  
						</div>
						<div class="text-center py-5">
							<button class="btn btn-light btn-lg me-3" id="astro_profile_update_btn">Edit</button>
							<button class="btn btn-primary btn-lg" id="astro_profile_save_btn">Save</button>
						</div>					
					</div>
				 </div>
			   </div> 
			 </div><!-- end profile tab//-->
			   
			   
			   <div class=" row tab-pane fade" id="pills-availability-tab" role="tabpanel" aria-labelledby="pills-availability">
				<!--== Astro Details Tab ==-->
				<input type="hidden" class="astro_id" value="<?php echo $_SESSION['my_astro_id'];?>">
				  <div id="astroAppoinment-info">
				  <h5 class="pt-3"> Available Dates : </h5>	
					<hr/>		  
				  <!-- Date Selection -->
				  <?php 
				  $day = date('w');
				  $weekstart = date('Y-m-d');
				  $weekend = date('Y-m-d', strtotime('+6 days'));?>
					<div class="astro_appoinment_date flex-wrap d-flex flex-row justify-content-start align-items-center py-4">
						<?php $j=1; for($i = $weekstart; $i <= $weekend && $j<=7; $i = date("Y-m-d",strtotime($i.'+1 days'))){?>
						<div class="p-2 d-flex flex-column justify-content-center align-items-center datebox">
							<div class="date_select_availability <?php if ($i == date("Y-m-d"))
								echo "active";
							else
								echo "deactive";?>" data-date="<?php echo $i;?>">
							<span <?php if (date("D", strtotime($i)) == 'Sun') {echo 'class="text-danger"';}?>><?php echo date("D",strtotime($i));?></span>
							<h4><?php echo date("j",strtotime($i));?></h4>
							<span><?php echo date("M",strtotime($i));?></span>
							</div>
						</div>
						<?php }?>
						
					</div> <!-- end Date Selection //-->
					<?php 
								$morning=array();
								$evening=array();
								foreach ($returndata as $key => $value) {
									if($value['slot_id']<25){
										$morning[$key]=$value;
									}
									else{
										$evening[$key]=$value;
									}
								}?>
					<div class="show_timings">
						<h5 class="pt-3"> Available Timings : </h5>
						<hr/>
						<div class="py-4"> 
							<div class="pb-3">
								
								<p>Morning : </p>	
								<div class="morning_slot">		
									<?php foreach($morning as $key=>$value){?>
											<a class="btn btn-sm <?php if ($value['status'] == 'available') {
												echo "btn-success avl_timings_booked";
											} else {
												echo "btn-light avl_timings";
											}?>" style="margin-right:10px;margin-bottom:10px" data-slot_id='<?php echo $value['slot_id'];?>'><?php echo $key;?></a>
									<?php }?>
								</div>
							</div>
							<div class="py-3">
								<p>Evening :</p>
								<div class="evening_slot">			
								<?php foreach($evening as $key=>$value){?>
											<a class="btn btn-sm <?php if ($value['status'] == 'available') {
												echo "btn-success avl_timings_booked";
											} else {
												echo "btn-light avl_timings";
											}?>" style="margin-right:10px;margin-bottom:10px" data-slot_id='<?php echo $value['slot_id'];?>'><?php echo $key;?></a>
									<?php }?>
								</div>	
							</div>
						</div>
						<div class="text-center">
							<button class="btn btn-primary btn-lg save_appoinment" data-date="<?php echo date("Y-m-d");?>">Save an Appoinment </button>
						</div>
					</div>
				  </div>
				</div>  <!-- Details Tab end //-->
			   </div>
			   </div>
			   
			   
			 </div>
		   </section>
		 </main>
		 <?php include("includes/footer.php");?>	

		 <script>
			$('.date_select_availability').click(function(){
				$('.date_select_availability').removeClass('active');
				$('.date_select_availability').addClass('deactive');
				$(this).removeClass('deactive');
				$(this).addClass('active');
				var date = $(this).data('date');
				$('.save_appoinment').data('date',date);
				var astro_id = $('.astro_id').val();
				var formData = { 'astro_id': astro_id,'action': 'fetch_astro_available_slots','avl_date':date, oauth: '7ff7c3ed4e791da7e48e1fbd67dd5b72' };
				$.ajax({
					type: "POST",
					url: API_URL,
					data: JSON.stringify(formData),
					dataType: 'json',
					success: function(result)
					{
						$('.show_timings').show();
						// console.log(result.returndata);
						var astro_avail_date_timing = result.returndata;
						$('.morning_slot').html('');
						$('.evening_slot').html('');
						$.each(astro_avail_date_timing, function(i, item) 
						{
							if(item.slot_id < 25 )
							{												
								if(item.status=='available')
								{
									$('.morning_slot').append('<a class="btn btn-success btn-sm avl_timings_booked" style="margin-right:10px;margin-bottom:10px" data-slot_id='+item.slot_id+' data-tta_avail_date = '+date+'>'+i+'</a>'); 
								}
								else
								{	
									$('.morning_slot').append('<a class="btn btn-light btn-sm avl_timings" style="margin-right:10px;margin-bottom:10px" data-slot_id='+item.slot_id+' data-tta_avail_date = '+date+'  >'+i+'</a>');
								}
							}
							else
							{
								if(item.status=='available')
								{
									$('.evening_slot').append('<a class="btn btn-success avl_timings_booked btn-sm" style="margin-right:10px;margin-bottom:10px" data-slot_id='+item.slot_id+' data-tta_avail_date = '+date+'>'+i+'</a>'); 
								}
								else
								{
									$('.evening_slot').append('<a class="btn btn-light btn-sm avl_timings" style="margin-right:10px;margin-bottom:10px" data-slot_id='+item.slot_id+' data-tta_avail_date = '+date+'  >'+i+'</a>');
								}
							}
							// console.log(i);
							// console.log(item.slot_id);
							// console.log(item.status);
						});
					}
				});
				// alert(date);
			});

			$(".show_timings").on("click", ".avl_timings", function()
			{
				
				if( $(this).hasClass('btn-success'))
				{
					$(this).removeClass('btn-success');
					$(this).addClass('btn-light');
					
				}
				else if( $(this).hasClass('btn-light'))
				{
					$(this).removeClass('btn-light');
					$(this).addClass('btn-success');
					;
				}
				
			});	
			$(".show_timings").on("click", ".save_appoinment", function()
			{
				var astro_id = $('.astro_id').val();
				
				var slot_id='';

				$( ".avl_timings.btn-success" ).each(function( index ) {
					slot_id += $(this).data('slot_id')+",";
				});
				
				var avail_date = $(this).data('date');		
				var formData = { 'astro_id': astro_id,'action': 'insert_astrologer_availability','avail_date':avail_date,'slot_id':slot_id, oauth: '7ff7c3ed4e791da7e48e1fbd67dd5b72' };
				$.ajax({
					type: "POST",
					url: API_URL,
					data: JSON.stringify(formData),
					dataType: 'json',
					success: function(result)
					{
						location.reload();
					}
				});
				// alert(avail_date);
				// alert(slot_id);		
			});	
			
			$('#astro_profile_save_btn').hide();
				
			$("#astro_profile_update_btn").click(function(event){
			   event.preventDefault();
			   
			   $('.astr_method').removeAttr("disabled");
			   $('.astr_exp').removeAttr("disabled");
			   $('.astr_email').removeAttr("disabled");
			   $('.astr_msisdn').removeAttr("disabled");
			   $('.astro_lang').removeClass('disabled');
			   
			   $('#astro_profile_update_btn').hide();
			   $('#astro_profile_save_btn').show();
			   
			});

			$(".astro_lang").click(function() 
			{
				//alert('hii');
				if( $(this).hasClass('btn-primary'))
				{
					$(this).removeClass('btn-primary');
					$(this).addClass('btn-light');
					
				}
				else if( $(this).hasClass('btn-light'))
				{
					$(this).removeClass('btn-light');
					$(this).addClass('btn-primary');
					;
				}
			});

			$('#astro_profile_save_btn').on('click', function(event)
			{
				event.preventDefault();
				 
				//if(validate()){
					var astro_method = $('#astro_method').val();
					var astro_exp = $('#astro_exp').val();
					var profile_email = $('#astro_email').val();
					var profile_msisdn = $('#astro_msisdn').val();
					
					var ast_lang1 = '';
					
					$( ".astro_lang.btn-primary" ).each(function( index ) {
						ast_lang1 += $(this).data('ast_lng')+", ";
						//alert($(this).data('ast_lng'));
					});

					$.post("profile_submit.php", {"astro_exp" : astro_exp,"astro_method":astro_method,"profile_email":profile_email,"profile_msisdn":profile_msisdn,"profile_lang":ast_lang1,source:'WEB',oauth:'7ff7c3ed4e791da7e48e1fbd67dd5b72'}, function(response){
						var res = $.parseJSON(response);
						if(res.returncode==200)
						{
							swal({title: "",text: "Your profile  has been updated",type: "success"}, function() 
							{
								window.location.reload();
							});
						}
						
					});	
				//} 
			});	
			</script>