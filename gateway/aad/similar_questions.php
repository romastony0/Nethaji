<div><button type="button" class="btn btn-info pull-right" style="padding:10px;" ><a href="<?php echo ($_SERVER['HTTP_REFERER']!="")?$_SERVER['HTTP_REFERER']."&view=".$_REQUEST['id']:"action.php?application=qa&action=dashboard&filter=not_assigned"."&view=".$_REQUEST['id'];?>" style="color: #000000;"><?php if($_SESSION['usertype'] == 'MODERATOR'){echo "Assign to SME";}else{{echo "Proceed to Answer";}}?> / Cancel</a></button></div><br><br>
<div id="mt-similar-questions" class="item-hentry">
	<?php if($result != 0) { ?>
		<?php foreach( $result as $res ) { $res = (object)$res; ?>
			<div class="question-wrap">
				<div class="question-head">
					<div class="author-avatar">
						<img src="<?php echo (!empty($res->stu_avatar)) ? APPLICATION_URL.'resources/'.$res->stu_avatar : APPLICATION_URL.'resources/images/profile/no_dp.png';?>">
					</div>
					<div class="question-title"><?php echo stripslashes($res->question_title); ?></div>
				</div>
				<div class="question-content"><?php echo stripslashes($res->question_content); ?></div>
				<?php if( isset($res->ques_attachment) && !empty($res->ques_attachment) ) { ?>
					<span class="entry-attachment">
						<a class="mt-btn mt-btn-xs mt-btn-default" href="<?php echo APPLICATION_URL.'storage/uploads/question_attachment/'.$res->ques_attachment;?>" target="_BLANK" alt="View Attachment" title="View Attachment">Attachment <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span></a>
					</span>
				<?php } ?>
				<span class="author-meta">
					<?php echo $res->student_name; ?> asked on <time><?php echo date("jS M Y, h:i A", strtotime($res->question_post_date)); ?></time>
				</span>
			</div>
			<div class="answer-wrap">
				<div class="answer-head">
					<div class="author-avatar">
						<img src="<?php echo (!empty($res->faculty_avatar)) ? APPLICATION_URL.'resources/'.$res->faculty_avatar : APPLICATION_URL.'resources/images/profile/no_dp.png';?>">
					</div>
					<div class="answer-content"><?php echo stripslashes($res->answer_content); ?></div>
					<?php if( isset($res->ans_attachment) && !empty($res->ans_attachment) ) { ?>
						<span class="entry-attachment">
							<a class="mt-btn mt-btn-xs mt-btn-default" href="<?php echo APPLICATION_URL.'storage/uploads/answer_attachment/'.$res->ans_attachment;?>" target="_BLANK" alt="View Attachment" title="View Attachment">Attachment <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span></a>
						</span>
					<?php } ?>
					<span class="author-meta">
						<?php echo $res->faculty_name; ?> from the Department of <span class="author-dept"><?php echo $res->branch_name; ?> answered on <time><?php echo date("jS M Y, h:i A", strtotime($res->ans_post_date)); ?></time>
					</span>
					<button type="submit" class="mt-btn mt-btn-xs mt-btn-success approve-similar-qa-btn" data-ans_id="<?php echo $res->ans_id?>" data-ques_id="<?php echo $request['id']?>" data-mtutor_id="<?php echo $request['mtutor_id']?>" data-user_type="<?php echo $_SESSION['usertype']; ?>">Approve</button>
				</div>				
			</div>			
			<span class="hr-line"></span>
		<?php } ?>
	<?php } else { ?>
		<div class="no-data">
			<span class="no-data-msg">No Matches Found <a style="float: right;" href="<?php echo APPLICATION_URL.'gateway/action.php?application=qa&action=dashboard';?>" class="text-danger">Back to Dashboard</a></span>
		</div>
	<?php } ?>
</div>
