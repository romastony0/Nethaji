<?php 

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

//print_r($_REQUEST);

include("includes/header.php");

session_start();
$post_data = array(
			'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
			'action' 		=> "get_zodiac_signs");
$response 		= curl_hit($post_data);
$zodiac_signs = $response['returndata'];

//echo '<pre>';print_r($_SESSION);exit;

/* Array
(
    [user_id] => 75
    [user_mobile_no] => 9597942467
    [register_otp_cnt] => 0
    [mobile_no] => 9597942467
    [session_code] => KVGWYW9WI94W3JPAV3JF
    [splash_screen] => 1
) */

//echo '<pre>';print_r($zodiac_signs);exit;

if(isset($_SESSION['user_id']) && $_SESSION['user_id']!=''){

	$post_data = array(
				'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
				'action' 		=> "get_register_details",
				'user_id'		=> $_SESSION['user_id']
				);
	$response 		= curl_hit($post_data);
	$register_data = $response['returndata'];

	$uname = isset($register_data['user_name'])?$register_data['user_name']:'';
	$email_id = isset($register_data['email_id'])?$register_data['email_id']:'';
	$msisdn = isset($register_data['msisdn'])?$register_data['msisdn']:'';
	$DOB = isset($register_data['DOB'])?$register_data['DOB']:'';
	$TOB = isset($register_data['TOB'])?$register_data['TOB']:'';
	$birth_place = isset($register_data['birth_place'])?$register_data['birth_place']:'';
	$zodiac_sign = isset($register_data['zodiac_sign'])?$register_data['zodiac_sign']:'';
	$gender = isset($register_data['gender'])?$register_data['gender']:'';
	$country_code = isset($register_data['country_code'])?$register_data['country_code']:'';
}

//echo '<pre>';print_r($register_data);exit;



?>

 <input type="hidden" name="user_id" id="user_id" value = "<?php echo $_SESSION['user_id'];?>">
<link href= "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	
<!-- Mani  -->
<main class="flex-shrink-0 container mb-5"> 
  
	<section id="ask-questions">
	<div class="container">
	  <div class="justify-content-center pt-5">
		<h3>Ask a Question</h3> 
		<p>Enter your details to ask a question</p>
		<hr/>			
			<div class="pt-3" >
				<form action="#"  class="g-3 needs-validation payment_details register_form"  novalidate name="payment_details" method="post">		
				<input type="hidden" id="aaq_user_id1" name="aaq_user_id" class="" value="<?php echo $_SESSION['user_id']; ?>">
				<?php $_SESSION['buy-price']=1;?>
					<input type="hidden" name="aaq_discountval" id="discountval" value="">
					<input type="hidden" name="aaq_branch" value="<?php echo '';?>">
					<input type="hidden" name="aaq_payment_method" class="paymethod">
					<input type="hidden" name="aaq_pincode" value="<?php echo '';?>">
					<input type="hidden" name="aaq_address_2" value="<?php echo '';?>">
					<input type="hidden" name="aaq_address_1" value="<?php echo '';?>">
					<input type="hidden" name="aaq_city" value="<?php echo '';?>">
					<input type="hidden" name="aaq_country" value="<?php echo 'India';?>">
					<input type="hidden" name="aaq_state" value="<?php echo '';?>">
					<input type="hidden" name="aaq_landmark" value="<?php echo '';?>">
					<input type="hidden" name="aaq_currency" value="<?php echo 'INR';?>">
					<input type="hidden" name="aaq_grandtotalval" id="grandtotalval" value="1">
					<input type="hidden" name="aaq_inside" value="YES">
					<input type="hidden" name="for_payment" id="for_payment" value="">
					<input type="hidden" name="aaq_enc_id" value="cHlSQXczODVweUQrUDVDZzhTbW16UT09">
					<input type="hidden" name="aaq_trans_id" id="aaq_trans_id" value="">
					<input type="hidden"  id="mob_no_ses" value="<?php echo $_SESSION['mobile_no']; ?>">
				 <div class="row mt-3">
					
					<div class="col">
					  <label for="signId" class="form-label">Sign</label>
					  <select class="form-select aaq_signId" id="signId" name="aaq_signId" required>
						<option selected disabled value="">Select</option>
						<?php foreach($zodiac_signs as $val){ ?>
						<option value="<?php echo $val['product_id']; ?>" <?php if($zodiac_sign==$val['product_id'] || $zodiac_sign==$val['product_name'] ){ echo 'selected';}?>><?php echo $val['product_name']; ?></option>
						<?php } ?>
					  </select>
					  <div class="invalid-feedback invalid-sign"></div>
					</div>
					<div class="col">
					  <label for="nameId" class="form-label">Name </label>
					  <input type="text" name="aaq_stuname" class="form-control aaq_nameId" id="nameId" onkeydown="return /[a-z]/i.test(event.key)" value="<?php echo $uname; ?>"  <?php //if($uname!=''){ echo "disabled";} ?>>
					  <div class="invalid-feedback invalid-name"></div>
					</div>
				  </div>
				  <div class="row mt-3">
					
					<div class="col">
					  <label for="emaiId" class="form-label">Email </label>
					  <input type="email" name="aaq_email_address" class="form-control aaq_emaiId" id="emaiId" value="<?php echo $email_id; ?>"  <?php //if($email_id!=''){ echo "disabled";} ?>>
					  <div class="invalid-feedback invalid-email"> </div>
					</div>
					<div class="col">
						<label for="dob" class="form-label">Birth Date</label>
						<div class="input-group">				    
						 
						  <input class="form-control aaq_dobId" name="aaq_date_of_birth" id="aaq_date" placeholder="yyyy-mm-dd" type="text" value="<?php echo $DOB; ?>"  <?php //if($DOB!=''){ echo "disabled";} ?> data-max_date="<?php echo date('Y-m-d');?>" />
						  <div class="invalid-feedback invalid-dob"> </div>
						</div>
					</div>
					
				  </div>
		  
				  <div class="row mt-3">
					
					<div class="col">
						<label for="dob" class="form-label">Birth Time</label>
						<div class="input-group">				    
						
						  <!--<input class="form-control" type="text" id="tob" placeholder="hh:mm:ss"/>-->
						  <input  class="form-control left aaq_tobId" type="time" id="tob3" name="time" value="<?php echo $TOB; ?>">
						  <div class="invalid-feedback invalid-tob"> </div>
						</div>
					</div>
					<div class="col">
					  <label for="placeId" class="form-label">Place of Birth </label>
					  <div class="input-group">
					  
						<div class="input-group-text"><i class="fa fa-map-marker"></i></div>
						<input type="text" name="pob" class="form-control aaq_placeId" id="placeId" value="<?php echo $birth_place; ?>"  <?php //if($birth_place!=''){ echo "disabled";} ?> >  
					  </div>
					  <div class="invalid-feedback invalid-bplace">Please provide a valid area</div>
					</div>
				  </div>
				  
				  <div class="row mt-3">                				
					
					<div class="col-md-6">
						<label for="dob" class="form-label">Gender</label>
						<div class="input-group">					
						  <select class="form-select aaq_genderId" name="gender" id="gender" <?php //if($gender!=''){ echo "disabled";} ?>>
							<option selected disabled value="">Choose</option>
							<option value="Male" <?php if($gender=='Male'){ echo "selected";} ?> >Male</option>
							<option value="Female" <?php if($gender=='Female'){ echo "selected";} ?>>Female</option>
						  </select>
						</div>
						 <div class="invalid-feedback invalid-gender"></div>
							
					</div>	
					<div class="col-md-2 col-4 mgt">
						<label for="phoneId" class="form-label">Phone<span style="color:red;">*</span></label>
					 
					  <select name="countrycode" id="countrycode" class="form-select selectpicker mt-inputs aaq_countrycode" >
						<option value="" disabled>Select</option>
						<option value="91" <?php if($country_code=='91'){ echo "selected";} ?>>+91</option>
						<option value="971" <?php if($country_code=='971'){ echo "selected";} ?>>+971</option>
					</select>
					  				
				  </div>
				  <div class="col-md-4 col-8 mgt">
					  <!--<span>+971</span>--> <input type="tel" class="form-control aaq_mobileId" name="aaq_stutelephone" id="mobileNo" maxlength="10" onkeypress="return onlyNumberKey(event)" value="<?php echo $msisdn; ?>"  <?php //if($msisdn!=''){ echo "disabled";} ?> style="margin-top:32px">
					  <div class="invalid-feedback invalid-mobile"> </div>
					
				  </div>
				  
				  </div>
			 <div class="row mt-3 products"  style="display:none">  
				  <div class="col-md-6" >
					  <label for="productId" class="form-label">Product</label>
					  <select class="form-select" id="product_id" name="aaq_product_ids" required>
						<option selected disabled value="">Select</option>
						<option value="13">1 Question</option>
						<option value="14">3 Question</option>
					  </select>
					  <div class="invalid-feedback invalid-prod"></div>
					</div>
				</div>
				  <div class="py-5 text-center category_aaq "style="display:none">
					<div class="row justify-content-center align-items-center select-catagory" >
						  <div class="col fs-3 bg-white ">
							Choose your catagory
						  </div>
						  <div class="col p-0">
							<select class="form-select bg-info b-0 text-black fs-4 " id="aaq_category">
							<option selected>Area of Query</option>
								<option value="Job">Job</option>
								<option value="Business">Business</option>
								<option value="Finance">Finance</option>
								<option value="Love">Love</option>
								<option value="Marriage">Marriage</option>
								<option value="Health">Health</option>
							 </select>
							 
						  </div>
						  
						</div>	<div class="invalid-feedback invalid-category"></div>				
					</div>
					
					 <div id="showOne" class="catagory-items">		  
											
						
					  </div>

					  


				<div class="text-center" id='aaq_submit_btn'>
					<button class="btn btn-secondary btn-block btn-lg mt-3" type="reset" >Clear</button>
					<button class="btn btn-primary btn-block btn-lg mt-3" id="aaq_submit" type="button" >Proceed</button>
				</div>					
	  			<div class="text-center">
					<button class="btn btn-primary btn-block btn-lg mt-3" id="aaq_nonregister_submit" type="button"  >Submit</button>
				</div>
				

				</form>
			</div>
	  </div>
	  
	 
	  
	  
	  
	</div>
  </section>
</main>
	
 <?php include("includes/footer.php");?>
 
 <script src= "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
 <script src= "https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.7/dayjs.min.js"></script>
 <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js" integrity="sha512-2xXe2z/uA+2SyT/sTSt9Uq4jDKsT0lV4evd3eoE/oxKih8DSAsOF6LUb+ncafMJPAimWAXdu9W+yMXGrCVOzQA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
 
 <script>
 
 var sess_user_id = <?php echo isset($_SESSION['user_id'])?$_SESSION['user_id']:0; ?>;
//alert(sess_user_id);



if(sess_user_id == '0' || sess_user_id == '' || sess_user_id == null){
		$("#showOne").hide();
		$("#showTwo").hide();
		$("#showThree").hide();
		$("#query").hide();
		$("#aaq_submit_btn").hide();
		
}

function aaq_form_validate(){
	//alert('function start');
	$('.invalid-feedback').removeClass('is-visible');
	
		
		var sign_id = $("#signId").val();
		var name = $("#nameId").val();
		var email_id = $("#emaiId").val();
		var dob = $("#aaq_date").val();
		var tob = $("#tob").val();
		var birth_place = $("#placeId").val();
		var gender = $("#gender").val();
		var mobileNo = $("#mobileNo").val();
		var countrycode = $("#countrycode").val();
	
		//alert('countrycode'+countrycode);
		//alert('mobileNo '+mobileNo);
		
		var err = 0;
		//alert(product_id);
		//alert('choose_sign '+sign_id);
		
		if(sign_id == '' || sign_id == null){			
			$('.invalid-sign').addClass('is-visible').text('Please select a valid Sign');
			err = 1;
		}
		if(name == '' || name == null){			
			$('.invalid-name').addClass('is-visible').text('Please provide a valid Username');
			err = 1;
		}
		if( email_id == '')
		{
			$('.invalid-email').addClass('is-visible').text('Please provide an Email ID');
			err = 1;
		}else if(email_id!='')
		{
			
		   if(!validateEmail(email_id))
		   {
			   $('.invalid-email').addClass('is-visible').text('Please provide a valid Email ID');
			   err = 1;
		   }
		  
		}		 
		if(dob == '')
		{
			$('.invalid-dob').addClass('is-visible').text('Please provide a valid Birth date');
			err = 1;
		} 
		if(birth_place == '')
		{
			$('.invalid-bplace').addClass('is-visible').text('Please provide a valid Area');
			err = 1;
		} 
		if(gender == '' || gender == null)
		{
			$('.invalid-gender').addClass('is-visible').text('Please select your gender');
			err = 1;
		} 
		if(mobileNo == '')
		{
			$('.invalid-mobile').addClass('is-visible').text('Please provide a valid Mobile No');
			err = 1;
		}else if(mobileNo!='')
		{
			if (isNaN(mobileNo))
			{
				$('.invalid-mobile').addClass('is-visible').text('Please Enter Valid Mobile Number');
				err = 1;
			}
			else if ((countrycode == '91') && mobileNo.length != 10) {
              	$('.invalid-mobile').addClass('is-visible').text('Mobile Number looks incorrect');
                err = 1;
            }else if (countrycode == '971' && mobileNo.length != 9) {
                	$('.invalid-mobile').addClass('is-visible').text('Mobile Number looks incorrect');
                err = 1;
            }
			
		 } 
		 
		 return err;
}

if(sess_user_id == '0' || sess_user_id == '' || sess_user_id == null){
 
 
	$("#aaq_nonregister_submit").click(function(e) 
	{
		e.preventDefault();
		
		var validate_resp = aaq_form_validate();
		//alert('aaq_category validate_resp '+validate_resp);
		//alert(validate_resp);
		if(validate_resp == 0){
			var forpage = 'aaq_';
			//$('#non_register').modal({backdrop: 'static', keyboard: false}, 'show');
			$('#non_register').modal('show');
			$("#register_button12").attr('data-for',forpage);
			$(".popup_password").addClass(forpage+'passwordId');
		}
	});
}

 else
{
	
	$(".products").css("display", "block");
	$(".category_aaq").css("display", "block");
	$("#aaq_nonregister_submit").css("display", "none");

	
	$("#aaq_category").change(function(e) 
	{
		e.preventDefault();
		
		var q_category = $('#aaq_category option:selected').val();
		//alert(q_category);
		
		
		
		  var formData = { 'user_id':sess_user_id,'question_category': q_category, action: 'get_predefined_questions', oauth: '7ff7c3ed4e791da7e48e1fbd67dd5b72',source:'web' };
		  
		  $.ajax({
				type: "POST",
				url: API_URL,
				data: JSON.stringify(formData),
				dataType: 'json',
				success: function(response)
				{
					var resdata = JSON.stringify(response);
					//alert(resdata);
					
					console.log(response);
					if (response.returncode == 200) 
					{
						
						var htm = '';
						//alert(response.returndata);
						
						var j=1;
						$.each(response.returndata, function (i, obj){
							//alert(obj);
								htm += '<div class="form-check pt-2"><input type="checkbox" class="form-check-input chk_quest check_question_'+j+'" id="check_question" name="" value="'+obj.question+'" >'+obj.question+'<label class="form-check-label" for="flexCheckDefault"></label></div>';
								j++;
							});
						
						var k=1;
						/* for(var k=1;k<=3;k++)
						{ */
							//alert(k);
								htm += '<div id="query" class="catagory-items" style="display: block !important;"><div class="pt-5 "><h4>Write Your Own Questions</h4></div><div id="write-own-question"><div class="mb-3"><textarea class="form-control text_que text_question_'+k+'" id="exampleFormControlTextarea1" rows="5"></textarea></div></div></div>';
						//}
						$('#showOne').html(htm);
						$('#showOne').show();
					}
					if (response.returncode == 201) 
					{
						//swal('', response.returnmessage, 'warning');
						//$('#showOne').hide();
						
						var htm = '';
						var k=1;
						/* for(var k=1;k<=3;k++)
						{ */
							//alert(k);
								htm += '<div id="query" class="catagory-items" style="display: block !important;"><div class="pt-5 "><h4>Write Your Own Questions</h4></div><div id="write-own-question"><div class="mb-3"><textarea class="form-control text_que text_question_'+k+'" id="exampleFormControlTextarea1" rows="5"></textarea></div></div></div>';
						//}
						$('#showOne').html(htm);
						$('#showOne').show();
					} 
			
				},
				error: function(response) {
					console.log('An error occurred.');
					console.log(response);
				},
			});
		
		 
		
	});
	
}
	
	$("#aaq_submit").click(function(e) 
	{
		e.preventDefault();
		
		var product_id = $("#product_id").val();
		var sign_id = $("#signId").val();
		var name = $("#nameId").val();
		var email_id = $("#emaiId").val();
		var dob = $("#aaq_date").val();
		var tob = $("#tob3").val();
		//alert(tob);
		var birth_place = $("#placeId").val();
		var gender = $("#gender").val();
		var mobileNo = $("#mobileNo").val();
		var countrycode = $("#countrycode").val();
		var q_category = $('#aaq_category option:selected').val();
		//alert('q_category'+q_category);
		var validate_resp = aaq_form_validate();
		//var trans_id = <?php echo substr(rand(1111,9999).date('ymdHis').rand(1111,9999), 0, 16); ?>;
		//alert('trans_id '+trans_id);
		//alert('aaq_submit validate_resp '+validate_resp);
		
		//alert($('input[type=checkbox]:checked').length);
		
		if(tob == '' || tob == null){			
			$('.invalid-tob').addClass('is-visible').text('Please provide a valid birth time');
			validate_resp = 1;
		}
		
		if(product_id == '' || product_id == null){			
			$('.invalid-prod').addClass('is-visible').text('Please select a valid Product');
			validate_resp = 1;
		}
		
		if(q_category == '' || q_category == 'Area of Query')
		{
			$('.invalid-category').addClass('is-visible').text('Please choose a category');
			validate_resp = 1;
		} 
	
		var ques_arr = new Array();
		var ques_textbox_arr = new Array();
		
		//alert($('.chk_quest:checked').size());
	
		$('input[type=checkbox]:checked').each(function() {
		  ques_arr.push(this.value);
		});
			
		var txt_cnt = 0;
		 $('.text_que').each(function() {
			if($(this).val().length>0){
				ques_textbox_arr.push($(this).val());
				txt_cnt++;
			}
		});
			
			
		//	alert('txt_cnt '+txt_cnt);

		//var selected_question = $('input[type=checkbox]:checked').length+txt_cnt;
		var selected_question = $('.chk_quest:checked').size()+txt_cnt;
		//alert('product_id'+product_id);
		//alert('selected_question '+selected_question);
		
		var final_ques_arr = new Array();
		final_ques_arr = $.merge(ques_arr,ques_textbox_arr);
		console.log(final_ques_arr);
		
		if(validate_resp == 0){
		
		if((product_id=='13' && selected_question == '1') || (product_id=='14' && selected_question == '3') ){		
			//console.log('ques_textbox_arr'+ques_textbox_arr);
		
		
		
		  var formData = { 'user_id':sess_user_id,'product_id': product_id, 'zodiac_id': sign_id, 'name': name, 'email_id': email_id,'dob': dob, 'tob': tob, 'birth_place'	: birth_place, 'gender': gender, 'mobileNo': mobileNo, action: 'askadoubt', for: 'askadoubt', oauth: '7ff7c3ed4e791da7e48e1fbd67dd5b72',source:'web','question_category':q_category,'question':final_ques_arr };
		  
		  $.ajax({
				type: "POST",
				url: API_URL,
				data: JSON.stringify(formData),
				dataType: 'json',
				success: function(response)
				{
					
					if (response.returncode == 200) 
					{
						//alert(response.returndata);
						//alert('succ-resp'+response);
						//$('#choosePlanpayment').	("show");
						$('#for_payment').val('aaq_');
						$('#aaq_trans_id').val(response.returndata);
						//alert('aaq_trans_id -- '+$('#aaq_trans_id').val());
						//alert($('#for_payment').val());
						$('.package').html('Ask a Question');
						//$('#choosePlanpayment').modal({backdrop: 'static', keyboard: false}, 'show');
						$('#choosePlanpayment').modal('show');
					}
					if (response.returncode == 201) 
					{
						swal('', response.returnmessage, 'warning');
					} 
			
				},
				error: function(response) {
					console.log('An error occurred.');
					console.log(response);
				},
			});
		
		 }else{
			 if(product_id=='13' && selected_question != '1'){
				 swal('', 'Please choose/write one question', 'warning');
			 }
			 
			 if(product_id=='14' && selected_question != '3'){
				 swal('', 'Please choose/write three questions', 'warning');
			 }
		 }
	}
	});
	
	function validateEmail(email) 
	{
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	
	$('#tob').datetimepicker({
        format: 'hh:mm:ss a'
    }); 
	
	$('.close_password').click(function(){
		$('#passwordId').val("");
		$('#non_register').modal('hide');	
	});

	
 </script>