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
 * @filename:	member.service.class.inc
 * @filetype:	PHP
 * @filedesc:   This file contains the controller for members Module
 * 				which select the action or state for model depends
 * 				upon the user input given to the controller.
 * 
 *
 */

session_start();
class QaController{
	/**
	 * Description:
	 * This is controller for user Module,it will decide the action  to be given to 
	 * the model class depends upon the action parameter given to the
	 * controller.
	 * 
	 * Parameters:
	 *	null		: null
	 * Return Value: 
	 *	null		: null
	*/
	function __construct(){
		
		if(!mtutor_is_loggedin()) {
			header('location: '.APPLICATION_URL);
		}
		
		global $request, $tsResponse, $library;
		//Collecting the action parameter from request.
		$qa_model	= new QaModel();
		$action		= strtolower ( trim ( $request ['action'] ) );
		$user		= mtutor_current_user();
		$qa_model->status_change_onload(DEFAULT_RESPOND_TIME);
		
		switch ($action) {
			case "dashboard" :
				include(ROOT_DIR.'gateway/aad/header.php');
				$default_respond_in = explode(',', CUSTOM_RESPOND_TIME);
				$user_type			= $user->type;
				if( isset($request['filter']) && !empty($request['filter']) ) {
					$filter = $request['filter'];
				} else {
					$filter = '';
				}
				if ($user_type == 'SME') {
					if(isset($request['show']) && $request['show'] == 're-assigned') {
						$display_warning	= 'Question is Re-Assigned and your Answer Content is saved as Draft. Please Contact Moderator to assign the question to you so that you can publish.';
					}
					$results	= $qa_model->get_all_qa('', $user->id, $filter);
					
				} elseif ($user_type == 'MODERATOR') {
					$results	= $qa_model->get_all_qa('', '', $filter);
					//$branches	= $qa_model->getBranchList();
				}
				
				include(ROOT_DIR.'gateway/aad/dashboard.php');
				include(ROOT_DIR.'gateway/aad/edit_modal.php');
				include(ROOT_DIR.'gateway/aad/footer.php');
				break;
			case "chatroom" :
				include(ROOT_DIR.'gateway/aad/header.php');
				if($user->type == 'MODERATOR') {
					$result	= $qa_model->getChatRoomList();
					include(ROOT_DIR.'gateway/chatroom.php');
				} else {
					echo 'You don\'t have permission to access this page.';
				}
				include(ROOT_DIR.'gateway/aad/footer.php');
				break;
			case "rejected_answers" :
			include(ROOT_DIR.'gateway/aad/header.php');
				if($user->type == 'SME') {
					$result = $qa_model->get_rejected_answers('', $user->id);
					include(ROOT_DIR.'gateway/rejected_questions.php');
				}elseif($user->type == 'MODERATOR') {
					$question_id        = $request['question_id'];
					$result = $qa_model->get_rejected_answers($question_id, '');
					include(ROOT_DIR.'gateway/rejected_questions.php');
				}
				else {
					echo 'You don\'t have permission to access this page.';
				}
				include(ROOT_DIR.'gateway/aad/footer.php');
				break;
			case "answer" :
				include(ROOT_DIR.'gateway/aad/header.php');
				if($user->type == 'SME') {
					$ques_id		= $request['id'];
					$check_draft	= (object)$qa_model->get_qa($ques_id);
					if( in_array($check_draft->question_status, array('assigned', 'in_progress', 'respond_in', 'draft'), true) && $check_draft->assigned_to  == $user->id ) {
						if(!in_array($check_draft->question_status, array('respond_in', 'draft'), true)) {
							$qa_model->update_question_status($ques_id, 'in_progress');
						}
						if($check_draft->question_status == 'respond_in') {
							$responded	= strtotime($check_draft->modified_time.' + '. $check_draft->respond_in .' minute');
						} else {
							$responded	= strtotime($check_draft->assigned_time.' + '. DEFAULT_RESPOND_TIME .' minute');
						}
						$time_to_answer	= date('Y-m-d H:i:s', $responded);
						$current_time	= date('Y-m-d H:i:s');
						$interval		= strtotime($time_to_answer) - strtotime($current_time);
						$seconds		= round($interval);
						
						if ($check_draft->anscount > 0) {
							$draft			= 1;
							$answer			= $qa_model->asked_question($ques_id);
							$result			= (object)$answer;
							$ans_result		= $check_draft;
							$ans_content	= $ans_result->answer_content;
							$ans_id			= $ans_result->ans_id;
							$attach			= $ans_result->attach;
						} else {
							$draft		= 0;
							$question	= $qa_model->get_all_qa($ques_id, '', '');
							$result		= (object)reset($question);
						}
					}
					if(isset($request['show']) && !empty($request['show'])) {
						$error	= $request['show'];
						if($error == 'size') {
							$display_error = 'File size should be less that 5MB.';
						} elseif($error == 'type') {
							$display_error = 'File type you uploaded is not allowed.';
						}
					}
					include(ROOT_DIR.'gateway/aad/answer.php');
				} else {
					echo 'You don\'t have permission to access this page.';
				}
				include(ROOT_DIR.'gateway/aad/footer.php');
				break;
			case "similar_questions" :
				include(ROOT_DIR.'gateway/aad/header.php');
				if($user->type == 'MODERATOR' || $user->type == 'SME') {
					$qa_model->delete_answer($request['id']);
					$result	= $qa_model->get_similar_qa($request['id'], '');
					include(ROOT_DIR.'gateway/aad/similar_questions.php');
				} else {
					echo 'You don\'t have permission to access this page.';
				}
				include(ROOT_DIR.'gateway/aad/footer.php');
				break;
			case "post_answer" :
				include(ROOT_DIR.'gateway/aad/header.php');
				$post_action	= $request['post_answer'];
				$faculty_id		= $request['faculty_id'];
				$question_id	= $request['question_id'];
				$answer			= trim($request['answer-form-data']);
				$error			= '';
				$question		= (object)$qa_model->get_qa($question_id);
				if($post_action == 'Save as draft' || $question->assigned_to != $faculty_id) {
					$answer_post_type = 'draft';
					if($question->assigned_to != $user->id) {
						$display_warning	= 're-assigned';
					}
				} else {
					$answer_post_type = 'pending';
				}
				if(isset($_FILES['mtutor-ans-attachment']['name']) && !empty($_FILES['mtutor-ans-attachment']['name'])) {
					$upload_path	= '/storage/uploads/answer_attachment/';
					$file_name		= $_FILES['mtutor-ans-attachment']['name'];
					$file_size		= $_FILES['mtutor-ans-attachment']['size'];
					$file_tmp		= $_FILES['mtutor-ans-attachment']['tmp_name'];
					$file_type		= $_FILES['mtutor-ans-attachment']['type'];
					$expensions		= array('application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'image/gif');
					$file_ext		= strtolower(end(explode('.', $file_name)));
					$file_name_dir	= $user->id . '_' . rand(10000, 99999) . '_' . time() . '.' . $file_ext;
					if( !in_array($file_type, $expensions)=== false ) {
						if( $file_size <= 5242880 ) {
							move_uploaded_file($file_tmp, ROOT_DIR . $upload_path . $file_name_dir);
						} else {
							$error	= 'size';
						}
					} else {
						$error	= 'type';
					}
				} else {
					$error = '';
				}

				if (isset($answer) && $answer != '' && $error == '') {
					$store_data		= $qa_model->post_answer($question_id, $faculty_id, $answer, $answer_post_type);
					if(!empty($_FILES['mtutor-ans-attachment']['name'])) {
						$file_update	= $qa_model->update_answer_attachment($question_id, $faculty_id, $file_type, $file_name_dir);
					}
					if (isset($store_data) && $answer_post_type == 'pending') {
						$args = array(
							'faculty_id'	=> $faculty_id,
							'question_id'	=> $question_id
						);
						//send_email_sms('answered', $args);
					}
					if($question->assigned_to != $user->id) {
						header('location: '.APPLICATION_URL.'gateway/action.php?application=qa&action=dashboard&show='.$display_warning);
					} else {
						header('location: '.APPLICATION_URL.'gateway/action.php?application=qa&action=dashboard');
					}
				} else {
					header('location: '.APPLICATION_URL.'gateway/action.php?application=qa&action=answer&id='.$question_id.'&show='.$error);
				}
				include(ROOT_DIR.'gateway/aad/footer.php');
				break;
				
			case "get_user_astro_details" :
			
					$result	= $qa_model->get_user_astro_details($request);
					//echo '<pre>';print_r($result);
					if($result){
						$returndata = array(
						'returncode'=>'200',
						'returndata'=>$result
						);
					}else{
						$returndata = array(
						'returncode'=>'201',
						'returndata'=>'No Data Found'
						);
					}
					//echo '<pre>';print_r($returndata);exit;
					echo json_encode($returndata);
					//exit;
				break;	
		}
		
	}
	
}
?>