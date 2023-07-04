(function($) {
	'use strict';
	$.mtsoc = $.mtsoc || {};

	$(document).ready(function() {
		$.mtsoc.init();
		$.mtsoc.faculty_save_draft();
		$.mtsoc.faculty_post_answer();
		$.mtsoc.similar_qa();
		$.mtsoc.filter_qa();
		$.mtsoc.periodic_call();
		$.mtsoc.approve_reject_chatroom();
		$.mtsoc.faculty_register();
		var periodic_check = function() {
			$.ajax({
				type	: 'POST',
				url		: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=periodic_check',
				global	: false,
				success	: function(data) {
					var response = JSON.parse(data);
					if(response != false) {
						if(response.type == 'MODERATOR') {
							if(response.data.new_ques > 0) {
								$('#mast-head').find('.notification').removeAttr('style');
								$('#mast-head').find('.new-ques').removeAttr('style');
								$('#mast-head').find('.new-ques').attr('href', mtsoc_vars.base_url + response.q_href);
								$('#mast-head').find('.new-ques').attr('title', response.q_title);
								$('#mast-head').find('.new-ques').html('Q'+response.data.new_ques);
							}
							if(response.data.new_ans > 0) {
								$('#mast-head').find('.notification').removeAttr('style');
								$('#mast-head').find('.new-ans').removeAttr('style');
								$('#mast-head').find('.new-ans').attr('href', mtsoc_vars.base_url + response.a_href);
								$('#mast-head').find('.new-ans').attr('title', response.a_title);
								$('#mast-head').find('.new-ans').html('A'+response.data.new_ans);
							}
						} else if(response.type == 'SME') {
							if(response.data.new_ques > 0) {
								$('#mast-head').find('.notification').removeAttr('style');
								$('#mast-head').find('.new-ques').removeAttr('style');
								$('#mast-head').find('.new-ques').attr('href', mtsoc_vars.base_url + response.href);
								$('#mast-head').find('.new-ques').attr('title', response.title);
								$('#mast-head').find('.new-ques').html('Q'+response.data.new_ques);
							}
						}
					}
				}
			});
		};
		setInterval(periodic_check, 5000);
		periodic_check();
	});
	$(document).click(function(){	
		$(".respondin-dropdown-menu").hide();
	});
	function CountCharacters() {
		var body = tinymce.get("answer-form-data").getBody();
		var content = body.textContent;
		return content.length;
	}
	
	$.mtsoc = {
		init: function() {
			$('#mt-dashboard-table').bootstrapTable({
				showHeader	: true,
				showColumns	: true,
				pagination	: true,
				search		: true,
				striped		: true,
				onAll		: function() {
					$('[data-toggle="tooltip"]').tooltip();
				}
			});

			// Bootstrap Table Fix
			if ( mtsoc_vars.user_type == 'SME' ) {
				var SearchPlaceholder = 'Search by Question';
			} else {
				var SearchPlaceholder = 'Search by Question, Assigned To';
			}

			$('.bootstrap-table .search input').attr('placeholder', SearchPlaceholder).css('min-width', '350px');
			
			$('.bootstrap-table .fixed-table-toolbar').prepend('<h3 class="pull-left table-title">Q&A Dashboard</h3>');
			
			tinymce.init({
				selector			: '.mt-tinymce-editor',
				removed_menuitems	: 'newdocument',
				height				: 200,
				image_advtab		: true,
				language			: 'en',
				menubar				: false,
				statusbar			: false,
				plugins				: 'paste, code',
				paste_as_text		: true,
				toolbar				: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify alignnone | subscript superscript code',
				setup				: function (ed) {
					ed.on('keyup', function (e) { 
						var count	= CountCharacters();
						var max 	= 1000;
						document.getElementById("chars_left").innerHTML = count + " | " + max + " Characters allowed!";
						if(count > max) {
							document.getElementById("chars_left").innerHTML = count + " | <span style='color: red;'>" + max + " Characters allowed!</span>";
						}
					});
				}
			});
			
			// ajax loader 
			var $loading = $('#ajaxSpinnerContainer');
			$(document).ajaxStart(function () {
				$loading.show();
			}).ajaxStop(function () {
				$loading.hide();
			});	
		},

		faculty_save_draft: function() {
			var mainContext = $('#mt-answer-form');
			$(".delete-attachment", mainContext).on('click', function(e){
				e.preventDefault();
				var answer_id	= $(this).parents('#answer-form').data('id'),
					attachment	= $('.answer-attachment', mainContext).attr('href'),
					data_id		= {'ans_id' : answer_id, 'file' : attachment};
				$.ajax({
					type	: 'POST',
					url		: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=delete_answer_attachment',
					data	: data_id,
					dataType: 'json',
					success	: function(data) {
						$('.draft-attachment', mainContext).hide();
					}
				});
			});
		},
		
		faculty_post_answer: function() {
			var mainContext = $('#mt-answer-form');
			$("#answer-form-btn", mainContext).on('click', function(e){				
				e.preventDefault();
				if(document.getElementById("mtutor-ans-attachment").files.length != 0 ){
				var FileSize = document.getElementById('mtutor-ans-attachment').files[0].size;
			}else{
				var FileSize = '';
			}
				var count	= CountCharacters();
				var max 	= 1000;
				if(count >= max) {
					alert(max + " Characters allowed!");
				}else 
				if (FileSize > 5242880) {	
					alert('File size exceeds 5 MB');
				} else {
					$("#answer-form-btn").hide();
					$("#save-as-draft").hide();					
					$("#answer-form", mainContext).submit();
				}
				
			});
			
			$("#save-as-draft", mainContext).on('click', function(e){				
				$("#answer-form-btn").hide();
				$("#save-as-draft").hide();				
			});
		},
		
		similar_qa: function() {
			var mainContext = $('.answer-wrap');
			$(".approve-similar-qa-btn", mainContext).on('click', function(e){				
				e.preventDefault();
				var question_id	= $(this).data('ques_id'),
					answer_id	= $(this).data('ans_id'),
					mtutor_id	= $(this).data('mtutor_id'),
					user_type	= $(this).data('user_type'),
					url 		= mtsoc_vars.base_url + 'gateway/action.php?application=qa&action=dashboard';			
				$.ajax({
					type	: 'POST',
					url		: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=approve_similar_qa',
					data	: {'question_id': question_id, 'answer_id': answer_id,'mtutor_id':mtutor_id,'user_type':user_type},
					dataType: 'json',
					success	: function(response) {
						window.location.href = url;						
					}
				});			
			});
		},
		filter_qa: function(){			
			$('#filter_qa').on('change', function(e){
				e.preventDefault();
				var sort_key = $(this).val(),
					url 	 = $(this).data('location');
					window.location.href = url+sort_key;
			});
		},
		periodic_call: function(){
			$.blink = function(selector){
				$(selector).fadeOut('1008', function(){
					$(this).fadeIn('1008', function(){
						$.blink(this);
					});
				});
			}
			if ($("body").hasClass("subpage-edit")) {
				var count = $('#ansform-editor').data('timeout');
				var minutes, seconds;
				setInterval(function() {
					count = count - 1;
					minutes = Math.floor(count / 60);
					seconds = count % 60;
					if (count <= 0) {
						$('.overlay-timeout').show();
						$('.time-left').html('Time Out. If you want to answer for this question, ask moderator to re-assign to you.');
						$('.time-left').addClass('make-red');
					} else {
						if (count < 180) {
							$.blink('.time-left');
							$('.time-left').html('Time remaining to Post answer is ' + minutes + ' minutes ' + seconds + ' seconds. Please Save Draft of Post answer.');
							$('.time-left').addClass('make-red');
						} else if (count > 180 && count < 300) {
							$('.time-left').html('Time remaining to Post answer is ' + minutes + ' minutes ' + seconds + ' seconds.');
							$('.time-left').addClass('make-red');
						} else {
							$('.time-left').html('Time remaining to Post answer is ' + minutes + ' minutes ' + seconds + ' seconds.');
						}
					}
				}, 1000);
			}
		},
		approve_reject_chatroom: function(){
			$('.chatroom-row').each(function(){
				$(".chatroom-action", $(this)).on('click', function(e) {
					e.preventDefault();
					var data	= {'chatroom_id': $(this).attr('id'), 'chatroom_action' : $(this).data('action')};
					$.ajax({
						type: 'POST',
						url:  mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=chatroom_action',
						data: data,
						dataType: 'json',
						success: function(data) {
							window.location.reload();
						}
					});
				});
			});
		},
		faculty_register: function(){
			$('#country_id').on('change', function(e){
				e.preventDefault();
				var country_id = $(this).val();
				if(country_id != 0){
					$.ajax({
						type: 'POST',
						url:  mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=get_university',
						data: {'country_id': country_id},
						dataType: 'json',
						success: function(response) {
							$('#university_id').html('');
							if(response.status != false) {
								$('#university_id').append('<option value="">Select University</option>');
								$.each( response, function(k, val) {
									$('#university_id').append('<option value=' + val.id + '>' + val.name + '</option>');
								});
							} else {
								$('#university_id').append('<option value="">No University Found!</option>');
							}
						}
					});
				} else{
					$('#university_id, #college_id').html('');
					$('#university_id').append('<option value="">Select University</option>');
					$('#college_id').append('<option value="">Select College</option>');
				}
			});
			$('#state_id').on('change', function(e){
				e.preventDefault();
				var state_id = $(this).val();
				$.ajax({
					type: 'POST',
					url:  mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=get_university',
					data: {'state_id': state_id},
					dataType: 'json',
					success: function(response) {
						$('#university_id').html('');
						if(response.status != false) {
							$('#university_id').append('<option value="">Select University</option>');
							$.each( response, function(k, val) {
								$('#university_id').append('<option value=' + val.id + '>' + val.name + '</option>');
							});
						} else {
							$('#university_id').append('<option value="">No University Found!</option>');
						}
					}
				});
			});
			$('#university_id').on('change', function(e){
				e.preventDefault();
				var university_id = $(this).val();
				if(university_id != 0){
					$.ajax({
						type: 'POST',
						url:  mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=get_college',
						data: {'university_id': university_id},
						dataType: 'json',
						success: function(response) {
							$('#college_id').html('');
							if(response.status != false) {
								$('#college_id').append('<option value="">Select College</option>');
								$.each( response, function(k, val) {
									$('#college_id').append('<option value=' + val.id + '>' + val.name + '</option>');
								});
							} else {
								$('#college_id').append('<option value="">No College Found!</option>');
							}
						}
					});
				} else{
					$('#college_id').html('');
					$('#college_id').append('<option value="">Select College</option>');
				}
			});
			$("#sme_register_btn").on('click', function(e){
				e.preventDefault();
				$.ajax({
					type	: 'POST',
					url		: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=register_submit',
					data	: $('#form-sme-register').serialize(),
					dataType: 'json',
					success	: function(response) {
						$('#result').css('display', 'block');
						if(response.status != false) {
							$('#result').html(response.message);
							window.location.reload();
						} else {
							if(response.type == 'text') {
								$('#result').html(response.key + ' cannot be empty.');
							} else if(response.type == 'select') {
								$('#result').html('Please select ' + response.key + '.');
							} else if(response.type == 'mismatch') {
								$('#result').html('Password & Confirm Password should be same.');
							} else if(response.type == 'custom') {
								$('#result').html(response.key);
							} else {
								$('#result').html('Something Went Wrong.');
							}
						}
					},
					error	: function(response) {
						console.log(response);
					},
				});
			});
		}
	};

		//change Password
	$('#change_password').on('click', function(e) {
		e.preventDefault();
		$.ajax({
			type	: 'POST',
			url		: mtsoc_vars.base_url + 'gateway/action?application=member&action=change_password_submit',
			data	: $('#form-change-password').serialize(),
			dataType: 'json',
			success	: function(response) {
					if(response.status != false) {
						$('#result').html('<font color="green"><strong>Password Changed</strong></font>');
						//window.location.reload();
						history.go(-1);
					} else {
						if(response.type == 'text') {
							$('#result').html('<font color="red">' + response.key + ' cannot be empty. </font>');
						} 
						else if(response.type == 'custom'){
							$('#result').html('<font color="red">'+response.key+'</font>');
						}
					}
				},
				error: function (jqXHR, exception) {
					console.log(jqXHR);
				},
			});	
		});

		//Saving Academic Tracker START-Senthil Kumar
	
	$("#academic_tracker_btn").on('click', function(e){
			e.preventDefault();
			var formData = new FormData($('form')[0]);
			$.ajax({
				type	: 'POST',
				url		: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=save_academic_tracker',
				data	:  formData,//$('#academic_tracker-form').serialize(),
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,

				success	: function(response) {
					
					$(function() {
							$('html, body').animate({
								scrollTop: $(".mt-container").offset().top
							}, 500);
					});
		
					if(response.key)var output_message = response.key;
						 else var output_message = "Something Went Wrong..!";
										$(".alert").remove();  
					if(response.status != false) {
									var DialogueObj =   new BootstrapDialog()
						.setTitle('Message')
						.setMessage('<strong>Successfully Inserted..!</strong>')
						.setType(BootstrapDialog.TYPE_SUCCESS)
						.open();
								setTimeout(function(){ window.location.reload(); },500);
								} else {
					if(response.type == 'text' ) {
										var DialogueObj =   new BootstrapDialog()
						.setTitle('Warning')
						.setMessage('<strong>'+output_message+' Required</strong>')
						.setType(BootstrapDialog.TYPE_WARNING)
						.open();	
									} else if(response.type == 'custom'){
										
						var DialogueObj =   new BootstrapDialog()
						.setTitle('Warning')
						.setMessage('<strong>'+output_message+'</strong>')
						.setType(BootstrapDialog.TYPE_WARNING)
						.open();
						
									}
									
								}
				},
				error	: function(response) {
					console.log(response);
				},
			});
		});
		//Saving Academic Tracker END






})(jQuery);


window.dashboard_action = {
	// Get and View Rejected Answers
	'click .rejected-answer-popup': function (e, value, row, index) {
		var id				= $(this).parents('.question-item').data('id'),
			parentContext	= $(this).parents('.tcol-question');

		$.ajax({
			type	: 'POST',
			url		: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=get_reject_answer',
			data	: {'question_id': id},
			dataType: 'html',
			success	: function(response) {
				parentContext.find('.modal-body').empty('').append(response);
			},
		});
	},

	// Reject Question
	'click .reject-question-btn': function (e, value, row, index) {
		var id				= $(this).parents('.question-item').data('id'),
			parentContext	= $(this).parents('.tcol-action'),
			modalContext	= parentContext.find('#reject-question-form-popup-'+ id),
			inputContext	= modalContext.find('textarea'),
			application_type= $('#application_type').val(),
			mtutor_id 		= $('#mtutor_id').val();

		if( inputContext.val() != '' && inputContext.attr('placeholder') != inputContext.val() ) {
			$.ajax({
				type	: 'POST',
				url		: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=reject_question',
				data	: {'question_id': id, 'rejected_reason': inputContext.val(),'application_type':application_type,'mtutor_id':mtutor_id},
				dataType: 'json',
				success	: function(response) {
					window.location.reload();
				},
			});	
		} else {
			alert('Reason cannot be empty!');
		}
	},

	// Approve Answer
	'click .approve-answer-btn': function (e, value, row, index) {
		var id				= $(this).parents('.question-item').data('id'),
			answer_id		= $(this).parents('.reject-answer-form').data('id'),
			mtutor_id		= $(this).parents('.reject-answer-form').data('mtutor_id'),
			application_type= $(this).parents('.reject-answer-form').data('application'),
			parentContext	= $(this).parents('.tcol-action'),
			modalContext	= parentContext.find('#reject-question-form-popup-'+ id),
			inputContext	= modalContext.find('textarea');
//alert(application);return false;
		if(!id == '') {
			$.ajax({
				type: 'POST',
				url: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=approve_answer',
				data: {'question_id': id, 'answer_id': answer_id,'mtutor_id':mtutor_id,'application_type':application_type},
				dataType: 'json',
				success: function(response) {
					window.location.reload();
				}
			});
		}
	},

	// Reject Answer
	'click .reject-answer-btn': function (e, value, row, index) {
		var id				= $(this).parents('.question-item').data('id'),
			answer_id		= $(this).parents('.reject-answer-form').data('id'),
			mtutor_id		= $(this).parents('.reject-answer-form').data('mtutor_id'),
			parentContext	= $(this).parents('.tcol-action'),
			modalContext	= parentContext.find('#view-answer-popup-'+ id),
			inputContext	= modalContext.find('textarea');

		if( inputContext.val() != '' && inputContext.attr('placeholder') != inputContext.val() ) {
			$.ajax({
				type	: 'POST',
				url		: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=reject_answer',
				data	: {'question_id': id, 'answer_id': answer_id, 'rejected_reason': inputContext.val()},
				dataType: 'json',
				success	: function(response) {
					window.location.reload();
				},
			});	
		} else {
			alert('Reason cannot be empty..!');
		}
	},

	// Onchange Branch Selector
	'change .branch-selector': function (e, value, row, index) {
		if( $(this).val() != '' ) {
			var data_id				= {'branch_id': $(this).val()},
				assigneeContext	= $(this).parent().next().find('.assignee-selector');

			$.ajax({
				type	: 'POST',
				url		: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=get_faculty_by_branch',
				data	: data_id,
				dataType: 'json',
				success	: function(response) {
					assigneeContext.html('');
					assigneeContext.append('<option value="">Select Assignee</option>');
					$.each( response, function(k, val) {
						assigneeContext.append('<option value=' + val.id + '>' + val.first_name + ' ' + val.last_name + '</option>');
					});
				},
				error	: function(response) {
					console.log(response);
				}
			});
		}
	},

	// Onchange Assignee Selector
	'change .assignee-selector': function (e, value, row, index) {
		var parentContext = $(this).parents('.question-item');

		if( $(this).val() != '' ) {
			$.ajax({
				type	: 'POST',
				url		: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=get_faculty_workload',
				data	: {'assignee_id': $(this).val()},
				dataType: 'json',
				success	: function(response) {
					parentContext.find('.item-faculty-load').empty('');
					parentContext.find('.item-faculty-load').append('<br>In Progress : ' + response.inprogress + '<br>Yet to Start : '+ response.assigned );
				}
			});
		}
	},

	// Assign Question
	'click .assign-question-btn': function (e, value, row, index) {
		var id				= $(this).parents('.question-item').data('id'),
			parentContext	= $(this).parents('.tcol-action'),
			branchContext	= $(this).parents('.item-assignee-wrap').find('.branch-selector'),
			assigneeContext	= $(this).parents('.item-assignee-wrap').find('.assignee-selector');

		if( branchContext.val() == '' ) {
			alert('Please Select branch and faculty to assign question..!');
		} else if( assigneeContext.val() == '' ) {
			alert('Select faculty to assign question..!');
		} else {			
			$.ajax({
				type: 'POST',
				url: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=assign_question',
				data: {'question_id': id, 'faculty_id': assigneeContext.val()},
				dataType: 'json',
				success: function(response) {
					window.location.reload();
				},
			});
		}
	},

	// Respond In
	'click #respondin-dropdown-btn': function (e, value, row, index) {
		var parentContext = $(this).parents('#respondin-dropdown-wrap');
			parentContext.children(".dropdown-menu" ).show();
		e.stopPropagation();
	},

	'click .respondin-dropdown-menu': function (e, value, row, index) {
		e.stopPropagation();
	},	
	
	'click .respondin-btn': function (e, value, row, index) {
		var id				= $(this).data('id'),
			respond_time	= $(this).parents('.respondin-dropdown-menu').find("input[name='respond_time']:checked").val(),
			parentContext	= $(this).parents('#respondin-dropdown-wrap');
			
		if(!respond_time){
			alert("Please select respond in minutes");
		}else {
			$.ajax({
				type	: 'POST',
				url		: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=faculty_respond',
				data	: {'question_id': id, 'respond_time': respond_time},
				dataType: 'html',
				success	: function(data) {
					if(data != "") {
						parentContext.css('display', 'none');	
					} 
				}
			});
		}
	},	
	
	// Force Reassign
	'click #force_reassign': function (e, value, row, index) {
		var id	= $(this).val();
		$.ajax({
			type	: 'POST',
			url		: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=force_reassign',
			data	: {'question_id': id},
			dataType: 'json',
			success	: function(response) {
				window.location.reload();
			},
		});	
	},	
	
	
	'click .student_details': function (e) {		
		  
			var user_id 	= $(this).data("student_id");
			var question_id = $(this).data("question_id");
			var application	= $(this).data("application");
			//alert(user_id);

			if(application == 'SCHOOL'){
				var url = 'http://65.1.29.148/school_api_simulator/gateway/api.php';
				action =  'get_student_details';
				var auth_token = '7ff7c3ed4e791da7e48e1fbd67dd5b72';
				var question_id = $(this).data("question_id");
				var man_data = '{"action":"'+action+'","user_id":"'+user_id+'","oauth":"'+auth_token+'","authtoken":"'+authtoken+'","type":"info","userid":"'+user_id+'","question_id":"'+question_id+'"}';
			}else if(application == 'MLEARN'){
				var url = 'http://18.138.131.97/stg_lom/gateway/api.php';
				var action = 'get_user_details';
				var auth_token = '7ff7c3ed4e791da7e48e1fbd67dd5b72';
				var man_data = '{"action":"'+action+'","user_id":"'+user_id+'","oauth":"'+auth_token+'","authtoken":"'+authtoken+'","type":"info","userid":"'+user_id+'"}';
			}else if(application == 'M-tutor'){
				var url = 'https://betaonline.m-tutor.com/mtutor/gateway/mtutorAPI_5.php';
				var action = 'info';
				var authtoken = 'cc3717c967858348b2ea7b3c400c912d';
				//var man_data = "{'type':'info','userid':'"+user_id+"','authtoken':'"+authtoken+"'}";
				var man_data = {"type":"info","userid":user_id,"authtoken":authtoken,"requestedon":"2021-04-23 10:40:43"};
			}else if(application == 'Astromart'){
				var url = 'http://astromarts.com/gateway/action.php?application=qa&action=get_user_astro_details';
				//var man_data = '{"userid":"'+user_id+'","question_id":"'+question_id+'"}';
				//alert(man_data);
			}
			$.ajax({
					type	: 'POST',
					dataType: "json",
					url		: url,
					data	: {'userid': user_id, 'question_id': question_id},
					//'{"action":"'+action+'","user_id":"'+user_id+'","oauth":"'+auth_token+'","authtoken":"'+authtoken+'","type":"info","userid":"'+user_id+'"}',
					dataType: 'json',
					success	: function(response) {
						//console.log('aa');
						console.log(response);
						//console.log('aa1');
					//alert('response'+response.returncode);
					
					if(application == 'M-tutor'){
							var  alert_msg = "Name : "+ response.name+"\n"+"University : "+response.university+"\nCollege : "+response.college+"\nBranch : "+response.branchname+"\nCourse : "+response.coursename+"\n"+"Mob No : "+response.mobilenumber+"\n"+"Type : "+response.paid_type;
							
						alert(alert_msg);
					}else if(application != 'M-tutor'){
						if(response.returncode == '200'){
							var res =  response.returndata;
						
							if(res.name != null)
								var name = res.name;
							else
								var name = "";
							if(application == 'SCHOOL'){
								if(res.mobile_number !== 'undefined'){
									var mob_no = res.mobile_number;
									var grade_name = res.question_grade_name;
									var board_name = res.question_board_name;
									var  alert_msg = "Name : "+ name+"\nMob No : "+mob_no+"\nGrade Name : "+grade_name+"\nBoard Name : "+board_name;
								}else
									var mob_no = "";
							}else if(application == 'MLEARN'){
								if(res.msisdn !== 'undefined'){
									var mob_no = res.msisdn;
									var  alert_msg =  "Name : "+ name+"\n"+"Mob No : "+mob_no;
								}else
									var mob_no = "";
							}else if(application == 'Astromart'){
									//alert(response);
									//alert('hi');
									var  alert_msg =  "Name : "+ res.user_name+"\n"+"Mob No : "+res.msisdn+"\n"+"Zodiac Sign : "+res.product_name+"\n"+"Birth Date : "+res.DOB+"\n"+"Birth Time : "+res.TOB+"\n"+"Birth Place : "+res.birth_place;
							}
							
								alert(alert_msg);
						}
					}else{
						//alert('Something went wrong..!');
						alert('No Records Found..!');
					}
					},
					error	: function(response) {
						//alert(response);
						console.log('aa error');
						console.log(response);
					},
				});
	}
	
};

//Upload Files Button function START
 $(function() {

  // We can attach the `fileselect` event to all file inputs on the page
  $(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });

  // We can watch for our custom `fileselect` event like this
  $(document).ready( function() {
      $(':file').on('fileselect', function(event, numFiles, label) {

          var input = $(this).parents('.input-group').find(':text'),
              log = numFiles > 1 ? numFiles + ' files selected' : label;

          if( input.length ) {
              input.val(log);
          } else {
              //if( log ) alert(log+'tes');
          }

      });
  });
//Upload Files Button function END 

}); 

  
  //Whatsapp Form
   $(document).ready( function() {
		$('#university').on('change', function(e){
			e.preventDefault();
			if($(this).val() == ""){
				return false;
				$('#subject').empty().append('<option selected="selected" value="">SELECT</option>')
;
			}
			$.ajax({
			type	: 'POST',
			url		: mtsoc_vars.base_url + 'gateway/action?application=w_app&action=get_subject',
			data	: {'university_id': $(this).val()},
			dataType: 'html',
			success	: function(data) {
				var result = $.parseJSON(data);
				var toAppend = '';
				   $.each(result,function(i,o){
				   toAppend += '<option value="'+o.sid+'">'+o.sname+'</option>';
				  });

				$('#subject').append(toAppend);
			}
		});
			
		});
			
		$("#whatsapp_form_btn").click(function(e){
			e.preventDefault();
	
		  var university_id 	= $('#university').val();
		  var subject_id 		= $('#subject option:selected').val();
		  var subject_name 		= $('#subject option:selected').text();
		  var user_id 			= $('#user_id').val();
		  var mobile_no 		= $('#mobile_no').val();
		  var email_id 			= $('#email_id').val();
		  var question_type 	= $('#question_type').val();
		  var source 			= $('#source').val(); 
		  var question 			= $('#question').val(); 
		  if(university_id == "" || subject_id == "" || user_id == "" || mobile_no == "" ||source == "" || question == "" ){alert("Please Fill all mandatory fields");return false;}else if(!validateEmail(email_id)) {
			   alert("Invalid Email Id");return false;  
		  }else if(mobile_no.length != 10){alert("Invalid Mobile Number");return false;}
		var formData	=	new FormData(document.querySelector('form'));
		formData.append('subject_name', $('#subject option:selected').text()); 
		formData.append('image', $('input[type=file]')[0].files[0]); 
		  console.log(formData);
		  $.ajax({
				url: mtsoc_vars.base_url + 'gateway/action?application=w_app&action=form_submit',
				type: 'POST',
				dataType: "JSON",
				data: formData,
				processData: false,
				contentType: false,
				success: function (data, status)
				{
					if(data == 1){
						alert("Data Saved Successfully");
						window.location.reload();
					}else{
						alert("Something went Wrong");
					}
				},
				error: function (xhr, desc, err)
				{
					

				}
			}); 
		});

	$(document).on('click', '.question_edit', function(e) {
			e.preventDefault();
		var question_id = $(this).attr("data-question_id");
		var mtutor_id = $(this).attr("data-mtutor_id");
		var question = $(this).attr("data-question");
		$('#edit_question_id').val(question_id);
		$('#edit_question_value').val(question);
		$('#edit_question_mtutor_id').val(mtutor_id);
		$('#edit_question_Modal').modal('toggle');
	});
		
	$(document).on('click',"#save_edit_question", function(e) {
		e.preventDefault();
		var question_id = $('#edit_question_id').val();
		var question = $('#edit_question_value').val();
		var mtutor_id = $('#edit_question_mtutor_id').val();
		$.ajax({
			type	: 'POST',
			url		: mtsoc_vars.base_url + 'gateway/action?application=w_app&action=update_question',
			data	: {'question_id': question_id,'question':question,'mtutor_id':mtutor_id},
			dataType: 'html',
			success	: function(reponse) {
				if(reponse == 1){						
					alert("Question Updated Successfully");
					window.location.reload();
				}else{
					alert("Something went Wrong");
				}
			}
		});
	});	
  });
  
 function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}


$(function () {
	$('.datetimepicker').datetimepicker({
		format: 'DD-MM-YYYY',
		minDate: moment("01/01/2015"),
        maxDate: moment()
	});
});  

$(document).on('click',"#add_report_btn", function(e) {
		e.preventDefault();
		var start_date 	= 	$('#start_date').val(),
			end_date	=	$('#end_date').val();
			
					$.ajax({
			type	: 'POST',
			url		: mtsoc_vars.base_url + 'gateway/action?application=aad_report&action=fetch_report',
			data	: {'start_date':start_date,'end_date':end_date},
			dataType: 'html',
			success	: function(response) {
				$('#aad_records').html(response);
			}
		});	
});


	$(document).on('click', '.export_csv', function(e) {
		var id = $(this).attr("data-id");
		console.log('export_csv11');
		$("#"+id).table2excel({
			name: "Export",
			filename: "Data",
			fileext: ".xls",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true
		});
	});
  
$(document).ready(function() {  
  var sel = $('#filter_qa');
var selected = sel.val(); // cache selected value, before reordering
var opts_list = sel.find('option');
opts_list.sort(function(a, b) { return $(a).text() > $(b).text() ? 1 : -1; });
sel.html('').append(opts_list);
sel.val(selected);
});



//For Testing
$(document).on('click',"#add_report_btn_test", function(e) {
		e.preventDefault();
		var start_date 	= 	$('#start_date').val(),
			end_date	=	$('#end_date').val();
			
					$.ajax({
			type	: 'POST',
			url		: mtsoc_vars.base_url + 'gateway/action?application=aad_report_test&action=fetch_report',
			data	: {'start_date':start_date,'end_date':end_date},
			dataType: 'html',
			success	: function(response) {
				$('#aad_records').html(response);
			}
		});	
});
//End Testing

//EDIT SME START
$(document).on('click',"#edit_fetch_btn", function(e) {
		e.preventDefault();
		var email_id 	=	$('#email_id').val();
			
			$.ajax({
			type	: 'POST',
			url		: mtsoc_vars.base_url + 'gateway/action?application=member&action=fetch_sme',
			data	: {'email_id':email_id},
			dataType: 'html',
			success	: function(response) {
				var data = JSON.parse(response);
				$('#sme_id').val(data.id);
				$('#first_name').val(data.first_name);
				$('#last_name').val(data.last_name);
				$('#gender').val(data.gender).change();
				$('#mobile_no').val(data.mobile_no);
				$('#email').val(data.email);
				$('#branch_id').val(data.branch_id).change();
				$('#status').val(data.status).change();
				$('#form-sme-edit-register').css("display", "block");
			}
		});	
});

$(document).on('click',"#update_sme_btn", function(e) {
		e.preventDefault();
		if($('#first_name').val() == '' || $('#gender option:selected').val() == '' || $('#branch_id option:selected').val() == '0' || $('#mobile_no').val() == '' || $('#email').val() == '' || $('#status').val() == '' ){
			var DialogueObj =   new BootstrapDialog()
				.setTitle('Warning')
				.setMessage('<strong>Please Select All Mandatory Fields</strong>')
				.setType(BootstrapDialog.TYPE_WARNING)
				.open();
				return false;
		}

		var email_id = $('#email').val(),mobile_no = $('#mobile_no').val();
		if(!validateEmail(email_id)) {
			var DialogueObj =   new BootstrapDialog()
				.setTitle('Warning')
				.setMessage('<strong>Invalid Email Id</strong>')
				.setType(BootstrapDialog.TYPE_WARNING)
				.open();
				return false;  
		  }else if(mobile_no.length != 10){
			var DialogueObj =   new BootstrapDialog()
				.setTitle('Warning')
				.setMessage('<strong>Invalid Mobile Number</strong>')
				.setType(BootstrapDialog.TYPE_WARNING)
				.open();
				return false;
			  
		  }
					
			
		var data 	=	$('#form-sme-edit-register').serialize();
			
			$.ajax({
			type	: 'POST',
			url		: mtsoc_vars.base_url + 'gateway/action?application=member&action=edit_sme',
			data	: data,
			dataType: 'html',
			success	: function(response) {
				if(response == 200){
					var DialogueObj =   new BootstrapDialog()
						.setTitle('Message')
						.setMessage('<strong>Successfully Updated..!</strong>')
						.setType(BootstrapDialog.TYPE_SUCCESS)
						.open();
								setTimeout(function(){ window.location.reload(); },1000);
				}else{
					var DialogueObj =   new BootstrapDialog()
						.setTitle('Warning')
						.setMessage('<strong>Something Went Wrong</strong>')
						.setType(BootstrapDialog.TYPE_WARNING)
						.open();
				}
			}
		});	
});

//EDIT SME END

//Answered Question Rejection Process Start
$(document).on('click',".re_answer", function(e) {
		e.preventDefault();
		
var id				= $(this).parents('.question-item').data('id'),
    answer_id		= $(this).data('id'),
	rejected_reason = $('.re_answer_reason_'+id).val();
	
	
if(rejected_reason!=""){
		$.ajax({
			type	: 'POST',
			url		: mtsoc_vars.base_url + 'gateway/action.php?application=ajax&action=re_answer',
			data	: {'question_id': id,'answer_id':answer_id,'rejected_reason':rejected_reason},
			dataType: 'json',
			success	: function(response) {
				console.log(response);
				if(response == 200){
						var DialogueObj =   new BootstrapDialog()
						.setTitle('Message')
						.setMessage('Question Reverted Successfully')
						.setType(BootstrapDialog.TYPE_SUCCESS)
						.open();
								setTimeout(function(){ window.location.reload(); },800);
					
				}else{
						var DialogueObj =   new BootstrapDialog()
						.setTitle('Warning')
						.setMessage('Something Went Wrong')
						.setType(BootstrapDialog.TYPE_WARNING)
						.open();
				}
			}
		});
}else {
			alert('Reason cannot be empty..!');
			return false;
		}
});

//Answered Question Rejection Process End