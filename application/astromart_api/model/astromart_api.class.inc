<?php
/*
 * @company: 	Symbiotic Infotech Pvt. Ltd.
 * @copyright: 	© Symbiotic Infotech Pvt. Ltd. 2011
 *				All rights reserved.Any redistribution or reproduction of part
 * 				or all of the stos_tool_contents in any form is prohibited. You may not,
 * 				except with express written permission, distribute or
 * 				commercially exploit or personally use the content.
 * 				Nor may you transmit it or store it in any other media or
 * 				other form of electronic or physical retrieval system.
 *
 * @filename:	ticket.class.inc
 * @filetype:	PHP
 * @filedesc:   This file contains the model for user module
 * 				and contains all the business logic for ticket chaking module.
 *
 *
 */

class Astromart_appModel 
{
    public function Astromart_appModel() {
		global $db,$request;
	}
	public function get_zodiac_signs() 
	{
        global $db,$request;
		
		$query = "select product_id,product_name,image_name from product_details where product_category='zodiac' and is_active=1";

        return $db['master']->getResults($query);
    }

    public function get_horoscope_details($data) 
	{
        global $db,$request;

		$type = $data['horoscope_type'] ? $data['horoscope_type'] : 'horoscope';
		if ($data['horoscope_type'] == 'love' || $data['horoscope_type'] == 'career' || $data['horoscope_type'] == 'finance' || $data['horoscope_type'] == 'health') {
			$query = "select hc.*,p.product_name from horoscope_content hc join product_details p on p.product_id=hc.product_id where hc.horoscope_type='" . $type . "'";
		}
		else{
			$data['horoscope_type'] = "horoscope";
			$query = "select hc.*,p.product_name from horoscope_content hc join product_details p on p.product_id=hc.product_id where hc.horoscope_type='" . $data['horoscope_type'] . "'";
			// $query = "select hc.*,p.product_name from horoscope_content hc join product_details p on p.product_id=hc.product_id where hc.date='" . $data['date'] . "' and hc.horoscope_type='" . $data['horoscope_type'] . "'";
		}

        if(!empty($data['product_id'])){
            $query .= " and hc.product_id=".$data['product_id']."";
			$query .= " order by rand()";
			return $db['master']->getOneRow($query);
        }
		else{
			$query .= "group by hc.product_id order by hc.product_id";
			return $db['master']->getResults($query);
		}
    }

	public function get_common_horoscope_content($data) 
	{
        global $db,$request;

		$type = $data['horoscope_type'] ? $data['horoscope_type'] : 'horoscope';

		if($data['content_type']=="daily"){
			$table_name = "horoscope_content";
		}
		else if($data['content_type']=="weekly"){
			$table_name = "weekly_horoscope";
		}
		else if($data['content_type']=="monthly"){
			$table_name = "monthly_horoscope";
		}
		else if($data['content_type']=="yearly"){
			$table_name = "yearly_horoscope";
		}
		$query = "select hc.*,p.product_name from ".$table_name." hc join product_details p on p.product_id=hc.product_id where hc.horoscope_type='".$type."' and hc.product_id=".$data['product_id']." order by rand()";

		// $query = "select hc.*,p.product_name from ".$table_name." hc join product_details p on p.product_id=hc.product_id where hc.horoscope_type='".$type."' and hc.product_id=".$data['product_id']."";

		// if($data['content_type']=="weekly"){
		// 	$query.=" and hc.week='".$data['weekno']."' and year(hc.date)='".date('Y',strtotime($data['date']))."' and month(hc.date)='".date('m',strtotime($data['date']))."'";
		// }
		// else{
		// 	$query.=" and hc.date='".$data['date']."'";
		// }

		return $db['master']->getOneRow($query);
    }

    public function get_purchase_detail($data) 
	{
        global $db,$request;

		$content_type = $data['common_type'] ? $data['common_type'] : $data['content_type'];

		$query = "select * from purchased_details pud join product_details prd on pud.product_id=prd.product_id  where pud.user_id='" . $data['user_id'] . "' and pud.product_category='zodiac' and pud.content_type='" . $content_type . "' and date(expiry_date)>='" . $data['date'] . "' and pud.is_active=1";
		
		if(!empty($data['product_id'])){
            $query .= " and pud.product_id=".$data['product_id']."";
        }

        return $db['master']->getOneRow($query);
    }

    public function check_existing_user($data) {
		global $db,$request;
		$msisdn_cond='';
        if(!empty($data['mobile_no'])){
			$msisdn_cond= "msisdn = ".$data['mobile_no'];
		}
		$query = "SELECT * FROM register WHERE ".$msisdn_cond." AND status = 'active' 
					ORDER BY user_id DESC LIMIT 1";
		return $db['master']->getOneRow($query);
	}

	public function msisdn_verification($data){
		global $db,$request;
		console ( LOG_LEVEL_INFO, 'msisdn_verification 1');
		$country_code = str_replace("+", "", $data['country_code']);
		$query = "SELECT * FROM country_code_details WHERE code = ".$country_code."";
			
		console ( LOG_LEVEL_INFO, 'msisdn_verification 2');
		return $db['master']->getOneRow($query);
		
	}

	public function logout_data($data) {
		global $db,$request;
		
		$query = "UPDATE register SET session_code=NULL WHERE user_id='".$data["user_id"]."' AND status='active'";
		$result = $db['master']->query($query);
		return $result;
	}

    public function register($data) {
		global $db,$request;
		
		$query = "INSERT INTO register
            (user_name, msisdn,email_id,password,otp_verify,status,country_code,created_on,zodiac_sign,gender,DOB,TOB,birth_place)VALUES 
			( '".$data['user_name']."', '".$data['mobile_no']."','".$data['mail']."', '".$data['password']."', '0', 'inactive','".$data['country_code']."',NOW(), '".$data['zodiac_sign']."','".$data['gender']."','".$data['DOB']."','".$data['TOB']."','".$data['place']."')";
			
		$register = $db['master']->query($query);
		
		return $register;
	}
	
	
    public function dipl_backend_register($data) 
	{
		global $db,$request;
		$query = "INSERT INTO register
            (user_name, msisdn,email_id,password,otp_verify,status,country_code,created_on,zodiac_sign,gender,DOB,TOB,birth_place)VALUES 
			( '".$data['user_name']."', '".$data['mobile_no']."','".$data['mail']."', '".$data['password']."', '1', 'active','".$data['country_code']."',NOW(), '".$data['zodiac_sign']."','".$data['gender']."','".$data['DOB']."','".$data['TOB']."','".$data['place']."')";
			
		$register = $db['master']->query($query);
		
		return $register;
	}
	
    public function insert_otp($data) {
		global $db,$request;
		console ( LOG_LEVEL_INFO, 'insert_otp request data : '. var_export($data, true));
		$user_data = $this->get_register_details($data);		
		if($user_data){
			$user_id = $user_data['user_id'];
			$msisdn = $user_data['msisdn'];
			console ( LOG_LEVEL_INFO, 'User Details to Generate OTP : '. var_export($user_data, true));
		}else{
			console ( LOG_LEVEL_ERROR, 'User Not Exists to Generate OTP');
			return false;
		} 
		$resend_count = 0;
		$forgot_count = 0;
		if($data['for'] == "register"){
			$resend_count = 1;
		}
		if($data['for'] == "forgot_password"){
			$forgot_count = 1;
		}
		$query = "INSERT INTO otp_auth
            (user_id ,  msisdn, otp_val ,  is_verified ,  retry_count ,  resend_count,  forgot_verified ,  added_on )
			VALUES 
			( ".$user_id.", '".$msisdn."', ".$data['otp_val'].", '0', '0', '".$resend_count."', '".$forgot_count."', '".date("Y-m-d H:i:s")."')";
			
			$response	= $db['master']->query($query);
			if($response){
				return $db['master']->getLastID();
			}else{
				return false;
			}
	}

    public function get_register_details($data) {
		console ( LOG_LEVEL_INFO, 'get_register_details request data : '. var_export($data, true));
		global $db,$request;
		
		$query = "SELECT * FROM register WHERE 1=1";
		
		
		if(isset($data['mobile_no'])&&!empty($data['mobile_no'])){
			$query .= "	AND msisdn = ".$data['mobile_no'];
		}
		if(isset($data['user_id'])){
			$query .= "	AND user_id = ".$data['user_id'];
		}
		
			$query .= "   ORDER BY user_id DESC LIMIT 1";
			
			return $db['master']->getOneRow($query);
	}

	public function insert_purchase_history($data)
	{
		global $db,$request;
		
		$query_01 = 'insert into purchase_history (user_id,order_id,status,product_ids,purchase_mode,amount,source,trans_id) values ("'.$data['user_id'].'","'.$data['order_id'].'","Pending","'.$data['productId'].'","'.$data['pay_mode'].'","'.$data['amount'].'","'.$data['source'].'","'.$data['transId'].'")'; // change to dynamic(important)
		return $db['master']->query($query_01);
	}

    public function get_otp($data) {
		global $db,$request;
		
		$otp_id = (isset($data['otp_id']))? $data['otp_id'] : false;
		$user_id = (isset($data['user_id']))? $data['user_id'] : false;
		$query = "SELECT * FROM otp_auth WHERE ";
		
		if($otp_id){
			$query .= "	otp_id = ".$otp_id;
		}elseif($user_id){
			$query .= "	user_id = ".$user_id;
		}
		
		$query .= " AND is_verified = 0 AND DATE(added_on) > DATE_SUB(NOW(), INTERVAL 24 HOUR)
					
		ORDER BY otp_id DESC LIMIT 1";//AND retry_count <= 3 AND resend_count <= 3
			
		return $db['master']->getOneRow($query);
	}

	public function check_order_exist($data)
	{
		global $db,$request;
		
		$query_01 = 'select * from purchase_history where user_id="'.$data['user_id'].'" and order_id="'.$data['razorpay_order_id'].'"';
		return $db['master']->getResults($query_01);
	}

	public function update_order_details($data)
	{
		global $db,$request;
		
		$query_01 = 'update purchase_history set payment_id="'.$data['razorpay_payment_id'].'",signature="'.$data['razorpay_signature'].'" where user_id="'.$data['user_id'].'" and order_id="'.$data['razorpay_order_id'].'"';
		return $db['master']->query($query_01);
	}

	public function make_user_active($data)
	{
		global $db,$request;

		if($data['product_ids']=="prev day"||$data['product_ids']=="next day"){
			$valid_days=date('Y-m-d', strtotime("+1 day"));
		}
		if($data['product_ids']=="weekly"||$data['product_ids']=="prev week"||$data['product_ids']=="next week"){
			$valid_days=date('Y-m-d', strtotime("+6 days"));
		}
		if($data['product_ids']=="monthly"||$data['product_ids']=="prev month"||$data['product_ids']=="next month"){
			$valid_days=date('Y-m-d', strtotime("+1 month"));
		}
		if($data['product_ids']=="yearly"||$data['product_ids']=="prev year"||$data['product_ids']=="next year"){
			$valid_days=date('Y-m-d', strtotime("+365 days"));
		}
		
		$query_01 = 'update purchase_history set status="paid",valid_till="'.$valid_days.'",purchased_on=now() where user_id="'.$data['user_id'].'" and order_id="'.$data['razorpay_order_id'].'"';
		return $db['master']->query($query_01);
	}

	public function dubilling_payment_success_update($data)
	{
		global $db,$request;
		if($data['for']=='horo_')
		{
			if($data['product_ids']=="prev day"||$data['product_ids']=="next day"){
				$valid_days=date('Y-m-d', strtotime("+1 day"));
			}
			if($data['product_ids']=="weekly"||$data['product_ids']=="prev week"||$data['product_ids']=="next week"){
				$valid_days=date('Y-m-d', strtotime("+6 days"));
			}
			if($data['product_ids']=="monthly"||$data['product_ids']=="prev month"||$data['product_ids']=="next month"){
				$valid_days=date('Y-m-d', strtotime("+1 month"));
			}
			if($data['product_ids']=="yearly"||$data['product_ids']=="prev year"||$data['product_ids']=="next year"){
				$valid_days=date('Y-m-d', strtotime("+365 days"));
			}
			
			$query_01 = 'insert into purchase_history (user_id,status,product_ids,purchase_mode,amount,source,trans_id,valid_till) values ("'.$data['user_id'].'","paid","'.$data['product_ids'].'","DU billing","1","'.$data['source'].'","'.$data['transId'].'","'.$valid_days.'")'; // change to dynamic(important)

			$db['master']->query($query_01);

			$query_01 = 'select product_id from product_details where product_name like("%' . $data['signId'] . '%") and is_active=1 ';
				$product_details = $db['master']->getOneRow($query_01);

			if ($product_details) 
			{
				
				$_SESSION['horo_id']=$product_details['product_id'];
				$query_02 = 'insert into purchased_details (user_id,product_id,product_category,content_type,expiry_date,purchased_date) values ("' . $data['user_id'] . '","' . $product_details['product_id'] . '","zodiac","' . $data['product_ids'] . '","' . $valid_days . '",curdate())';
				$db['master']->query($query_02);
				
				return 1;
			}
		}
		else if($data['for']=="aaq_")
		{
			
			$query = 'insert into purchase_history (user_id,status,product_ids,purchase_mode,amount,source,trans_id,valid_till) values ("'.$data['user_id'].'","paid","'.$data['product_ids'].'","DU billing","1","'.$data['source'].'","'.$data['trans_id'].'",curdate())';
			$insert_aaq_pur = $db['master']->query($query);
			
			if($data['trans_id'])
			{
				$upd_mtques = 'update mtsoc_question set ques_status = " " where trans_id="'.$data['trans_id'].'" ';
				$db['master']->query($upd_mtques);
			}
			
			$query_01 = 'select product_id from product_details where product_id = "'. $data['product_ids'] .'" and is_active=1 ';
			$product_details = $db['master']->getOneRow($query_01);
			if ($product_details) {
				$query_01 = 'insert into purchased_details (user_id,product_id,product_category,zodiac_id,expiry_date,purchased_date) values ("' . $data['user_id'] . '","' . $product_details['product_id'] . '","aaq","' . $data['signId'] . '",curdate(),curdate())';
				$db['master']->query($query_01);
				return 1;
			} else {
				return 0;
			}
		}
		
		else if($data['for']=="tta_")
		{
			if($data['avail_id']!='')
			{ 
				$query_ins = 'INSERT INTO `astrologer_booking_details`(`user_id`,`avl_id`,`status`)VALUES(
							 "'.$data['user_id'].'", "'.$data['avail_id'].'","booked")';
				 $results_astro_booking = $db['master']->query($query_ins);
			}
			$query = 'insert into purchase_history (user_id,status,product_ids,purchase_mode,amount,source,trans_id,valid_till) values ("'.$data['user_id'].'","paid","15","DU billing","1","'.$data['source'].'","'.$data['trans_id'].'",curdate())';
			$insert_tta_pur = $db['master']->query($query);
			
			$query_01 = 'insert into purchased_details (user_id,product_id,product_category,content_type,expiry_date,purchased_date) values ("' . $data['user_id'] . '","15","tta","' . $data['avail_id'] . '","' . $data['avail_date'] . '",curdate())';
			$db['master']->query($query_01);
			
			return 1;
		}

	}

	public function dubilling_payment_failure_update($data)
	{
		global $db,$request;

		$query_01 = 'insert into purchase_history (user_id,status,product_ids,purchase_mode,amount,source,trans_id) values ("'.$data['user_id'].'","Fail","'.$data['product_ids'].'","DU billing","'.$data['amount'].'","'.$data['source'].'","'.$data['transId'].'")'; // change to dynamic(important)

		$db['master']->query($query_01);
	}

	public function unsub_purchase($data)
	{
		global $db,$request;

		$query_01 = 'update purchased_details set is_active=0 where user_id="'.$data['user_id'].'" and purchase_id="'.$data['purchase_id'].'"'; // change to dynamic(important)

		$db['master']->query($query_01);
	}

	public function active_purchased_product($data)
	{
		global $db,$request;
		if ($data['for'] == 'horo_') {
			if($data['product_ids']=="prev day"||$data['product_ids']=="next day"){
				$valid_days=date('Y-m-d', strtotime("+1 day"));
			}
			if($data['product_ids']=="weekly"||$data['product_ids']=="prev week"||$data['product_ids']=="next week"){
				$valid_days=date('Y-m-d', strtotime("+6 days"));
			}
			if($data['product_ids']=="monthly"||$data['product_ids']=="prev month"||$data['product_ids']=="next month"){
				$valid_days=date('Y-m-d', strtotime("+1 month"));
			}
			if($data['product_ids']=="yearly"||$data['product_ids']=="prev year"||$data['product_ids']=="next year"){
				$valid_days=date('Y-m-d', strtotime("+365 days"));
			}
			// $query_01 = 'select product_id from ms_product_list where product_id in ('.$data['product_ids'].') and is_active=1';
			$query_01 = 'select product_id from product_details where product_name like("%' . $data['signId'] . '%") and is_active=1 ';
			$product_details = $db['master']->getOneRow($query_01);
			if ($product_details) {
				// foreach($product_details as $products){
				// $query_02 = 'select * from purchased_de where user_id="'.$data['user_id'].'" and grade_id="'.$products['grade_id'].'"';
				// $dup_check=$db['master']->getOneRow($query_02);
				// if($dup_check){
				// $query_01 = 'update purchased_details set expiry_date="'.date('Y-m-d', strtotime("+".$products['valid_days']." days")).'" where purchase_id="'.$dup_check['purchase_id'].'"';
				// $db['master']->query($query_01);
				// }
				// else{
					$_SESSION['horo_id']=$product_details['product_id'];
				$query_01 = 'insert into purchased_details (user_id,product_id,product_category,content_type,expiry_date,purchased_date) values ("' . $data['user_id'] . '","' . $product_details['product_id'] . '","zodiac","' . $data['product_ids'] . '","' . $valid_days . '",curdate())';

				// $query_01 = 'insert into user_purchased_tracking (user_id,product_id,purchased_date,status,purchase_mode,order_id,payment_id,signature) values ("'.$data['user_id'].'","'.$products['product_id'].'",now(),"active","RAZOR_PAY","'.$data['razorpay_order_id'].'","'.$data['razorpay_payment_id'].'","'.$data['razorpay_signature'].'")';
				$db['master']->query($query_01);
				// }
				// $query_02 = 'delete from ms_product_cart where user_id="'.$data['user_id'].'" and product_id="'.$products['grade_id'].'"';
				// $db['master']->query($query_02);
				// }
				return 1;
			} else {
				return 0;
			}
		}
		else if($data['for']=="aaq_"){
			
			$query = 'select * from purchase_history where user_id="'.$data['user_id'].'" and order_id="'.$data['razorpay_order_id'].'"';
			$fetch_ordid = $db['master']->getOneRow($query);
			
			if($fetch_ordid){
				$upd_mtques = 'update mtsoc_question set ques_status = " " where trans_id="'.$fetch_ordid['trans_id'].'" ';
				$db['master']->query($upd_mtques);
			}
			
			$query_01 = 'select product_id from product_details where product_id = "'. $data['product_ids'] .'" and is_active=1 ';
			$product_details = $db['master']->getOneRow($query_01);
			if ($product_details) {
				$query_01 = 'insert into purchased_details (user_id,product_id,product_category,zodiac_id,expiry_date,purchased_date) values ("' . $data['user_id'] . '","' . $product_details['product_id'] . '","aaq","' . $data['signId'] . '",curdate(),curdate())';
				$db['master']->query($query_01);
				return 1;
			} else {
				return 0;
			}
		}
		
		else if($data['for']=="tta_")
		{
			if($data['ttaavail_id']!='')
			{ 
				$query_ins = 'INSERT INTO `astrologer_booking_details`(`user_id`,`avl_id`,`status`)VALUES(
							 "'.$data['user_id'].'", "'.$data['ttaavail_id'].'","booked")';
				 $results_astro_booking = $db['master']->query($query_ins);
			}
			$query_01 = 'insert into purchased_details (user_id,product_id,product_category,content_type,expiry_date,purchased_date) values ("' . $data['user_id'] . '","15","tta","' . $data['ttaavail_id'] . '","' . $data['ttaavail_date'] . '",curdate())';
			$db['master']->query($query_01);
			
			return 1;
		}
	}

    public function validate_resend_otp($data) {
		global $db,$request;
		$user_id = (isset($data['user_id']) && !empty($data['user_id']))?$data['user_id']:false;
		$mobile_no = (isset($data['mobile_no']) && !empty($data['mobile_no']))?$data['mobile_no']:false;
		$query = "SELECT count(1) count FROM otp_auth WHERE date(added_on) = CURDATE() ";
		if($user_id)
		$query .= "AND user_id = '".$data['user_id']."'";
		elseif($mobile_no)
		$query .= "AND msisdn = '".$data['mobile_no'] ."' ";
		
		if($data['for'] == "register"){
			$query .= "AND resend_count = '1' ";
		}
		if($data['for'] == "forgot_password"){
			$query .= "AND forgot_verified = '1' ";
		}
		
		return $db['master']->getOneRow($query);
	}

    public function login_verification($data) {
		global $db,$request;
		console ( LOG_LEVEL_INFO, 'login_verification send data: '.var_export($data, true));

		if($data['user_id']) { // for single signon
			$query = "SELECT user_id,otp_verify FROM register WHERE user_id = ".$data['user_id']." AND status = 'active'";
			$result = $db['master']->getOneRow($query);
			if($data['login_verify'] == 1){
				if($result){
					$session_code = $data['rand_num'];
					$update_query = "UPDATE register SET session_code='".$session_code."' WHERE user_id = ".$data['user_id']." and status = 'active'";
					$db['master']->query($update_query);
					$this->put_login_details($data,$result['user_id']);	
				}
			}
		} else {
			$msisdn_cond='';
			if(!empty($data['mobile_no'])){
				$msisdn_cond= "msisdn = ".$data['mobile_no'];
			}
			$query = "SELECT user_id,otp_verify,session_code FROM register WHERE ".$msisdn_cond." AND password = '".$data['password']."' AND status = 'active'";
			
			$result = $db['master']->getOneRow($query);
			if($data['login_verify'] == 1){
				if($result){
					$session_code = $data['rand_num'];
					$update_query = "UPDATE register SET session_code='".$session_code."' WHERE ".$msisdn_cond." AND password = '".$data['password']."' AND status = 'active'";
					$db['master']->query($update_query);
					
					$this->put_login_details($data,$result['user_id']);		
				}
			}
			
		}
			
		
		return $result;
	}

	public function put_login_details($data,$user_id){
		global $db,$request;
		if($data['source'] == 'Android'){$data['source']='APP';}
		$query = "INSERT INTO user_login_history (user_id,session_code,logged_on,mode)VALUES('".$user_id."','".$data['rand_num']."',NOW(),'".$data['source']."')";
		$db['master']->query($query);
	}

    public function get_user_details($data) {
		global $db,$request;
		
		$query = "SELECT * FROM register r left join astrologer_details ad on r.ast_id=ad.ast_id WHERE r.status = 'active'";
		
		if(isset($data['mobile_no'])  && $data['mobile_no']!=''){
			$query .= "	AND r.msisdn = ".$data['mobile_no'];
		}elseif(isset($data['user_id'])){
			$query .= "	AND r.user_id = ".$data['user_id'];
		}
			$query .= "   ORDER BY r.user_id DESC LIMIT 1";
			
			return $db['master']->getOneRow($query);
	}

	public function otp_counter($data) {
		global $db,$request;
		$otp_data = $this->get_otp($data);
		if($otp_data){
			$query = "UPDATE otp_auth SET retry_count = retry_count+1 WHERE otp_id = ".$otp_data['otp_id'];
			return $db['master']->query($query);
		}else{
			return false;
		}
	}

//This function updates the 'otp_verify' and 'status' columns in the 'register' table
//for the user with the given 'user_id'.
public function update_otp_user($data) {
	//Global variables '$db' and '$request' are declared.
	global $db,$request; 
		
		//An SQL query is constructed to update the row with the given 'user_id'
		$query = "UPDATE register SET otp_verify = 1,status = 'active' WHERE user_id = ".$data['user_id'];
		
		//The query is executed on the master database and the result is returned
		return $db['master']->query($query);
}

	
	public function update_otp_auth($data) {
		global $db,$request;
		
			$query = "UPDATE otp_auth SET is_verified = 1 WHERE user_id = ".$data['user_id'];
			return $db['master']->query($query);
	}

	public function get_festival_data($data){
		global $db,$request;
		
		$query = "select * from festival_data where is_active=1 ";
		
		if($data['fest_date']){
			$query .= "and month(festival_date)=month('".$data['fest_date']."') and year(festival_date)=year('".$data['fest_date']."')";
		}else{
			$query .= "and month(festival_date)=month(curdate()) and year(festival_date)=year(curdate())";
		}
		$query .= "  ORDER BY festival_date ASC";
		
		return $db['master']->getResults($query);
	}

	public function update_otp_entry($data){
		global $db,$request;
		$query = "UPDATE otp_auth set forgot_verified=0 WHERE user_id='".$data['user_id']."'";
		$result = $db['master']->query($query);
		return true;
	}

	public function verify_old_password($data) {
		global $db,$request;
		
			$query = "Select user_id FROM register WHERE password = '".$data['password']."' 
					AND user_id = ".$data['user_id']." AND status = 'active'";
			return $db['master']->getOneRow($query);
	}

	public function update_user_password($data) {
		global $db,$request;
		
			$query = "UPDATE register SET password = '".$data['password']."' 
					WHERE user_id = ".$data['user_id'];
			 $db['master']->query($query);
			 return $db['master']->getLastID();
	}

	public function get_panchang_details($data) {
		global $db,$request;
		
			$query = "select * from ".trim($data['language'])."_panchang where date(date)='".$data['date']."' ";
			$panchang_details =  $db['master']->getOneRow($query);
			return $panchang_details;
	}
	public function get_panchang_location() 
	{
		global $db,$request;
		$query = "select location_name,location_id,language_name from location where is_active='1'";
		return $db['master']->getResults($query);
	}
	
	/* public function add_doubt($data) {
		global $db,$request;
		
		$data['question'] = str_replace("'", "\'", $data['question']);
		$data['question1'] = str_replace("'", "\'", $data['question1']);
		$data['question2'] = str_replace("'", "\'", $data['question2']);
		$data['question3'] = str_replace("'", "\'", $data['question3']);
		
		if($data['no_of_question'] == 3){
			
			for($i=1;$i<=$data['no_of_question'];$i++){
				$query = "INSERT INTO mtsoc_question (question_title, user_id,author_id, product_id,zodiac_id,question_category,  source, post_date) VALUES ('".$data['question'.$i]."', '".$data['user_id']."', '".$data['user_id']."', '".$data['product_id']."', '".$data['zodiac_id']."', '".$data['question_category']."', '".$data['source']."', NOW())";		
			
				$db['master']->query($query);
			}
			return true;
		}else{
		
			$query = "INSERT INTO mtsoc_question (question_title, user_id,author_id, product_id,zodiac_id,question_category,  source, post_date) VALUES ('".$data['question']."', '".$data['user_id']."', '".$data['user_id']."', '".$data['product_id']."', '".$data['zodiac_id']."', '".$data['question_category']."', '".$data['source']."', NOW())";		
		
			return $db['master']->query($query);
		}
	} */
	
	public function add_doubt($data) {
		global $db,$request;
		
		if($data['tob'] != ''){
			$query = "UPDATE register SET TOB = '".$data['tob']."' 
					WHERE user_id = ".$data['user_id'];
			 $db['master']->query($query);
		}
		
		foreach($data['question'] as $val){
			if($val!='on')
			{
				$query = "INSERT INTO mtsoc_question (question_title, user_id,author_id, product_id,zodiac_id,question_category,  source, post_date,trans_id,ques_status) VALUES ('".$val."', '".$data['user_id']."', '".$data['user_id']."', '".$data['product_id']."', '".$data['zodiac_id']."', '".$data['question_category']."', '".$data['source']."', NOW(),'".$data['trans_id']."','waiting')";
				$db['master']->query($query);
			}
		}
		return true;
	}
	
	public function get_doubts($data) {
		global $db,$request;
		
		
		
		$ques_query = "SELECT q.id AS question_id,q.user_id, q.zodiac_id, q.question_category, q.question_title, q.post_date, q.ques_status, a.answer_content, a.post_date AS ans_date ,q.rejected_reason
		FROM mtsoc_question q 
		LEFT JOIN mtsoc_answer a ON q.id = a.question_id
		WHERE q.user_id = '".$data['user_id']."' and q.ques_status NOT IN ('pending','waiting') ";
		
		if(isset($data['question_id']) && !empty($data['question_id'])) {
			$ques_query .= " AND q.id = '".$data['question_id']."'";
		}
		$ques_query .= " ORDER BY q.id DESC";
		if(isset($data['limit']) && $data['limit']!=''){$ques_query .= ' LIMIT '.$data['limit'];}
		$ques_result = $db['master']->getResults($ques_query);

		return $ques_result;
		
	}
	
	
	public function get_predefined_questions($data) {
		console ( LOG_LEVEL_INFO, 'get_predefined_questions request data : '. var_export($data, true));
		global $db,$request;
		
		$query = "SELECT * FROM predefined_questions WHERE `is_active`=1 and  question_category = '".$data['question_category']."'";
			
			return $db['master']->getResults($query);
	}
	
	public function get_tarot_category_name($data) 
	{
		console ( LOG_LEVEL_INFO, 'get_tarot_category_name request data : '. var_export($data, true));
		global $db,$request;
		$query = "SELECT DISTINCT(name_of_card) FROM tarot_content WHERE  tarot_type = '".$data['tarot_type']."' and `is_active`=1 order by rand() limit 0,11" ;
		return $db['master']->getResults($query);
	}
	public function get_tarot_category_content($data) 
	{
		console ( LOG_LEVEL_INFO, 'get_tarot_category_content request data : '. var_export($data, true));
		global $db,$request;
		$query = "SELECT * FROM tarot_content WHERE tarot_type = '".$data['tarot_type']."' and name_of_card = '".$data['name_of_card']."' and `is_active`=1 order by rand()";
		return $db['master']->getOneRow($query);
	}
	public function get_profile($data) 
	{
		global $db,$request;
		//$query = "SELECT * FROM register  WHERE user_id='".$data["user_id"]."'";
		$query = "SELECT * FROM register r left join astrologer_details ad on r.ast_id=ad.ast_id WHERE r.user_id='".$data["user_id"]."' and r.status = 'active'";
		$result = $db['master']->getOneRow($query);
		return $result;
	}
	public function submit_profile($data,$file_name) 
	{
		global $db,$request;
		console ( LOG_LEVEL_INFO, 'submit_profile : '. var_export($data, true));
		$add_column = "";
		if($file_name != ""){
			$add_column = " image_path='".$file_name."'";
			$query = "UPDATE register SET  ".$add_column." WHERE user_id='".$data["user_id"]."'";
		}
		elseif($data["msisdn"]!='')
		{
		$query = "UPDATE register SET birth_place='".$data["pob"]."',zodiac_sign= '".$data["sign"]."',email_id='".$data["email"]."',msisdn='".$data["msisdn"]."'  WHERE user_id='".$data["user_id"]."'";
		}
		
		$result = $db['master']->query($query);
		if($data["role"]=='astrologer'){
			$fetch_ast = $db['master']->getOneRow("select * from register where user_id='".$data["user_id"]."' "); 
			$upd_astr = $db['master']->query("UPDATE astrologer_details SET ast_email='".$data["email"]."',ast_msisdn='".$data["msisdn"]."',experience='".$data["astro_exp"]."',method='".$data["astro_method"]."',language='".substr($data["astro_lang"], 0, -2)."'  WHERE ast_id='".$fetch_ast["ast_id"]."'");
		}
		return $result;
	}
	
	public function get_mysubs($data) {
		global $db,$request;		
		
		$query = "select pur.*,pd.*,datediff(expiry_date,curdate()) as date_diff from purchased_details pur join product_details pd on pur.product_id=pd.product_id and pur.user_id = '".$data['user_id']."' and pur.is_active=1";		
		
		$query .= "  order by pur.purchase_id asc";
		
		$sub_result = $db['master']->getResults($query);

		return $sub_result;
	}
	public function get_slot_details($data) 
	{
		global $db,$request;
		$query = "select * from slot_master where is_active='1'";
		$slot_details =  $db['master']->getResults($query);
		return $slot_details;
	}
	public function insert_astrologer_availability($data)
	{
		global $db,$request;
		$slot_id = explode(",",$data['slot_id']);
		foreach($slot_id as $sl_id){
			if($sl_id!=''){
			$query_01 = 'INSERT INTO `astrologer_availability` (`avl_date`,`ast_id`,`slot_id`)VALUES("'.$data['avail_date'].'","'.$data['astro_id'].'","'.$sl_id.'")';
			$db['master']->query($query_01);
			}
		}
		return true;
	}
	/* public function get_astro_available_slot($data) 
	{
		global $db,$request;
		
		$query = 'select * from astrologer_availability av,slot_master sd,astrologer_booking_details abd where av.slot_id=sd.slot_id  and av.avl_id = abd.avl_id  and ast_id =  "'.$data['astro_id'].'"';
		if(isset($data['avail_date']) && $data['avail_date'] != '' ){
			$query .= ' and avl_date="'.$data['avail_date'].'"';
		}
		
		
		$slot_details =  $db['master']->getResults($query);
		return $slot_details;
	} */
	public function insert_req_booking_appoinment($data)
	{
		global $db,$request;
		console ( LOG_LEVEL_INFO, 'avail_id_astro: '. var_export($astro_avail_id, true));
			if($data['avail_id']!='')
			{ 
				$query_ins = 'INSERT INTO `astrologer_booking_details`(`user_id`,`avl_id`,`status`)VALUES(
							 "'.$data['user_id'].'", "'.$data['avail_id'].'","booked")';
				 $results_astro_booking = $db['master']->query($query_ins);
			}
	
		return $results_astro_booking;
	}
	
	public function get_astrologer_details($data) 
	{
		global $db,$request;
		$query = 'select * from astrologer_details ad left join register r on r.ast_id=ad.ast_id where 1=1';
		if(isset($data['astro_id']) && $data['astro_id'] != '' ){
			$query .= ' AND ad.ast_id = "'.$data['astro_id'].'"';	
		}
		if(isset($data['astro_lang']) && $data['astro_lang'] != '' ){
			$query .= ' AND ad.language like "%'.$data['astro_lang'].'%"';	
		}
		if(isset($data['qualification']) && $data['qualification'] != '' ){
			$query .= ' AND ad.qualification like "%'.$data['qualification'].'%"';	
		}
		if(isset($data['astro_search']) && $data['astro_search'] != '' ){
			$query .= ' AND ad.ast_name like "%'.$data['astro_search'].'%"';	
		}
		
		$astro_details =  $db['master']->getResults($query);
		
		return $astro_details;
	}
	public function get_astro_available_date($data) 
	{
		global $db,$request;
		$query = 'select *,day(avl_date) AS date_val,SUBSTRING(dayname(avl_date),1,3) as day_name,SUBSTRING(monthname(avl_date),1,3) as avail_mon from astrologer_availability where  ast_id =   "'.$data['astro_id'].'" and avl_date >= CURDATE() and avl_date<=DATE_ADD(CURDATE(), INTERVAL 6 DAY) group by avl_date';
		$astro_avail_date =  $db['master']->getResults($query);
		return $astro_avail_date;
	}//vignesh change
	public function get_astro_avail_date_timings($data) 
	{
		global $db,$request;
		$query = '	SELECT av.avl_id,av.avl_date,av.ast_id,av.slot_id,sm.in_time,sm.out_time,abd.status,sm.display_time FROM astrologer_availability av left join slot_master sm on av.slot_id=sm.slot_id  left join  astrologer_booking_details abd on av.avl_id = abd.avl_id  where 1=1 and av.ast_id="'.$data['astro_id'].'"';
		
		if(isset($data['avail_date'])  && $data['avail_date']!=''){
			$query .=' and av.avl_date="'.$data['avail_date'].'"';
		}
		
	$astro_avail_date_timings =  $db['master']->getResults($query);
		return $astro_avail_date_timings;
	}//vignesh change
	
	public function get_astro_appoinments($data) 
	{
		global $db,$request;
		$query = '	SELECT av.avl_id,av.avl_date,av.ast_id,av.slot_id,sm.in_time,sm.out_time,sm.display_time,abd.status FROM astrologer_availability av left join slot_master sm on av.slot_id=sm.slot_id  left join  astrologer_booking_details abd on av.avl_id = abd.avl_id  where 1=1 and av.ast_id="'.$data['astro_id'].'" and av.avl_date >= CURDATE() and av.avl_date<=DATE_ADD(CURDATE(), INTERVAL 6 DAY) and sm.is_active="1"';
		
		if(isset($data['avail_date'])  && $data['avail_date']!=''){
			$query .=' and av.avl_date="'.$data['avail_date'].'"';
		}
		
	$astro_avail_date_timings =  $db['master']->getResults($query);
		return $astro_avail_date_timings;
	}
	
	public function get_astrologerbooked_details($data) 
	{
		global $db,$request;
		$query = 'select ast.ast_id,ast.ast_name,date(b.booked_date) as date from astrologer_booking_details b join astrologer_availability av  on av.avl_id=b.avl_id join astrologer_details ast on ast.ast_id=av.ast_id where b.user_id="'.$data['user_id'].'" ;';
		
		$astro_bookeddetails =  $db['master']->getResults($query);
		
		return $astro_bookeddetails;
	}
	
	public function fetch_astro_available_slots($data) 
	{
		global $db,$request;
		$query = 'select sm.slot_id,sm.display_time,aa.avl_date from slot_master sm left join astrologer_availability aa on sm.slot_id=aa.slot_id and aa.avl_date="'.$data['avl_date'].'" and aa.ast_id="'.$data['astro_id'].'" where sm.is_active="1"';
  
															 
														 
   
		
		$astro_avail_slots =  $db['master']->getResults($query);
		return $astro_avail_slots;
	}
	
	public function general_calendar_details($data) 
	{
		global $db,$request;
		$query = 'select * from general_calendar where `date`="'.$data['calendar_date'].'"';
		
		$calendar_details =  $db['master']->getOneRow($query);
		
		return $calendar_details;
	}
	
	public function get_language() 
	{
		global $db,$request;
		$query = 'select * from language where is_active="1"';
		
		$lang_details =  $db['master']->getResults($query);
		
		return $lang_details;
	}
}
?>
