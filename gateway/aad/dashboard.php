<?php if(isset($display_warning)) { ?>
	<script type="text/javascript">alert('<?php echo $display_warning; ?>');</script>
<?php } ?>
<div class="filter_content">
	<select id="filter_qa" class="filter_qa form-control" data-location="<?php echo APPLICATION_URL.'gateway/action.php?application=qa&action=dashboard&filter='; ?>">
		<option value='' <?php if(empty($filter)) echo 'selected'; ?>>All</option>
		<?php if($user_type == 'MODERATOR') { ?>		
		<option value="not_assigned"<?php if($filter=='not_assigned') echo 'selected'; ?>>Not Assigned</option>
		<option value="rejected"<?php if($filter=='rejected') echo 'selected'; ?>>Question Rejected</option> 
		<option value="ans_rejected"<?php if($filter=='ans_rejected') echo 'selected'; ?>>Answer Rejected</option>
		<?php } if($user_type == 'MODERATOR' || $user_type == 'SME') { ?>
		<option value="assigned" <?php if($filter=='assigned') echo 'selected'; ?>>Assigned</option>
		<option value="in_progress"<?php if($filter=='in_progress') echo 'selected'; ?>>In Progress</option>
		<option value="published"<?php if($filter=='published') echo 'selected'; ?>>Published</option>
		<option value="draft"<?php if($filter=='draft') echo 'selected'; ?>>In Draft</option>
		<option value="respond_in"<?php if($filter=='respond_in') echo 'selected'; ?>>Respond In</option>
		<option value="pending"<?php if($filter=='pending') echo 'selected'; ?>>Pending Approval</option>
		<?php } ?>		
	</select>
</div>
<table id="mt-dashboard-table" class="table"  data-sort-order="desc"><!--data-sort-name="id"-->
	<thead>
		<tr>
			<th data-class="tcol-id" data-field="id" data-title="ID" data-sortable="true" data-searchable="false" data-width="20" data-events="dashboard_action">ID</th>
			<th data-class="tcol-question" data-field="question" data-title="Question" data-sortable="true" data-searchable="true" data-width="200" data-events="dashboard_action">Question</th>
			<?php if($user_type == 'MODERATOR' || $user_type == 'SME') { ?>
				<th data-class="tcol-posted-by" data-field="posted_by" data-title="Posted By" data-sortable="true" data-searchable="true" data-events="dashboard_action">Source</th>
			<?php } ?>
			<th data-class="tcol-subject" data-field="subject" data-title="Category" data-sortable="true" data-searchable="true" data-events="dashboard_action">Category</th>
			<!--<th data-class="tcol-topic" data-field="topic" data-title="Topic" data-sortable="true" data-searchable="false" data-events="dashboard_action">Topic</th>-->
			<th data-class="tcol-status" data-field="status" data-title="Status" data-sortable="true" data-searchable="false" data-events="dashboard_action">Status</th>
			<?php if($user_type == 'MODERATOR') { ?>
				<th data-class="tcol-assigned-to" data-field="assigned_to" data-title="Assigned To" data-sortable="true" data-searchable="true" data-events="dashboard_action">Assigned To</th>
				<th data-class="tcol-assigned-time" data-field="assigned_time" data-title="Assigned Time" data-sortable="true" data-searchable="false" data-width="100" data-events="dashboard_action">Assigned Time</th>
			<?php } ?>
			<th data-class="tcol-posted-time" data-field="posted_time" data-title="Posted Time" data-sortable="false" data-searchable="true" data-width="100" data-events="dashboard_action">Posted Time</th>
			<th data-class="tcol-action" data-field="action" data-title="Action" data-sortable="false" data-searchable="false" data-width="150" data-events="dashboard_action">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach($results as $result) {
				$result	= (object)$result;
		?>
			<tr class="question-item" data-id="<?php echo $result->id;?>">
				<td><span class="student_details" style="cursor:pointer;" data-question_id="<?php echo $result->id;?>" data-application="<?php echo 'Astromart'?>" data-student_id="<?php echo $result->author_id;?>"><?php echo $result->id;?></span>
	<?php if($user_type == 'MODERATOR'){?>			
				<br><br><span class="question_edit" data-mtutor_id="<?php echo $result->mtutor_id;?>" data-question_id="<?php echo $result->id;?>" data-question="<?php echo stripslashes($result->question_title);?>" style="cursor:pointer;color:blue;" ><button type="button" class="btn btn-primary">
				  Edit
				</button></span>
	<?php }?>
</td>
				<td>
					<p class="item-title mod-title-tooltip" <?php if(strlen($result->question_title) > 150) { ?>data-toggle="tooltip" data-placement="right" title="<?php echo stripslashes($result->question_title); ?>"<?php } ?>><b><?php echo mtutor_excerpt_length( stripslashes($result->question_title), 150, '...' ); ?></b></p>
					<p class="item-title mod-content-tooltip" <?php if(strlen($result->question_content) > 150) { ?>data-toggle="tooltip" data-placement="right" title="<?php echo stripslashes($result->question_content); ?>"<?php } ?>><b><?php echo mtutor_excerpt_length( stripslashes($result->question_content), 150, '...' ); ?></b></p>
					
					<?php if(isset($result->ques_attachment) && !empty($result->ques_attachment)) { ?>
						<a class="mt-btn mt-btn-xs mt-btn-default" href="<?php echo $result->ques_attachment;?>" target="_blank" alt="View Attachment" title="View Attachment">Attachment <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span></a>
					<?php } ?>
					<?php if((isset($result->ans_rejected_reason) && !empty($result->ans_rejected_reason)) && ( $user_type == 'MODERATOR')) { ?>
					<br><br>
					<a href="<?php echo APPLICATION_URL.'gateway/action.php?application=qa&action=rejected_answers&question_id='.$result->id; ?>">View Previous Rejections</a>
						<!--<button type="button" class="mt-btn mt-btn-xs mt-btn-bordered mt-btn-primary rejected-answer-popup" data-toggle="modal" data-target="#rejected-answer-popup-<?php echo $result->id;?>">View Previous Rejections</button>
						<div class="modal fade modal-primary" id="rejected-answer-popup-<?php echo $result->id;?>" tabindex="-1" role="dialog" aria-labelledby="Question Modal Label">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
										<h4 class="modal-title">Reason</h4>
									</div>
									<div class="modal-body"></div>
								</div>
							</div>
						</div>-->
					<?php } ?>
				</td>
				<?php if($user_type == 'MODERATOR' || $user_type == 'SME') { ?>
					<td>
						<!--<span><?php if($result->source != '') { echo $result->student_first_name.' '.$result->student_last_name.' - '.ucfirst($result->source); } else { echo $result->student_first_name.' '.$result->student_last_name; }?></span>-->
						<span><?php if($result->source != '') { echo 'Astromart - '.ucfirst($result->source); } else { echo $result->student_first_name.' '.$result->student_last_name; }?></span>
					</td>
				<?php } ?>
				<td><?php echo $result->question_category; ?></td>
				<!--<td><?php echo $result->topic_name; ?></td>-->
				<td>
					<?php
						switch( $result->ques_status ) {
							case 'assigned' :
								echo '<span class="mt-text-success">Assigned</span>';
							break;
							case 'in_progress' :
								echo '<span class="mt-text-success">In Progress</span>';
							break;
							case 'respond_in' :
								$responded	= strtotime($result->modified_time.' + '. $result->ques_respond_in .' minute');
								$remaining	= $responded - strtotime(date('Y-m-d H:i:s'));
								if($remaining < 60)
									$remaining_disp = round($remaining). ' Seconds';
								else
									$remaining_disp = round($remaining / 60). ' Minutes';
								echo '<span class="mt-text-success">Respond in '. $remaining_disp  .'</span>';
							break;
							case 'draft' :
								echo '<span class="mt-text-success">Draft</span>';
							break;
							case 'published' :
								echo '<span class="mt-text-success">Published</span>';
							break;
							case 'pending' :
								echo '<span class="mt-text-success">Pending Approval</span>';
							break;
							case 'rejected' :
								echo '<span class="mt-text-success">Question Rejected</span>';
?>
								<button type="button" class="mt-btn mt-btn-xs mt-btn-bordered mt-btn-primary" data-toggle="modal" data-target="#rejected-question-reason-popup-<?php echo $result->id;?>">View Reason</button>
								<div class="modal fade modal-primary" id="rejected-question-reason-popup-<?php echo $result->id;?>" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
												<h4 class="modal-title">Reason for Rejection</h4>
											</div>
											<div class="modal-body">
												<p><?php echo $result->ques_rejected_reason; ?></p>
												<span>Time : <?php 
													$rej_time		= $result->ques_rejected_time;
													$rej_timestamp	= strtotime($rej_time);
													echo date( 'd-m-y h:i a',  $rej_timestamp); ?></span>
											</div>
										</div>
									</div>
								</div>
<?php
							break;												
							default :
								echo '<span class="mt-text-error">Not Assigned</span>';
						}
					?>
				</td>
				<?php if($user_type == 'MODERATOR') { ?>
					<td>
						<?php
							if($result->assigned_to != 0 || $result->assigned_to != '' ) {
								echo $result->professor_first_name. ' ' . $result->professor_last_name;
								if($result->ques_status=='assigned' || $result->ques_status=='in_progress' || $result->ques_status=='draft' || $result->ques_status=='respond_in') { ?>
								<div class="force-reassign">
									<button type="button" class="mt-btn mt-btn-xs mt-btn-bordered mt-btn-primary" id="force_reassign" value="<?php echo $result->id;?>">Force Reassign</button>
								</div>
								<?php }
						 	}
						 ?>
						<span class="item-faculty-load"></span>
					</td>
					<td>
						<?php
							if($result->assigned_time != '0000-00-00 00:00:00') {
								$time		= $result->assigned_time;
								$timestamp	= strtotime($time);
								if($timestamp != ''){
									echo date( 'd-m-y h:i a',  $timestamp);
								}else {
									echo '';
								}
							}
						?>
					</td>
				<?php } ?>
				<td>
					<?php
						if($result->question_post_date != '0000-00-00 00:00:00') {
							$post_time	= $result->question_post_date;
							$timestamp	= strtotime($post_time);
							echo date('d-m-y h:i a', $timestamp);
						}
					?>
				</td>
				<td>
					<?php
						$time			= strtotime( $result->assigned_time );
						$custom_time	= DEFAULT_RESPOND_TIME;
						$reassign_time	= date('d-m-Y h:i:s a', strtotime('+'.$custom_time.' minutes', $time));
						$assigned_date	= date('Y-m-d h:i:s', strtotime('+'.$custom_time.' minutes', $time));
						$respond_time	= date('d-m-Y H:i:s', strtotime('+'.(int) $result->ques_respond_in.' minutes', strtotime( $result->modified_time ) ));
						$today			= date("Y-m-d h:i:s");
						if(($result->ques_status == 'assigned' || $result->ques_status == 'in_progress') && (strtotime($assigned_date) > strtotime($today)) && $user_type == 'MODERATOR') {
					?>
						<span class="mt-text-error">Re-Assign after <?php echo $reassign_time;?></span>
					<?php } else if( $result->ques_status == 'respond_in' && (strtotime($respond_time) > strtotime($today)) && $user_type == 'moderator') { ?>
						<span class="mt-text-error">Re-Assign after <?php echo  date('d-m-Y h:i A', strtotime($respond_time));?></span>
					<?php } elseif( $result->ques_status == 'published' || $result->ques_status == 'pending' ) { ?>
						<button type="button" class="mt-btn mt-btn-xs mt-btn-bordered mt-btn-primary" data-toggle="modal" data-target="#view-answer-popup-<?php echo $result->id;?>">View Answer</button>
						<div class="modal fade modal-primary" id="view-answer-popup-<?php echo $result->id;?>" tabindex="-1" role="dialog" aria-labelledby="Question Modal Label">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
										<h4 class="modal-title">View Answer</h4>
									</div>
									<div class="modal-body">
										<?php echo stripslashes($result->answer_content); ?>
										<?php
											if(isset($result->ans_attachment) && !empty($result->ans_attachment)) {
										?>
												<a class="mt-btn mt-btn-xs mt-btn-default" href="<?php echo APPLICATION_URL.'storage/uploads/answer_attachment/'.$result->ans_attachment;?>" target="_BLANK" alt="View Attachment" title="View Attachment">Attachment <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span></a>
										<?php } ?>
										<p>Time : <?php echo date('j-M-Y g:i A', strtotime($result->ans_post_date)); ?></p>
										<?php if($result->ques_status == 'pending' && ($user_type == 'MODERATOR')) {?>
											<div class="reject-answer-form" data-id="<?php echo $result->ans_id; ?>" data-mtutor_id="<?php echo $result->mtutor_id;?>" data-application="<?php echo $result->application_type;?>">
												<div class="form-group">
													<label class="form-label">Reason for Answer Rejection</label>
													<textarea class="input-sm" maxlength="1000" name="answer_rejected_reason" placeholder="Please enter the valid reason"></textarea>
												</div>
								<button type="submit" class="mt-btn mt-btn-xs mt-btn-success approve-answer-btn">Approve</button>
								<button type="submit" class="mt-btn mt-btn-xs mt-btn-danger reject-answer-btn">Reject</button>
											</div>

										<?php } ?>
					<?php if($result->ques_status == 'published'){?>
					<div class="form-group">
						<label class="form-label">Reason for Answer Rejection</label>
						<textarea class="input-sm re_answer_reason_<?php echo $result->id;?>" maxlength="1000" name="re_answer_reason" placeholder="Please enter the valid reason"></textarea>
					</div>
						<button type="submit" class="mt-btn mt-btn-xs mt-btn-danger re_answer" data-id="<?php echo $result->ans_id; ?>" data-mtutor_id="<?php echo $result->mtutor_id;?>" data-application="<?php echo $result->application_type;?>"  >Re Assign</button>
												<?php } ?>											
										</div>
									</div>
								</div>
							</div>
				
					<?php } elseif(($result->ques_status =='' && (!$result->assigned_to)) && ($user_type == 'MODERATOR')) { ?>
						<div class="item-assignee-wrap">
							<div class="form-group"  style="display: <?php if($_REQUEST["view"] != $result->id){echo "none;";}?>">
								<select class="branch-selector input-sm" name="branch_id">
									<option value="">Select Branch</option>
									<option value="all_branches">All Branches</option>
									<?php foreach($branches as $branch) { $branch = (object)$branch; ?>
										<!--<option value="<?php echo $branch->branch_id ;?>"><?php echo $branch->branch_name; ?></option>-->
									<?php } ?>
								</select>
							</div>
							<div class="form-group"  style="display: <?php if($_REQUEST["view"] != $result->id){echo "none;";}?>">
								<select class="assignee-selector input-sm" name="assignee_id">
									<option value="">Select Assignee</option>
								</select>
							</div>
							<button type="submit" class="mt-btn mt-btn-xs mt-btn-success assign-question-btn" style="display: <?php if($_REQUEST["view"] != $result->id){echo "none;";}?>" >Assign</button>
							<button type="submit" class="mt-btn mt-btn-xs mt-btn-danger" data-toggle="modal" data-target="#reject-question-form-popup-<?php echo $result->id;?>"  >Reject</button>
							<br>
							<a href="<?php echo APPLICATION_URL."gateway/action.php?application=qa&action=similar_questions&mtutor_id=".$result->mtutor_id."&id=".$result->id;?>" style="font-size:10px" class="text-primary">View Similar Questions</a>
							<div class="modal fade modal-primary" id="reject-question-form-popup-<?php echo $result->id;?>" tabindex="-1" role="dialog" aria-labelledby="Question Modal Label">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
									<input type="hidden" name="mtutor_id" id="mtutor_id" value="<?php echo $result->mtutor_id;?>">
									<input type="hidden" name="application_type" id="application_type" value="<?php echo $result->application_type;?>">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
											<h4 class="modal-title">Reason</h4>
										</div>
										<div class="modal-body">
											<div class="form-group">
												<label class="form-label" for="reject-question">Reason for Question Rejection</label>
												<textarea class="input-sm" maxlength="1000" name="question_reject_reason" placeholder="Please enter the valid reason"></textarea>
											</div>
											<button type="submit" class="mt-btn mt-btn-xs mt-btn-success reject-question-btn">Submit</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } elseif($user_type == 'SME') { ?>
						<?php if($result->ques_status == 'draft' || $result->anscount > 0) { ?>
							<a href="<?php echo APPLICATION_URL.'gateway/action.php?application=qa&action=answer&id='.$result->id; ?>" class="mt-btn mt-btn-xs mt-btn-default">Edit Draft</a>
						<?php } else { ?>
							<a href="<?php echo APPLICATION_URL.'gateway/action.php?application=qa&action=answer&id='.$result->id; ?>" class="mt-btn mt-btn-xs mt-btn-default"  style="display: <?php if($_REQUEST["view"] != $result->id){echo "none;";}?>">Answer</a>
							<?php if($result->ques_status == 'assigned') { ?>
								<div id="respondin-dropdown-wrap" class="mt-btn-group dropdown" role="group">
									<button id="respondin-dropdown-btn" type="button" class="mt-btn mt-btn-success mt-btn-xs" data-toggle="dropdown">Respond in<span class="caret"></span></button>
									<ul class="respondin-dropdown-menu dropdown-menu">
										<?php foreach ($default_respond_in as $value) { 
										$respond_in_time = $value;
										$hours = floor($respond_in_time / 60);
										$minutes = $respond_in_time % 60;
										if($hours>0 && $minutes>0){
											$time = $hours.' Hour(s) '.$minutes.' Minute(s)';
										} elseif($hours>0) {
											$time = $hours.' Hour(s) ';
										} elseif($minutes>0) {
											$time = $minutes.' Minutes(s)';
										}
										?>
											<li>
												<label><input type="radio" class="respond_time" name="respond_time" value="<?php echo $value;?>"> <?php echo $time;?></label>
											</li>
										<?php } ?>
										<li>
											<button type="button" class="mt-btn mt-btn-default mt-btn-xs respondin-btn" data-id="<?php echo $result->id; ?>">Submit</button>
										</li>
									</ul>
								</div>
							<?php } ?>
							<br>
							<a href="<?php echo APPLICATION_URL."gateway/action.php?application=qa&action=similar_questions&mtutor_id=".$result->mtutor_id."&id=".$result->id;?>" style="font-size:10px" class="text-primary">View Similar Questions</a>
							
							
						<?php } ?>
					<?php } else { ?>
						<p><?php echo 'Can\'t reassign'; ?></p>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
