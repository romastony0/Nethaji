<?php if(isset($display_warning)) { ?>
	<script type="text/javascript">alert('<?php echo $display_warning; ?>');</script>
<?php } ?>
<div id="mt-answer-form" class="item-hentry">
	<div class="question-wrap">
		<div class="item-question-head">
			<div class="author-avatar">
				<?php if($draft == 0){ ?>
					<img src="<?php echo (!empty($result->stu_avatar)) ? APPLICATION_URL.'resources/'.$result->stu_avatar : APPLICATION_URL.'resources/images/profile/no_dp.png';?>">
				<?php }else{ ?>
					<img src="<?php echo (!empty($result->student_avatar)) ? APPLICATION_URL.'resources/'.$result->student_avatar : APPLICATION_URL.'resources/images/profile/no_dp.png';?>">
				<?php } ?>
			</div>
			<div class="question-title">
				<?php echo stripslashes($result->question_title); ?>
			</div>
		</div>
		<p class="question-content"><?php echo stripslashes($result->question_content); ?></p>
		<div class="question-meta">
			<?php if( isset($result->qattach) && !empty($result->qattach) ) { ?>
				<a class="mt-btn mt-btn-default" href="<?php echo APPLICATION_URL.'resources/uploads/question_attachment/'.$result->qattach;?>" target="_BLANK" alt="View Attachment" title="View Attachment">Attachment <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span></a>
			<?php } ?>
			<span class="author-meta">
				<b><?php echo $result->student_first_name.' '.$result->student_last_name; ?></b> asked on 
					<?php if($draft == 0){ ?>
						<time><?php echo date("jS M Y, g:i A", strtotime($result->question_post_date)); ?></time>
					<?php }else{ ?>
						<time><?php echo date("jS M Y, g:i A", strtotime($result->question_date)); ?></time>
					<?php } ?>
				</span>
			</div>
	</div>
	<form id="answer-form" name="answer_form" method="POST" enctype="multipart/form-data" action="<?php echo APPLICATION_URL."gateway/action.php?application=qa&action=post_answer";?>" data-id="<?php echo ($draft == 1) ? $ans_id : 0; ?>">
		<div id="ansform-editor" data-timeout="<?php echo $seconds; ?>">
			<div><span>Characters:</span> <span id="chars_left">0</span><div class="pull-right"><span class="time-left"></span></div></div>
			<textarea id="answer-form-data" class="mt-tinymce-editor" name="answer-form-data">
				<?php if($draft == 1){ 
					echo $ans_content;
				} ?>
			</textarea>
		</div>
		<div class="attachment-uploader">
			<div style="font-weight: bold;">Upload File</div>
			<div class="text-danger">Allowed file type: jpg/jpeg, png, pdf</div>
			<div class="text-danger" >Max Size : 5MB</div>
			<input style="height: auto; width:350px; border:none;" type="file" name="mtutor-ans-attachment" id="mtutor-ans-attachment">
			<input type="hidden" name="faculty_id" value="<?php echo ($draft == 0) ? $result->assigned_to : $result->faculty_id;?>">
			<input type="hidden" name="question_id" value="<?php echo $result->id;?>">
			<?php 
				if($draft == 1){ 
					if(isset($attach) && !empty($attach)){ ?>
						<div class="draft-attachment">
							1 attachment is in draft. <a class="text-success answer-attachment" href="<?php echo APPLICATION_URL.'storage/uploads/answer_attachment/'.$attach;?>" target="_BLANK">View</a> | <a class="text-danger delete-attachment" href="javascript();">Delete</a>
						</div>
					<?php }
				}
			?>
		</div>
		<button class="mt-btn mt-btn-sm mt-btn-default" id="save-as-draft" value="Save as draft" name="post_answer">Save as draft</button>
		<button class="mt-btn mt-btn-sm mt-btn-primary" id="answer-form-btn" value="Post" name="post_answer">Post</button>
	</form>
</div>