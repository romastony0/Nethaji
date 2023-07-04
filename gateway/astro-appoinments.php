<?php 
session_start();

include("includes/header.php");
$post_data = array(
'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
'astro_id' 		=> $_SESSION['my_astro_id'],
'action' 		=> "get_astro_appoinments",
'session_code' 	=> $_SESSION['session_code']
);

$response = curl_hit($post_data);

$returndata = $response['returndata'];
//echo "<pre>"; print_r($response);exit;

// $name		= $response['returndata']['user_name'];
// $birth_place		= $response['returndata']['birth_place'];
// $zodiac_sign 		= $response['returndata']['zodiac_sign'];
// $email 		= $response['returndata']['email_id'];
// $msisdn 		= $response['returndata']['msisdn'];
// $image_path = $response['returndata']['image_path'];
// $image_path = $image_path == "" ? "./assets/images/profile/avatar.png" : $image_path;

?>
<style>
#profile .card .card-body {
	padding:1rem !important;
}
</style>
<?php 
$day = date('w');
$weekstart = date('Y-m-d', strtotime('-'.$day.' days'));
$weekend = date('Y-m-d', strtotime('+'.(6-$day).' days'));
?>
		 <main class="flex-shrink-0 container mb-5">
		   <h3 class=" py-3">Your appointments</h3>
		   
		   <section id="astro_appoinments" class="pt-3">
			 <div class="container">
				<?php $j=1; for($i = $weekstart; $i <= $weekend && $j<=7; $i = date("Y-m-d",strtotime($i.'+1 days'))){?>
					<div class="row align-items-center bdr">
						<div class="col-md-3">
							<h5 class="<?php if (date("l", strtotime($i)) == 'Sunday') {echo "text-danger";} else {	echo "text-primary";}?>"><?php echo date("l",strtotime($i));?></h5>
							<small><?php echo date("M d, Y", strtotime($i));?></small>
						</div>
						<div class="col-md-9">
							<?php if($returndata[$i]==''){?>
							<p> - Not Available - </p>
							<?php } else {
								foreach ($returndata[$i] as $key => $value) { ?>
							<a <?php if ($value == "booked") { echo 'href="https://meetings.astromarts.com:3008/?type=host&ast='.$_SESSION['my_astro_id'].'"'; }?> class="btn <?php if ($value == "booked") {
								echo "btn-success";
							} else {
								echo "btn-light";
							} ?> btn-sm"> <?php if ($value == "booked") {echo $key.'  join';}else{echo $key;} ?></a>
							<?php }
							}?>
						</div>
					</div>
				<?php 
			}?>
						
				
			 </div>
		   </section>
		 </main>

	<!-- Main End //-->
	<?php include("includes/footer.php");?>	<!-- End Astro Book an appoinment //-->