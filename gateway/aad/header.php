<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mtutor Social</title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">

	<!-- Stylesheet -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL.'resources/css/bootstrap.css'; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL.'resources/css/bootstrap-table.css'; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL.'resources/css/style.css?v=3.0'; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL.'resources/css/mticons.css'; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL.'resources/css/bootstrap-dialog.css'; ?>">

	<!-- Scripts -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
	<script type="text/javascript" src="<?php echo APPLICATION_URL.'resources/js/bootstrap-table.js'; ?>" charset="utf-8"></script>
	<script type="text/javascript" src="<?php echo APPLICATION_URL."resources/js/tinymce/tinymce.min.js";?>"></script>
	
	<script type="text/javascript"> 
		var mtsoc_vars	= {
			'base_url'	: '<?php echo APPLICATION_URL; ?>',
			'user_type' : '<?php echo mtutor_current_user() ? mtutor_current_user()->type : ''; ?>',
			'custom_ajax_call'	: '<?php echo AJAX_TIMEOUT; ?>',
		}
	</script>
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script type="text/javascript">
		$(window).load(function() {
			$("#ajaxSpinnerContainer").fadeOut("slow");
		})
	</script>
</head>
<body class="<?php echo mtutor_get_body_class(); ?>">
	<audio id="NotificationAudio">
		<source src="<?php echo APPLICATION_URL.'resources/sounds/beep.ogg'; ?>" type="audio/ogg">
		<source src="<?php echo APPLICATION_URL.'resources/sounds/beep.mp3'; ?>" type="audio/mpeg">
		<source src="<?php echo APPLICATION_URL.'resources/sounds/beep.wav'; ?>" type="audio/wav">
	</audio>
	<!-- Ajax Loader -->
	<div id="ajaxSpinnerContainer"></div>
	<div id="site" class="clearfix">
		<!-- Header Start -->
		<header id="mast-head">
			<div class="mt-container">
				<a class="brand-logo" href="<?php echo APPLICATION_URL ?>"><img src="<?php echo APPLICATION_URL; ?>resources/images/logo.jpg"></a>
				<ul class="user-navs">
					<?php if ( mtutor_current_user() ){ ?>
						<li><span class="user-welcome">Welcome <?php echo mtutor_current_user()->first_name .' '. mtutor_current_user()->last_name; ?></span></li>
						<?php if(mtutor_current_user()->type == 'SME' || mtutor_current_user()->type == 'MODERATOR'){ 
						$view = (isset($request['id']))?"&view=".$request['id']:"";
						?>
							<li><a href="<?php echo APPLICATION_URL.'gateway/action.php?application=qa&action=dashboard'.$view; ?>">Q&A Dashboard</a></li>				
						<?php } ?>
						<?php if(mtutor_current_user()->type == 'MODERATOR'){ ?>
							<!--<li><a href="<?php echo APPLICATION_URL.'gateway/action.php?application=qa&action=chatroom' ?>">Chat Rooms</a></li>-->
							<!--<li class="last"><a href="<?php echo APPLICATION_URL.'gateway/action.php?application=member&action=register' ?>">SME Register</a></li>
							<li class="last"><a href="<?php echo APPLICATION_URL.'gateway/action.php?application=member&action=edit_register' ?>">Edit SME </a></li>-->
						<?php } ?>
						<?php if(mtutor_current_user()->type == 'SME'){ ?>
							<li><a href="<?php echo APPLICATION_URL.'gateway/action.php?application=qa&action=rejected_answers' ?>">Rejected List</a></li>
						<?php } ?>
						<?php if(mtutor_current_user()->type == 'SUPPORT'){ ?>
							<li><a href="<?php echo APPLICATION_URL.'gateway/action.php?application=academic_tracker&action=initiate' ?>">Manual Academic Tracker</a></li>
						<?php } ?>
						<?php if(mtutor_current_user()->type == 'SME' || mtutor_current_user()->type == 'MODERATOR'){ ?>
							<!--<li><a href="<?php echo APPLICATION_URL.'gateway/action.php?application=w_app&action=initiate' ?>">Social Doubts</a></li>
							<li><a href="<?php echo APPLICATION_URL.'gateway/action.php?application=aad_report&action=initiate' ?>">AAD Report</a></li>-->
						<?php } ?>
						<!--<li>
							<?php $dec = encrypt_decrypt('decrypt', $_SESSION['eemail']); ?>
							<a href="<?php echo API_SRC_URL."&email=".$dec;?>" class="sidebar-toggle back-src" style="font-family:inherit;">Back to Src</a>
						</li>-->
						<!--<li class="last"><a href="<?php echo APPLICATION_URL.'gateway/action.php?application=member&action=change_password' ?>">Change Password</a></li>-->
						<li class="last"><a href="<?php echo APPLICATION_URL.'gateway/action.php?application=member&action=logout' ?>">Logout</a></li>
					<?php } else { ?>
						<li class="last"><a href="<?php echo APPLICATION_URL.'members/login' ?>">Login</a></li>
					<?php } ?>
				</ul>
			</div>
			<span class="notification" style="display: none">
				<a class="new-ques" style="display: none" href=""></a>
				<a class="new-ans" style="display: none" href=""></a>
			</span>
		</header>
		<!-- Header End -->
		<!-- Content Start -->
		<main id="main-content" class="clearfix" role="main">
			<div class="mt-container">
			<div class="overlay-timeout"></div>