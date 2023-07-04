<?php 
include("includes/header.php");


$post_data = array(
			'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
			'action' 		=> "get_astrologer_details");
$response 		= curl_hit($post_data);
$astrologer_details = $response['returndata'];

$post_data = array(
			'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
			'user_id' 		=> $_SESSION['user_id'],
			'action' 		=> "get_profile");
$response 		= curl_hit($post_data);
$get_profile = $response['returndata'];

$post_data = array(
			'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
			'action' 		=> "get_language");
$lang_response 		= curl_hit($post_data);
$get_language = $lang_response['returndata'];
//echo '<pre>';print_r($astrologer_details);exit;

?>
	<form action="#" class="tta_payment_details" name="tta_payment_details" method="post">
		<input type="hidden" id="tta_avail_id" name="tta_avail_id" value="<?php echo '';?>">
		<input type="hidden" id="tta_avail_date" name="tta_avail_date" value="<?php echo '';?>">
		<input type="hidden" id="tta_avail_time" name="tta_avail_time" value="<?php echo '';?>">
		<input type="hidden" id="tta_user_id" name="tta_user_id" class="" value="<?php echo $_SESSION['user_id']; ?>">	
		<input type="hidden" id="tta_stuname" name="tta_stuname" class="" value="<?php echo $get_profile['user_name']; ?>">	
		<input type="hidden" id="tta_stutelephone" name="tta_stutelephone" class="tta_mobileId" value="<?php echo $get_profile['msisdn']; ?>">
		<input type="hidden" id="tta_countrycode" name="tta_countrycode" class="tta_countrycode" value="<?php echo $get_profile['country_code']; ?>">	
		<input type="hidden"  id="mob_no_ses" value="<?php echo $_SESSION['mobile_no']; ?>">
		
		<input type="hidden" id="tta_email_address" name="tta_email_address" class="" value="<?php echo $get_profile['email_id']; ?>">	
		<input type="hidden" id="tta_product_ids" name="tta_product_ids" class="" value="15">	
		<input type="hidden" name="for_payment" id="for_payment">
		<input type="hidden" name="tta_payment_method" class="paymethod">

		
	</form>
		<!-- Main  -->
		 <main class="flex-shrink-0 container mb-5">
		   <h3 class=" py-3">Talk to Astrologer </h3>
		   <div class="d-flex bd-highlight">
			 <div class="p-2 flex-grow-1">
			   <h4 class=" pt-1">Choose your Astrologer </h4>
			 </div>
			 <div class="p-2">
			   <select name="sort" class="form-select mb-3 filter_menu float-end lang_filter" id="filter_menu">
				 <option value="" selected=""> Sort By </option>
				 <?php foreach($get_language as $val){ ?>
					<option value="<?php echo $val['language']; ?>"><?php echo $val['language']; ?></option>
				 <?php } ?>
			   </select>
			 </div>
			 <div class="p-2">
			   <select name="filter" class="form-select mb-3 filter_menu float-end qual_filter" id="filter_menu">
				 <option value="" selected=""> Filter </option>
				 <option value="Professional">Professional</option>
				 <option value="Beginner">Beginner</option>
			   </select>
			 </div>
			 <div class="p-2">
			    <input type="text" class="form-control astro_search" id="searchId" placeholder="Search">
			 </div>
			 
		   </div>
		   <section id="talk-astro" class="pt-3">
			 <div class="container">
			   <div class="row row-cols-1 row-cols-md-4 g-4 astro_list">
			  
			   <?php foreach($astrologer_details as $val){ 
					if($val['image_path']==''){
						$val['image_path'] = APPLICATION_URL.'gateway/assets/images/profile/avatar.png';
					}
			   ?>
				 <div class="col">
				   <div class="card h-100">
					 <div class="card-top align-items-center d-flex">
					   <div class="avatar">
						 <img src="<?php echo $val['image_path']; ?>" width="100%" height="200" alt="Astrologer consultation online">
					   </div>
					   <div class="name-content">
						 <h5 class="cust-name astroname" > <?php echo $val['ast_name']; ?></h5>
						 <div>
						   <img src="./assets/images/icons/ic-language.svg" alt="Language" title="Language" width="16" height="16" class="me-1"> <?php echo $val['language']; ?>
						 </div>
						 <div>
						   <img src="./assets/images/icons/rupee-indian.png" alt="Rupee" title="Rupee" width="16" height="16" class="me-1"> <?php echo $val['method']; ?>
						 </div>
					   </div>
					 </div>
					 <div class="card-body d-flex justify-content-between align-items-center">
					   <div class="float-start">
						 <ul>
						   <li>
							 <i class="fa fa-graduation-cap"></i> <?php echo $val['experience']; ?> Years
						   </li>
						   <li>
							 <i class="fa fa-thumbs-up"></i> 198
						   </li>
						 </ul>
					   </div>
					   <div class="float-end text-center">
						 <div class="d-flex justify-content-center align-items-center">
						   <div data-bs-toggle="modal" data-bs-target="#astroAppoinment" class="astro_call" data-astro_id="<?php echo $val['ast_id']; ?>" data-astro_name="<?php echo $val['ast_name']; ?>" data-astro_language="<?php echo $val['language']; ?>" data-astro_method="<?php echo $val['method']; ?>" data-astro_experience="<?php echo $val['experience']; ?>" data-astro_img="<?php echo $val['image_path']; ?>"  > Call <a>
							   <img src="./assets/images/icons/phone_icon.png" width="30px">
							 </a>
						   </div>
						 </div>
					   </div>
					 </div>
				   </div>
				 </div>
			   <?php } ?>
				
			   </div>
			 </div>
		   </section>
		 </main>

	<!-- Main End //-->
	
 <?php include("includes/footer.php"); ?>
 <?php  if(isset($_GET['status']) && $_GET['status']=='success') { ?>
 <script>
	$(window).on('load', function() {
        $('#astroAppoinment').modal('show');
        $('.astroAppoinment_info_parent').hide(); 
		$('#astroAppoinment-thankyou').show();
    });
	

 </script>

 <?php } ?>
 
 
 <script>
 
 $(".lang_filter, .qual_filter").change(function() 
{
	
	event.preventDefault();
	
	var lang = $(".lang_filter").val();
	var qualification = $(".qual_filter").val();
	//alert(lang);
	
	 var formData ={  'astro_lang': lang,'qualification':qualification,"action": 'get_astrologer_details', source: 'WEB', oauth: '7ff7c3ed4e791da7e48e1fbd67dd5b72' };
			$.ajax({
				type: "POST",
				url: API_URL,
				data: JSON.stringify(formData),
				dataType: 'json',
				success: function(response) {
					
					
				if (response.returncode == 200)
				{
					//alert(response);
					$('.astro_list').html('');
					
					var astro_det = response.returndata;
					$.each(astro_det, function(i, item) 
					{
						//alert(item.image_path);
						
						if(item.image_path == null){
							item.image_path = APPLICATION_URL+'gateway/assets/images/profile/avatar.png';
						}
						
						$('.astro_list').append('<div class="col"><div class="card h-100"><div class="card-top align-items-center d-flex"><div class="avatar"><img src="'+item.image_path+'" width="100%" height="200" alt="Astrologer consultation online"></div><div class="name-content"><h5 class="cust-name astroname" > '+item.ast_name+'</h5><div><img src="./assets/images/icons/ic-language.svg" alt="Language" title="Language" width="16" height="16" class="me-1"> '+item.language+'</div><div><img src="./assets/images/icons/rupee-indian.png" alt="Rupee" title="Rupee" width="16" height="16" class="me-1">'+item.method+'</div></div></div><div class="card-body d-flex justify-content-between align-items-center"><div class="float-start"><ul><li><i class="fa fa-graduation-cap"></i> '+item.experience+' Years</li><li><i class="fa fa-thumbs-up"></i> 198</li></ul></div><div class="float-end text-center"><div class="d-flex justify-content-center align-items-center"><div data-bs-toggle="modal" data-bs-target="#astroAppoinment" class="astro_call" data-astro_id="'+item.ast_id+'" data-astro_name="'+item.ast_name+'" data-astro_language="'+item.language+'" data-astro_method="'+item.method+'" data-astro_experience="'+item.experience+'" data-astro_img="'+item.image_path+'" > Call <a ><img src="./assets/images/icons/phone_icon.png" width="30px"></a></div></div></div></div></div></div>');
					});
					
				}
				else{
					 //swal('', response.returnmessage, 'warning');
					 $('.astro_list').html('');
				} 
				
				},
			}); 
});	
 
 </script>