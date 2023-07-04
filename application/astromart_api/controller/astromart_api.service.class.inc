<?php
/*
 * @company: 	Symbiotic Infotech Pvt. Ltd.
 * @copyright: 	© Symbiotic Infotech Pvt. Ltd. 2011
 *				All rights reserved.Any redistribution or reproduction of part
 * 				or all of the contents in any form is prohibited. You may not,
 * 				except with express written permission, distribute or
 * 				commercially exploit or personally use the content.
 * 				Nor may you transmit it or store it in any other media or
 * 				other form of electronic or physical retrieval system.
 *
 * @filename:	ticket.service.class.inc
 * @filetype:	PHP
 * @filedesc:   This file contains the controller for members Module
 * 				which select the action or state for model depends
 * 				upon the user input given to the controller.
 * 
 *
 */
 //ini_set("display_errors",1);
class Astromart_appController
{
	/**
	 * Description:
	 * This is controller for ticket Module,it will decide the action  to be given to 
	 * the model class depends upon the action parameter given to the
	 * controller.
	 * 
	 * Parameters:
	 *	null		: null
	 * Return Value: 
	 *	null		: null
	*/
	function __construct() {
		global $request, $tsResponse, $library;
		
		$this->apimodel = new Astromart_appModel();
		
		$json = file_get_contents('php://input');
		$request = json_decode($json, true);
		console ( LOG_LEVEL_INFO, 'Incoming Request: '. var_export($request, true));
		
		// if(empty($request['oauth']) || $request['oauth'] != OAUTH_STRING) {
		// 	$result = array(
		// 		'returncode'	=> '201',
		// 		'returnmessage'	=> 'Invalid OAuth '.OAUTH_STRING,
		// 	);
		// 	console ( LOG_LEVEL_INFO, 'Response Sent: '. var_export($result, true));
		// 	echo json_encode($result);
		// 	exit;
		// }
		$req_action = $request['action'];
		$req_source = $request['source'];
		console ( LOG_LEVEL_INFO, 'Request Action: '. $req_action .' :: Request Source: '.$req_source);
		
		//$array_check = array("login","register","forgot_password","validate_otp","resend_otp","reset_password","get_country_data","log_out","get_board_details","set_password","get_profile","singlesignon","unregister","contact_us_submit","get_user_details","login_verify","promo_transaction","get_mark_list","get_assessment_details","get_assessment_list_sat","assessment_history_all");
		console ( LOG_LEVEL_INFO, 'Response Sentv twstt: ');

		// if (!in_array($req_action, $array_check)) {

		// 	$session_data = $this->apimodel->check_session($request);
		// 	//print_r($session_data);
		// 	console ( LOG_LEVEL_INFO, 'Result session_data :: '.var_export($session_data,true));
		// 	//exit;
			
		// 	if(!$session_data){
		// 		$result = array(
		// 			'returncode'	=> '202',
		// 			'returnmessage'	=> 'Oops! Looks like your user id is used in another device.. Logging out !'
		// 		);
		// 		console ( LOG_LEVEL_INFO, 'Response Sent: '. var_export($result, true));
		// 		echo json_encode($result);
		// 		exit;
		// 	}
		// }
		
		if(isset($request['action']) && !empty($request['action'])) {
			
			$response = $this->$req_action($request);
			
			console ( LOG_LEVEL_INFO, 'Req req_action :: '.var_export($req_action,true));
			console ( LOG_LEVEL_INFO, 'Req req_action Files :: '.var_export($_FILES,true));
			
			/*
			if (in_array($request['action'], $free_user_actions)) {
				if($response['returncode'] == '200') {
					$trial_data = $this->apimodel->set_trial_user($request, $request['action'], $validate_data['access_count']);
				}
			}
			if(isset($response['returndata']['session_id']) && $response['returndata']['session_id'] == 'end') {
				$trial_data = $this->apimodel->set_trial_user($request, $request['action'], $validate_data['access_count']);
			}*/
			console ( LOG_LEVEL_INFO, 'Response Sent: '. var_export($response, true));
			echo json_encode($response);
			exit;
		} 
	}

	//vignesh - horoscope api

	//home page
	private function get_zodiac_signs() {
		$sign_data = $this->apimodel->get_zodiac_signs();
		if(!empty($sign_data)) {
			$result = array(
				'returncode'	=> '200',
				'returnmessage'	=> 'Success',
				'returndata'	=> $sign_data
			);
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'data not found',
			);
		}
		return $result;
	}

	private function get_purchase_detail($data) {
		$purchase_data = $this->apimodel->get_purchase_detail($data);
		if(!empty($purchase_data)) {
			$result = array(
				'returncode'	=> '200',
				'returnmessage'	=> 'Success',
				'returndata'	=> $purchase_data
			);
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'data not found',
			);
		}
		return $result;
	}

	private function get_user_details($data) {
		$user_data = $this->apimodel->get_user_details($data);
		if(!empty($user_data)) {
			$result = array(
				'returncode'	=> '200',
				'returnmessage'	=> 'Success',
				'returndata'	=> $user_data
			);
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'user doesnt exist',
			);
		}
		return $result;
	}

	//horoscope page
	private function get_horoscope_details($data) {
		if (!empty($data['date'])&&!empty($data['content_type'])) {
			if ($data['content_type'] == 'daily') {
				$horoscope_details = $this->apimodel->get_horoscope_details($data);
				if (!empty($horoscope_details)) {
					$result = array(
						'returncode' => '200',
						'returnmessage' => 'Success',
						'returndata' => $horoscope_details
					);
				} else {
					$result = array(
						'returncode' => '201',
						'returnmessage' => 'data not found',
					);
				}
			}
			else{
				$is_purchased = $this->apimodel->get_purchase_detail($data);
				if (!empty($is_purchased)) {
					// purchase details also needed to show bubble if content type is not daily
					$horoscope_details = $this->apimodel->get_horoscope_details($data);
					if (!empty($horoscope_details)) {
						$result = array(
							'returncode' => '200',
							'returnmessage' => 'Success',
							'returndata' => $horoscope_details,
							'purchased_data'=>$is_purchased
						);
					} else {
						$result = array(
							'returncode' => '201',
							'returnmessage' => 'data not found',
						);
					}
				}
				else{
					return $result = array(
						'returncode' => '202',
						'returnmessage' => 'Purchase not found, purchase the pack to see the content',
					);
				}

			}
		}
		else{
			//give date and type of content like daily,weekly etc.,
			$result = array(
				'returncode' => '201',
				'returnmessage' => 'Required value missing, Give date and type of content',
			);
		}
		return $result;
	}

	private function get_common_horoscope_content($data) {
		if (!empty($data['date'])&&!empty($data['content_type'])&&!empty($data['product_id'])) {
			if ($data['common_type'] == 'this day' || $data['common_type'] == 'this week' || $data['common_type'] == 'this month' || $data['common_type'] == 'this year') {
				$horoscope_details = $this->apimodel->get_common_horoscope_content($data);
				if (!empty($horoscope_details)) {
					$result = array(
						'returncode' => '200',
						'returnmessage' => 'Success',
						'returndata' => $horoscope_details
					);
				} else {
					$result = array(
						'returncode' => '201',
						'returnmessage' => 'data not found',
					);
				}
			}
			else{
				$is_purchased = $this->apimodel->get_purchase_detail($data);
				if (!empty($is_purchased)) {
					// purchase details also needed to show bubble if content type is not daily
					$horoscope_details = $this->apimodel->get_common_horoscope_content($data);
					if (!empty($horoscope_details)) {
						$result = array(
							'returncode' => '200',
							'returnmessage' => 'Success',
							'returndata' => $horoscope_details,
							'purchased_data'=>$is_purchased
						);
					} else {
						$result = array(
							'returncode' => '201',
							'returnmessage' => 'data not found',
						);
					}
				}
				else{
					return $result = array(
						'returncode' => '202',
						'returnmessage' => 'Purchase not found, purchase the pack to see the content',
					);
				}

			}
		}
		else{
			//give date and type of content like daily,weekly etc.,
			$result = array(
				'returncode' => '201',
				'returnmessage' => 'Required value missing, Give date and type of content',
			);
		}
		return $result;
	}

	//vignesh - horoscope api

	//vignesh - register

	private function register ($data) {
		$exist_response 	= $this->apimodel->check_existing_user($data); //Check Existing User
		if($exist_response){
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Hi! Look like your mobile number already registered.Please proceed to login',
			);
			return $result;
		}				
		if(!empty($data['mobile_no']))
		{
			$msisdn_data 	= $this->apimodel->msisdn_verification($data);
			if($msisdn_data['msisdn_length'] != strlen($data['mobile_no'])){
			// if(strlen($data['mobile_no'])<10){
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Enter your valid Mobile Number'
				);
				return $result;
			}
		}
		if( !empty($data['mail'])&&!empty($data['mobile_no'])&&!empty($data['password'])) {
			if(!preg_match('/^(?=.*\d)(?=.*[a-zA-Z])[0-9A-Za-z\?]{5,10}$/', $data['password'])) {
					$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Enter valid Password',
				);
				return $result;
				/* May contain letter and numbers
				Must contain at least 1 number and 1 letter
				May contain any of these characters: !@#$%
				Must be 5-10 characters*/
			}
			
			console ( LOG_LEVEL_INFO, 'User Details to Insert : '. var_export($data, true));
			$insert_response 	= $this->apimodel->register($data); //New User Insertion
			if($insert_response) {
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success'
				);
			} else {
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'UNABLE TO INSERT RECORD'
				);
				return $result; //Error in Insertion
			}
			$response 		= $this->generate_otp($data);//OTP INSERTION
			
			if($data['country_code']=='91')
			{
				$mobile_message = "Register OTP - Your OTP is ".$response['returndata']['otp_val']." Thanks, MTutor WcHA76+mIpQ";
			}
			elseif($data['country_code']=='971')
			{
				$mobile_message = "Register OTP - Your OTP is ".$response['returndata']['otp_val']."";
			}
			$sms_args	= array(
						'number'		=> $data['mobile_no'],
						'message'		=> $mobile_message,
						'country_code' 	=> $data['country_code'],
						'tempid'		=> '1107162211565004165'
					);
			$this->send_sms( $sms_args );
			if($response['returncode'] == 200) {
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'OTP sent to user',					
					'returndata'	=> array('user_id' => $response['returndata']['user_id'])
				);
			} else {
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Problem in sending OTP'
				);
			}
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Required Values are missing'
			);
		}
		return $result;
	}
	
	
	
		private function dipl_backend_register ($data) 
		{
			$exist_response 	= $this->apimodel->check_existing_user($data); //Check Existing User
			if($exist_response){
				$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Hi! Look like your mobile number already registered.Please proceed to login',
				);
				return $result;
			}
			console ( LOG_LEVEL_INFO, 'User Details to Insert : '. var_export($data, true));
		    $insert_backend_response 	= $this->apimodel->dipl_backend_register($data); //New User Insertion
			if($insert_backend_response)
			{
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success'
				);
			} else {
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'UNABLE TO INSERT RECORD'
				);
			}
			return $result;
		}
	private function generate_otp ($data) {
		if((!empty($data['mobile_no']))) {
			
			$data['otp_val'] = mt_rand(1000, 9999);//OTP Generate
			$response = $this->apimodel->insert_otp($data);//OTP INSERTION
			console ( LOG_LEVEL_INFO, 'OTP DATA For: '.$data['mobile_no']." Is ". $response);
			if($response) {
				$otp_id['otp_id'] = $response;
				$otp_data 		= $this->apimodel->get_otp($otp_id);//OTP Fetch
				$this->apimodel->validate_resend_otp($data);
				console ( LOG_LEVEL_INFO, 'OTP DATA : '. var_export($otp_data, true));
				
				if($otp_data){
					$result = array(
						'returncode'	=> '200',
						'returnmessage'	=> 'OTP found',
						'returndata'	=> $otp_data//array('resend_count'=>$resend_response['count'])
					);
				}
				else{
					$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'No record Found'
					);
				}
			} else {
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'problem in inserting otp'
				);
			}
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Required Values are missing'
			);
		}
		console ( LOG_LEVEL_INFO, 'generate_otp Return DATA : '. var_export($result, true));
		return $result;
	}

	private function razor_payment_confirm($data) {
		console ( LOG_LEVEL_INFO, "razor_payment_confirm working");
		if($data['razorpay_order_id'] != "" && $data['razorpay_payment_id'] != "" && $data['razorpay_signature'] != "" ){
			$check_order = $this->apimodel->check_order_exist($data);
			if($check_order)
			{
				$update_order = $this->apimodel->update_order_details($data);
				$signature = hash_hmac('sha256', $data['razorpay_order_id'].'|'.$data['razorpay_payment_id'], RAZOR_PASSWORD);
				if(!strcmp($data['razorpay_signature'], $signature)) {
					$user_active = $this->apimodel->make_user_active($data);
					if($data['product_ids']!='' && $data['user_id']){
						$active_purchased_product=$this->apimodel->active_purchased_product($data);
						if($active_purchased_product){
							$result = array(
								'returncode'	=> '200',
								'returnmessage'	=> 'Payment Successful'
							);
						}
						else{
							$result = array(
								'returncode'	=> '201',
								'returnmessage'	=> 'Payment Error'
							);
						}
					}
				}
				else{
					$result = array(
						'returncode'	=> '201',
						'returnmessage'	=> 'Payment not Authorized'
					);
				}
			}
			else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Order not Exist'
				);
			}
		}
		else{
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Required Values are missing'
			);
		}
		return $result; 
	}

	public function generateRandomno($length = 10) {
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	private function login ($data) {
		if(!empty($data['mobile_no']) && !empty($data['password'])) {
			
			if(!empty($data['mobile_no'])){
			$msisdn_data 	= $this->apimodel->msisdn_verification($data);
			}
	 		//print_r($msisdn_data);exit();
		 	if($msisdn_data['msisdn_length'] != strlen($data['mobile_no'])){
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Enter your valid Mobile Number'
				);
			}else{
				
			$rand_num 	= $this->generateRandomno(20);
			$data = array_merge($data,array('rand_num'=>$rand_num));
			
			
			console ( LOG_LEVEL_INFO, 'login_verification send data: '.var_export($data, true));
			
			$data['login_verify'] = 0;
			$validate_data 	= $this->apimodel->login_verification($data);
			console ( LOG_LEVEL_INFO, 'login_verification response: '.var_export($validate_data, true));

			if(!$validate_data){
				
				$exist_response 	= $this->apimodel->check_existing_user($data);
				
				if($exist_response){
					$result = array(
						'returncode'	=> '201',
						'returnmessage'	=>'Hii! look\'s your password is incorrect'	
					);
				}else{
					$result = array(
						'returncode'	=> '201',
						'returnmessage'	=>'User doesn\'t Exists. Proceed to Sign Up'
					);
				}

				return $result;					
			}
			
				if($validate_data && is_numeric($data['mobile_no'])) {
					if($validate_data['otp_verify'] == 0){//Check User OTP Verified or Not
						$result = array(
							'returncode'	=> '201',
							'returnmessage'	=>'OTP Not Verified',
							'returndata'    => $validate_data
						);
					}else{
						//$this->apimodel->put_login_details($validate_data);
						$error_code = '0';//Verified
						$user_details 	= $this->apimodel->get_user_details($validate_data);
						$returnmessage	=	'Success';
						$result = array(
							'returncode'	=> '200',
							'returnmessage'	=> $returnmessage,
							'returndata'	=> is_null($validate_data['session_code']) ? 'NO' :  'YES',
							// 'returndata'	=> $user_details,
							// 'is_already_login'	=> is_null($validate_data['session_code']) ? 'NO' :  'YES',
						);
					}				
				} else {
					$result = array(
						'returncode'	=> '201',
						'returnmessage'	=> 'INCORRECT DATA'
					);
				}
			}
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Required Values are missing'
			);
		}
		return $result;
	}
	
	private function login_verify ($data) {
		if(!empty($data['mobile_no']) && !empty($data['password']) ) {
	 	//print_r($msisdn_data);exit();
		 if(!empty($data['mobile_no'])){
			$msisdn_data 	= $this->apimodel->msisdn_verification($data);
			}
	 		//print_r($msisdn_data);exit();
		 	if($msisdn_data['msisdn_length'] != strlen($data['mobile_no'])){
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Enter your valid Mobile Number'
				);
			}else{
				
				$rand_num 	= $this->generateRandomno(20);
				$data = array_merge($data,array('rand_num'=>$rand_num));	
				
				
				console ( LOG_LEVEL_INFO, 'login_verification send data: '.var_export($data, true));
				
				$data['login_verify'] = 1;
				$validate_data 	= $this->apimodel->login_verification($data);
				console ( LOG_LEVEL_INFO, 'login_verification response: '.var_export($validate_data, true));

				if(!$validate_data){
					
					$exist_response 	= $this->apimodel->check_existing_user($data);
					
					if($exist_response){
						$result = array(
							'returncode'	=> '201',
							'returnmessage'	=>'Hii! look\'s your password is incorrect'							
						);
					}else{
						$result = array(
							'returncode'	=> '201',
							'returnmessage'	=>'User doesn\'t Exists. Proceed to Sign Up'						
						);
					}

					return $result;					
				}
			
				if($validate_data && (!empty($data['mail'])||is_numeric($data['mobile_no']))) {
					if($validate_data['otp_verify'] == 0){//Check User OTP Verified or Not
						$result = array(
						'returncode'	=> '201',
						'returnmessage'	=>'OTP Not Verified',
						'returndata'    => $validate_data
					);
					}else{
						
						//$this->apimodel->put_login_details($validate_data);
						$error_code = '0';//Verified
						$user_details 	= $this->apimodel->get_user_details($validate_data);
						$returnmessage	=	'Success';
						$result = array(
						'returncode'	=> '200',
						'returnmessage'	=> $returnmessage,
						// 'returndata'	=> is_null($validate_data['session_code']) ? 'NO' :  'YES',
						'returndata'	=> $user_details,
						// 'is_already_login'	=> is_null($validate_data['session_code']) ? 'NO' :  'YES',
					);
					}				
				} else {
					$result = array(
						'returncode'	=> '201',
						'returnmessage'	=> 'INCORRECT DATA'
					);
				}
			}
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Required Values are missing'
			);
		}
		return $result;
	}

	private function validate_otp ($data) {
		if(!empty($data['otp_code']) && !empty($data['user_id'])){
			
			$otp_response 		= $this->apimodel->otp_counter($data); //Increment OTP retry
			$result 			= $this->apimodel->get_otp($data);
			$resend_response 	= $this->apimodel->validate_resend_otp($data);
			console ( LOG_LEVEL_INFO, 'OTP DATA For Verification : '. $otp_response);
			if(isset($result['otp_val']) && $data['otp_code'] == $result['otp_val']){
				$update_response = $this->apimodel->update_otp_user($data);//Update OTP user Tbl
				$update_response = $this->apimodel->update_otp_auth($data);//Update OTP Auth Tbl
				$response['user_id'] 	= $result['user_id'];
				$response['validator']	= "OTP verified";
				$returncode = 200;
			}elseif(isset($result) && $result['retry_count'] >2){
				$response['user_id'] 	= $result['user_id'];
				$response['validator'] 	= "Retry count exceeded..! Do you want to resend OTP again?";
				$response['resend_count']	= $resend_response['count'];
				$returncode = 201;
			}else{
				$response['user_id'] 	= $result['user_id'];
				$response['validator'] 	= "Incorrect OTP";
				$response['resend_count']	= $resend_response['count'];
				$returncode = 202;
			}
			if($otp_response) {
				$result = array(
						'returncode'	=> $returncode,
						'returnmessage'	=> $response['validator'],
						'returndata'	=> $response
					);				
			
			} else {
				$result = array(
					'returncode'	=> '202',
					'returnmessage'	=> 'No record Found'
				);
			}
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Required Values are missing'
			);
		}
		return $result;
	}
	
	private function resend_otp ($data) {
		if(!empty($data['user_id'])){
			
			$resend_response = $this->apimodel->validate_resend_otp($data);
			console ( LOG_LEVEL_INFO, 'Resend Data For: '.$data['user_id']." Is ".var_export($resend_response,true));
			if($resend_response['count'] > 3){
				$result = array(
						'returncode'	=> '202',
						'returnmessage'	=> 'You exceeded your limit to Resend OTP, please come back in a while',
					);
					return $result;
			}
			
			$data['otp_val'] = mt_rand(1000, 9999);//OTP Generate
			$response = $this->apimodel->insert_otp($data);//OTP INSERTION 
				console ( LOG_LEVEL_INFO, 'OTP DATA For: '.$data['user_id']." Is ". $response);

			if($response) {
				$otp_id['otp_id'] = $response;
				$otp_data 		= $this->apimodel->get_otp($otp_id);//OTP Fetch
				console ( LOG_LEVEL_INFO, 'OTP DATA : '. var_export($otp_data, true));
				//$mobile_message = "Your OTP is ".$otp_data['otp_val'];
				
				$for = ($data['for'] == "forgot_password")?"Forgot Pwd OTP - ":"Resgister OTP - ";
				$temp = ($data['for']== "forgot_password")?"1107161761718500887":"1107161761715552687";
				
				
				
				
				$user_data 		= $this->apimodel->get_user_details($data['user_id']);
				if($user_data['country_code']=='91')
				{
					$mobile_message = $for."Your OTP is ".$otp_data['otp_val']." Thanks, MTutor WcHA76+mIpQ";
				}
				elseif($user_data['country_code']=='971')
				{
					$mobile_message = $for."Your OTP is ".$otp_data['otp_val']."";
				}
				if(!empty($otp_data['msisdn']))
				{
					$sms_args	= array(
								'number'		=> $otp_data['msisdn'],
								'message'		=> $mobile_message,
								'country_code' 	=> $user_data['country_code'],
								'tempid'		=> $temp
							);
					$this->send_sms( $sms_args );
				}
				
			
				if($otp_data){
					$result = array(
						'returncode'	=> '200',
						//'errorcode'		=> '10',
						'returnmessage'	=> 'Success',
						'returndata'	=> array('resend_count'=>$resend_response['count'])
					);
				}else{
					$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'No record Found'
				);
				}
			} else {
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'No record Found'
				);
			}
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Required Values are missing'
			);
		}
		return $result;
	}

	//vignesh - register
	//vignesh - forgot password

	private function forgot_password ($data) {
		if(!empty($data['mobile_no']) ) 
		{
			if(!empty($data['mobile_no']))
			{
				$msisdn_data 	= $this->apimodel->msisdn_verification($data);
				$response = ($msisdn_data['msisdn_length'] != strlen($data['mobile_no']))?0:1;//0-false,1-true
				console ( LOG_LEVEL_INFO, $msisdn_data['msisdn_length'].' ABC '.strlen($data['mobile_no']));
				if($msisdn_data['msisdn_length'] != strlen($data['mobile_no'])){
					$result = array(
						'returncode'	=> '201',
						'returnmessage'	=> 'Enter your valid Mobile Number'
					);
					return $result;
				}
				console ( LOG_LEVEL_INFO, 'TEST1');
			}
			$user_data 	= $this->apimodel->get_user_details($data);
			console ( LOG_LEVEL_INFO, 'TEST12');
			if($user_data) {
				$returndata 		= array('user_id'=>$user_data['user_id'],'msisdn'=>$user_data['msisdn']);
				$data['user_id'] 	= $user_data['user_id'];
				$data['mobile_no'] 	= $user_data['msisdn'];
				$data['mail'] 	= $user_data['email_id'];
				/* $resend_response = $this->apimodel->validate_resend_otp($data);
				console ( LOG_LEVEL_INFO, 'OTP Count UserID: '.$data['user_id']." Is ".var_export($resend_response,true));
				if($resend_response['count'] > 2){
					$result = array(
							'returncode'	=> '200',
							'errorcode'		=> '108',
							'returnmessage'	=> 'Success',
							'returndata'	=> 'Resend Exceeded'
						);
						return $result;
				} */
					console ( LOG_LEVEL_INFO, 'TEST12'.var_export($data,true));
				$this->apimodel->update_otp_entry($data);

				$response 			= $this->generate_otp($data);//OTP INSERTION
				if(!empty($data['mobile_no']))
				{
				
				if($user_data['country_code'] =='91')
				{					
					$mobile_message = "Forgot Pwd OTP - Your OTP is ".$response['returndata']['otp_val']." Thanks, MTutor 
					WcHA76+mIpQ";
				}
				elseif($user_data['country_code'] =='971')
				{
					$mobile_message = "Forgot Pwd OTP - Your OTP is ".$response['returndata']['otp_val']."";
				}
					$sms_args	= array(
							'number'		=> $data['mobile_no'],
							'message'		=> $mobile_message,
							'country_code' 	=> $user_data['country_code'],
							'tempid'		=> '1107161761718500887'
						);
					$this->send_sms( $sms_args );
				}
				$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'OTP GENERATION FAILED',
				'returndata'	=> $returndata
			);
			
				if($response['returncode'] == 200) {
					$result = array(
						'returncode'	=> '200',
						'returnmessage'	=> 'Success',					
						'returndata'	=> array('user_id' => $response['returndata']['user_id'],'msisdn'=>$response['returndata']['msisdn'])
					);
				} else {
					$result = array(
						'returncode'	=> '201',
						'returnmessage'	=> 'No record Found'
					);
				}				
			}else{
				$error_code = '110';
				$returndata 	= "User doesn't Exists. Proceed to Sign Up";
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> "User doesn't Exists. Proceed to Sign Up",
				);
			}
					
			
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Required Values are missing'
			);
		}
		console ( LOG_LEVEL_INFO, 'forgot_password return data : '. var_export($result, true));
		return $result;
	}
	private function log_out($data) {
		$logout_data = $this->apimodel->logout_data($data);
		if($logout_data) {
			$result = array(
				'returncode'	=> '200',
				'returnmessage'	=> 'Success'
			);
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'failed'
			);
		}
		return $result;
	}
	
	private function reset_password ($data) {
		if($data['new_password']!="" && $data['confirm_password']!=""){
			if($data['new_password'] == $data['confirm_password']){
				$data['password'] = $data['new_password'];
				//if(!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])[0-9A-Za-z]{5,10}$/', $data['password'])) {
				if(!preg_match('/^(?=.*\d)(?=.*[a-zA-Z])[0-9A-Za-z\?]{5,10}$/', $data['password'])) {
				// if(!preg_match('/^[a-z0-9]{5,10}$', $data['password'])) {
						$result = array(
						'returncode'	=> '201',
						'returnmessage'	=> 'Enter valid Password', //Existing User
					);
					return $result;
				}else{
					$verify_password = $this->apimodel->verify_old_password($data);
					
					if(isset($verify_password['user_id'])){
							$result = array(
										'returncode'	=> '201',
										'returnmessage'	=> 'Looks you set the old password again ! Please set a new one',
										);
				return $result;
					}
					$update_data 	= $this->apimodel->update_user_password($data);
					$result 	= array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Password Changed successfully',
				);
					return $result;
				}
				
			}else{
				$result = array(
						'returncode'	=> '201',
						'returnmessage'	=> 'Oops! Passwords Mismatch',//Existing User
					);
					return $result;
			}	
		}else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Required Values are missing'
			);
		}
	}

	//vignesh - forgot password
	//vignesh - razor payment
	private function get_razorpay_orderid($data) {
		//console ( LOG_LEVEL_INFO, "Razor Orders API request :".var_export($data));

		//if(!empty($data['user_id']) && !empty($data['pay_name']) && !empty($data['pay_state']) && !empty($data['pay_country']) && !empty($data['pay_mode']) && !empty($data['pay_currency']) && !empty($data['amount']) && !empty($data['amount']) && !empty($data['productId']) && !empty($data['productId']) ) {
		   
	   if(!empty($data['user_id']) && !empty($data['pay_name'])  && !empty($data['pay_mode']) && !empty($data['pay_currency']) && !empty($data['amount']) ) {
	   

			$transactionid = $this->generateRandomno('32');
			$data['transactionid'] = $transactionid;
		   
		   //$payment_id = $this->apimodel->insert_payment($data);
		   //console ( LOG_LEVEL_INFO, "buy now payment insert id is :".$payment_id);
		   //if($payment_id){
			$post_fields = "amount=".$data['amount']."&currency=".$data['pay_currency']."&receipt=".$transactionid."&payment_capture=0";
			$url	= RAZOR_ORDERS_API;
			$ch		= curl_init();
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERPWD, RAZOR_USERNAME.":".RAZOR_PASSWORD);
			$response = curl_exec($ch);
			curl_close($ch);
			console ( LOG_LEVEL_INFO, "Razor Orders API request :".RAZOR_ORDERS_API." : post data :: ".$post_fields." | response: ".$response);
			$response_data = json_decode($response,true);
			$result_data = array(
				'orderid'	=> $response_data['id'],
				'amount'	=> $response_data['amount'],
				'currency'	=> $response_data['currency'],
				'receipt'	=> $response_data['receipt']
			);
			if($response_data['status'] == 'created' && !empty($response_data['id'])) {
				//$this->apimodel->payment_res_update($transactionid, $response_data['id']);
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Order Created',
					'paymentmsg'	=> $result_data
				);
				$data['order_id']=$result_data['orderid'];
				$insert_history = $this->apimodel->insert_purchase_history($data);

			} else {
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Razorpay Response Failed'
				);
			}
	   //}
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Required Values are missing'
			);
		}
		return $result; 
	}
	
	private function get_festival_data($data){
		$festive_data = $this->apimodel->get_festival_data($data);
		if(!empty($festive_data)) {
			$result = array(
				'returncode'	=> '200',
				'returnmessage'	=> 'Success',
				'returndata'	=> $festive_data
			);
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'data not found',
			);
		}
		return $result;		
	}

	private function get_panchang_details($data) {
		$panchang_details = $this->apimodel->get_panchang_details($data);
		if(!empty($panchang_details)) {
			$result = array(
				'returncode'	=> '200',
				'returnmessage'	=> 'Success',
				'returndata'	=> $panchang_details
			);
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'data not found',
			);
		}
		return $result;
	}
	private function get_panchang_location($data) {
		$location_details = $this->apimodel->get_panchang_location($data);
		if(!empty($location_details)) {
			$result = array(
				'returncode'	=> '200',
				'returnmessage'	=> 'Success',
				'returndata'	=> $location_details
			);
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'data not found',
			);
		}
		return $result;
	}
	
	private function get_register_details($data) {
		$register_data = $this->apimodel->get_register_details($data);
		if(!empty($register_data)) {
			$result = array(
				'returncode'	=> '200',
				'returnmessage'	=> 'Success',
				'returndata'	=> $register_data
			);
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'data not found',
			);
		}
		return $result;
	}
	
	private function askadoubt ($data) { 
	console(LOG_LEVEL_TRACE,"aad - data".var_export($data,true));
	
		if(!empty($data['user_id']) && isset($data['question']) && !empty($data['product_id']) && !empty($data['zodiac_id']) && !empty($data['source'])) {
			/* if(mb_strlen(str_replace(array("\n", "\r\n", "\r"), '', $data['question'])) > 1000) {
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'You have exceeded the maximum charaters of 1000'
				);
				return $result;
			} */
			console(LOG_LEVEL_TRACE,"aad - data1");
			$data['trans_id'] = substr(rand(1111,9999).date('ymdHis').rand(1111,9999), 0, 16);
			$insert_data = $this->apimodel->add_doubt($data);//return $insert_data;
						console(LOG_LEVEL_TRACE,"aad - data2");

			
			
			
			/* $subject = "User raised New Question";
			
			$MailFromName = "Astromart AskaQuestion";
			
			$content_format = 'Hi AAQ Team, <BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The mentioned user has raised a question through AAD on the following topic, AAD team to respond at their earliest.<BR><BR>User : ~USER~<BR><BR>Question : 
			~QUESTION~<BR><BR>Subject : ~SUBJECT~<BR><BR>Board : ~BOARD~<BR><BR>Grade : ~GRADE~<BR><BR>Source : ~SOURCE~';
		
		
			$data['board_name'] = (empty($data['board_name']))?"CBSE":$data['board_name'];
			$mail_content =  str_ireplace(array("~USER~","~QUESTION~","~SUBJECT~","~BOARD~","~GRADE~","~SOURCE~"), array($data['user_msisdn'],$data['question'], $data['subject_name'], $data['board_name'],$data['grade_name'],$data['source']), $content_format);
				
				
			//$res  =$this->sendemail(AAD_MAIL, $mail_content, $subject, AAD_MAIL_CC,$attachment,$MailFromName);
			console ( LOG_LEVEL_INFO, 'Mail Response: '.$res); */
				
			
			
			if($insert_data) {
				//$trial_data = $this->apimodel->set_trial_user($data, 'askadoubt', $data['trial_access_count']);
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $data['trans_id']
				);
			} else {
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Unable to insert record'
				);
			}
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Required Values are missing'
			);
		}
		return $result;
	}
	
	
	private function get_doubts ($data) {
		if(!empty($data['user_id'])) {
			$doubt_data = $this->apimodel->get_doubts($data);
			if(!empty($doubt_data)) {
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $doubt_data
				);
			} else {
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> 'nodata'
				);
			}
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'User Id mandatory'
			);
		}
		return $result;
	}
	
	private function get_predefined_questions($data) {
		$quest_data = $this->apimodel->get_predefined_questions($data);
		if(!empty($quest_data)) {
			$result = array(
				'returncode'	=> '200',
				'returnmessage'	=> 'Success',
				'returndata'	=> $quest_data
			);
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'data not found',
			);
		}
		return $result;
	}
	private function get_tarot_category_name($data) {
		$tarot_category_data = $this->apimodel->get_tarot_category_name($data);
		if(!empty($tarot_category_data)) {
			$result = array(
				'returncode'	=> '200',
				'returnmessage'	=> 'Success',
				'returndata'	=> $tarot_category_data
			);
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'data not found',
			);
		}
		return $result;
	}
	private function get_tarot_category_content($data) {
		$tarot_category_content_data = $this->apimodel->get_tarot_category_content($data);
		if(!empty($tarot_category_content_data)) {
			$result = array(
				'returncode'	=> '200',
				'returnmessage'	=> 'Success',
				'returndata'	=> $tarot_category_content_data
			);
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'data not found',
			);
		}
		return $result;
	}
	
	private function get_profile ($data) {
		if(!empty($data['user_id'])) {
			$profile_data = $this->apimodel->get_profile($data);
			if(!empty($profile_data)) {
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $profile_data
				);
			} else {
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> 'nodata'
				);
			}
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Invalid User ID'
			);
		}
		return $result;
	}
	private function profile_submit($data) 
	{
		console(LOG_LEVEL_INFO,"profile_submit start :: ".var_export($data,true));
		if(!empty($data['user_id']) && !empty($data['msisdn']) || isset($data['onlypropic']))  
		{
			$file_name = "";
			if(isset($data['profile_img']) && $data['profile_img'] != "")
			{
				$profilephoto = $data['profile_img'];
				$profile_image = $data["user_id"]."_".rand().time().".jpg";
				$stroage_name = ROOT_DIR.'storage/uploads/profile_image/'.$profile_image;
				$file_name = APPLICATION_URL.'storage/uploads/profile_image/'.$profile_image;
				$file = file_put_contents($stroage_name,base64_decode($profilephoto));
				console(LOG_LEVEL_INFO,"From API updateprofile path move :: ".var_export($file,true));
			}
			$profile_data = $this->apimodel->submit_profile($data,$file_name);
			// console ( LOG_LEVEL_INFO, 'User Details to Insert : '. var_export($data, true));
			if($profile_data){
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Successfully Updated',
					'profile_img'	=> $file_name
				);
			}else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Failed to update'
				);
			}
			
			
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'Required Values are missing'
			);
		}
		return $result;
	}
	
	private function get_mysubs ($data) {
		if(!empty($data['user_id'])) {
			$mysub_data = $this->apimodel->get_mysubs($data);
			if(!empty($mysub_data)) {
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $mysub_data
				);
			} else {
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Success',
					'returndata'	=> 'nodata'
				);
			}
		} else {
			$result = array(
				'returncode'	=> '201',
				'returnmessage'	=> 'User Id mandatory'
			);
		}
		return $result;
	}
	private function get_slot_details ($data)
	{
		$get_slot_details_data = $this->apimodel->get_slot_details($data);
		if(!empty($get_slot_details_data)) 
		{
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $get_slot_details_data
				);
		}else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Success',
					'returndata'	=> 'nodata'
				);
			}
		return $result;
	}
	private function insert_astrologer_availability ($data)
	{
		$insert_astrologer_avail_data = $this->apimodel->insert_astrologer_availability($data);
		if(!empty($insert_astrologer_avail_data)) 
		{
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success'
				);
		}else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'UNABLE TO INSERT RECORD'
				);
			}
		return $result;
	}
	/* private function get_astro_available_slot ($data)
	{
		$get_astro_available_slot_data = $this->apimodel->get_astro_available_slot($data);
		if(!empty($get_astro_available_slot_data)) 
		{
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $get_astro_available_slot_data
				);
		}else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Success',
					'returndata'	=> 'nodata'
				);
			}
		return $result;
	} */
	private function insert_req_booking_appoinment ($data)
	{
		$insert_req_booking_appoinment_data = $this->apimodel->insert_req_booking_appoinment($data);
		if(!empty($insert_req_booking_appoinment_data)) 
		{
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success'
				);
		}else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'UNABLE TO INSERT RECORD'
				);
			}
		return $result;
	}
	
	private function get_astrologer_details ($data)
	{
		$get_astrologer_details = $this->apimodel->get_astrologer_details($data);
		if(!empty($get_astrologer_details)) 
		{
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $get_astrologer_details
				);
		}else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Success',
					'returndata'	=> 'Astrologer unavailable'
				);
			}
		return $result;
	}
	private function get_astro_available_date ($data)
	{
		$get_astro_available_date_data = $this->apimodel->get_astro_available_date($data);
		if(!empty($get_astro_available_date_data)) 
		{
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $get_astro_available_date_data
				);
		}else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Success',
					'returndata'	=> 'nodata'
				);
			}
		return $result;
	}
	private function get_astro_avail_date_timings ($data)
	{
		$get_astro_avail_date_timings_data = $this->apimodel->get_astro_avail_date_timings($data);
		if(!empty($get_astro_avail_date_timings_data)) 
		{
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $get_astro_avail_date_timings_data
				);
		}else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Success',
					'returndata'	=> 'nodata'
				);
			}
		return $result;
	}
	
	private function get_astrologerbooked_details ($data)
	{
		$get_astrologerbooked_details = $this->apimodel->get_astrologerbooked_details($data);
		if(!empty($get_astrologerbooked_details)) 
		{
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $get_astrologerbooked_details
				);
		}else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Success',
					'returndata'	=> 'nodata'
				);
			}
		return $result;
	}
	
	private function get_astro_appoinments ($data)
	{
		$get_astro_appoinments = $this->apimodel->get_astro_appoinments($data);
		
		//echo '<pre>';print_r($get_astro_appoinments);exit;
		
		foreach($get_astro_appoinments as $val){
			$astro_appoinments[$val['avl_date']][$val['display_time']] = $val['status'] ;
		}
		//echo '<pre>';print_r($get_astro_appoinments);
		//exit;
		if(!empty($get_astro_appoinments)) 
		{
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $astro_appoinments,
				);
		}else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Success',
					'returndata'	=> 'nodata'
				);
			}
		return $result;
	}
	
	private function fetch_astro_available_slots ($data)
	{
		$astro_avail_slots = $this->apimodel->fetch_astro_available_slots($data);
		
		foreach($astro_avail_slots as $val){
			$avail_slots[$val['display_time']]['slot_id'] = $val['slot_id'] ;
			if($val['avl_date'] != ''){
				$avail_slots[$val['display_time']]['status'] = 'available' ;
			}else{
				$avail_slots[$val['display_time']]['status'] = 'free' ;
			}
		} 
		
		if(!empty($astro_avail_slots)) 
		{
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $avail_slots,
				);
		}else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Success',
					'returndata'	=> 'nodata'
				);
			}
		return $result;
	}
	private function general_calendar_details ($data)
	{
		$general_calendar_details = $this->apimodel->general_calendar_details($data);
		
		if(!empty($general_calendar_details)) 
		{
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $general_calendar_details,
				);
		}else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Success',
					'returndata'	=> 'nodata'
				);
			}
		return $result;
	}

	private function unsub_purchase ($data)
	{
		$unsub_purchase = $this->apimodel->unsub_purchase($data);
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Product Unsubscribed',
				);
		return $result;
	}
	
	private function get_language()
	{
		$get_language = $this->apimodel->get_language($data);
		if(!empty($get_language)) 
		{
				$result = array(
					'returncode'	=> '200',
					'returnmessage'	=> 'Success',
					'returndata'	=> $get_language
				);
		}else{
				$result = array(
					'returncode'	=> '201',
					'returnmessage'	=> 'Success',
					'returndata'	=> 'nodata'
				);
			}
		return $result;
	}
	
	private function dubilling_payment_update($data)
	{
		$payment_return = $data['billing_response'];
		if ($payment_return['returncode'] == "200") {
			$success_update = $this->apimodel->dubilling_payment_success_update($data);
			if (!empty($success_update)) {
				$result = array(
					'returncode' => '200',
					'returnmessage' => 'Purchase_success',
				);
			} else {
				$result = array(
					'returncode' => '201',
					'returnmessage' => 'Problem in inserting purchase',
				);
			}
		}
		else{
			$failure_update = $this->apimodel->dubilling_payment_failure_update($data);
			$result = array(
				'returncode' => '201',
				'returnmessage' => 'Purchase Failure',
			);
		}
		return $result;
	}
	
		private function send_sms( $args = array() ) {
		console ( LOG_LEVEL_INFO, 'send_sms incoming data : '. var_export($args, true));
		if( isset($args['number']) && !empty($args['number']) && isset($args['message']) && !empty($args['message']) ) {
			$tempid = "";
	if(isset($args['tempid']) && $args['tempid'] !="")$tempid = "&tempid=".$args['tempid'];
			if($args['country_code'] == "91"){
			$mobile_number	= $this->format_number($args['number']);	
			$oauth			= 'e8975be38def57f45ba3736d859dec62';
			$type			= 'sendSMS';
			$post_fields	= "msisdn=".$mobile_number."&msg=".$args['message']."&oauth=".$oauth."&type=".$type."".$tempid;
			$url	= 'https://online.m-tutor.com/mtutor/gateway/mtutorAPI_1.php';
			$ch		= curl_init();
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			console ( LOG_LEVEL_INFO, 'SMS OTP Request : '. var_export($args, true));
			$response = curl_exec($ch);
			console ( LOG_LEVEL_INFO, 'SMS OTP Response : '. var_export($response, true));
			if(curl_error($ch)) {
				return false;
			}else{
				curl_close($ch);
				return $response;
			}
		}elseif($args['country_code'] == "971"){
			$mobile_number = $args['country_code'].$args['number'];
			$cURL = curl_init();
		//	$url = "http://52.77.82.47:14013/cgi-bin/sendsms?user=user&pass=pass&to=".$mobile_number."&smsc=dipl_2042&mclass=1&coding=0&alt-charset=ASCII&from=2042&text=".urlencode($args['message']);
		//	$url = "http://196.219.55.242/Subscription/SMSSub/API/SMS.aspx?to=".$mobile_number."&PID=3&MSGType=campaign&text=".urlencode($args['message']);	
			
			$url = "http://52.77.82.47/sm_dipl/gateway/astro_otp.php?msisdn=".$mobile_number."&content=".urlencode($args['message']);
			curl_setopt($cURL,CURLOPT_URL,$url);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($cURL, CURLOPT_TIMEOUT,60);
			curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT,60);
			$result = curl_exec($cURL);
			$retCode = curl_getinfo($cURL);
			console ( LOG_LEVEL_TRACE, "SMS Notification - URL : ".$url." | Response : ".$result);
			console(LOG_LEVEL_INFO,'Curl Informations for the given request : '. var_export($retCode,true));
			curl_close($cURL);
			if($result == '' && $productid != ''){
				console(LOG_LEVEL_ERROR,'Error code is '.curl_errno($cURL).' |  Error is  '.curl_error($cURL));
			}
			return $result;
					
		} 
		} else {
			return false;
		}
	}
	
	private function format_number($number) {
		if( is_numeric($number) ){
			if( strlen($number) >= 10 && strlen($number) <= 13 )
				return '+91' . substr($number, -10);
			else
				return false;
		}else {
			return false;
		}
	}

}
?>