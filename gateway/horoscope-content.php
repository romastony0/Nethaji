<?php 
include("./includes/header.php");
$post_data = array(
  'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
  'user_id' 		=> $_SESSION['user_id'],
  'action' 		=> "get_horoscope_details",
  'content_type' => $_GET['content_type']?$_GET['content_type']:"daily",
  'date' => date('Y-m-d'),
  'product_id' => $_GET['product_id'],
  );
  $response 		= curl_hit($post_data);
  //print_r($response);
  $return_code = $response['returncode'];
//echo $return_code;
  $horoscope_content = $response['returndata'];
//print_r($horoscope_content);
?>
    <!-- Hero Banner  //-->
    <!-- header // -->
    <!-- Main  -->
    <style>
      .hideContent {
    overflow: hidden;
    line-height: 25px;
    height: 100px;
}
.showContent {
    line-height: 25px;
    min-height: auto;
}
/* .showContent{
    height: auto;
} */
/* .show-more {
    padding: 10px 0;
    text-align: center;
} */
      </style>
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id']?>">
    <input type="hidden" name="product_id" id="product_id" value="<?php echo $_GET['product_id'];?>">
    <input type="hidden" name="product_name" id="product_name" value="<?php echo $_GET['product_name'];?>">
    <input type="hidden" name="mobile_no_ses" id="mob_no_ses" value="<?php echo $_SESSION['mobile_no'];?>">
	<main class="flex-shrink-0 container mb-3">
	  <section id="horoscope">
		<div class="container">
		  <nav class="mt-3" aria-label="breadcrumb">
			<ol class="breadcrumb"> 
			  <li class="breadcrumb-item">
				<a href="horoscope.php">Horoscope</a>
			  </li>
			  <li class="breadcrumb-item content_bread">
				<?php echo $_GET['content_type']?ucfirst($_GET['content_type']):'Daily';?>
			  </li>
			  <li class="breadcrumb-item active" aria-current="page"><?php echo $_GET['product_name'];?></li>
			</ol>
		  </nav>
      <?php $content_type = $post_data['content_type'];?>
		  <div class="row horo-icon my-4 nav nav-tabs" id="myTab" role="tablist">
        <div class="col-md-3 nav-item" role="presentation">
          <div class="card content_change shadow mb-4 nav-link <?php if($content_type=='daily')echo 'active'; ?>" data-boxtype="daily" id="daily-tab" data-bs-toggle="tab" data-bs-target="#daily_tab1" type="button" role="tab" aria-controls="daily" aria-selected="true">
          <div class="card-body d-flex align-items-center justify-content-between hreo-icon">
            <h3 class="card-title">Daily</h3>
            <img src="assets/images/horoscope/day-icon.png" alt="" class="img-fluid" />
          </div>
          </div>
        </div>
        <div class="col-md-3 nav-item" role="presentation">
          <div class="card content_change shadow mb-4 nav-link <?php if($content_type=='weekly')echo 'active'; ?>" data-boxtype="weekly" id="weekly-tab" data-bs-toggle="tab" data-bs-target="#weekly_tab1" type="button" role="tab" aria-controls="weekly" aria-selected="false">
          <div class="card-body d-flex align-items-center justify-content-between hreo-icon">
            <h3 class="card-title">Weekly</h3>
            <img src="assets/images/horoscope/week-icon.png" alt="" class="img-fluid" />
          </div>
          </div>
        </div>
        <div class="col-md-3 nav-item" role="presentation">
          <div class="card content_change shadow mb-4 nav-link <?php if($content_type=='monthly')echo 'active'; ?>" data-boxtype="monthly" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly_tab1" type="button" role="tab" aria-controls="monthly" aria-selected="false">
          <div class="card-body d-flex align-items-center justify-content-between">
            <h3 class="card-title">Monthly</h3>
            <img src="assets/images/horoscope/month-icon.png" alt="" class="img-fluid" />
          </div>
          </div>
        </div>
        <div class="col-md-3 nav-item" role="presentation">
          <div class="card content_change1 shadow mb-4 nav-link <?php if($content_type=='yearly')echo 'active'; ?>" data-boxtype="yearly" id="yearly-tab" data-bs-toggle="tab" data-bs-target="#yearly_tab1" type="button" role="tab" aria-controls="yearly" aria-selected="false"> 
          <div class="card-body d-flex align-items-center justify-content-between">
            <h3 class="card-title">Yearly</h3>
            <img src="assets/images/horoscope/year-icon.png" alt="" class="img-fluid" />
          </div>
        </div>
			</div>  
    </div>
    <div class="tab-content asse-cont" id="myTabContent">
      <div class="tab-pane fade <?php if($content_type=='daily')echo 'show active'; ?>" id="daily_tab" role="tabpanel" aria-labelledby="daily-tab">
        <div class="tab-con">
          <div class="row">
			      <div class="col-md-12 pb-3" id="daily_auto">
			        <div class="d-flex mb-3 justify-content-between align-items-center">
				        <h5 class="card-title-daily"><?php $date=$horoscope_content!=''?date("M d, Y"):date("M d, Y") ;echo " ".$_GET['product_name']." daily horoscope - (".$date.") ";?></h5>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end ms-3">
                  <button class="btn btn btn-outline-info me-md-2 yest-btn common_category" data-type="daily" data-common_type="prev day" data-date="<?php echo date('Y-m-d',strtotime('-1 day'));?>" type="button">Yesterday</button>
                  <button class="btn btn btn-outline-info me-md-2 tod-btn common_category" data-type="daily" data-common_type="this day" data-date="<?php echo date('Y-m-d');?>" type="button">Today</button>
                  <button class="btn btn btn-outline-info tom-btn common_category" data-type="daily" data-common_type="next day" data-date="<?php echo date('Y-m-d',strtotime('+1 day'));?>" type="button">Tomorrow</button>
                </div>
			        </div>
              <p  class="daily_content_change content hideContent"><?php echo $horoscope_content['content'];?></p>
              <div class="show-more">
                <button class="moreless-button btn btn-primary">Show more</button>
              </div>			      </div>
		      </div>
        </div>
      </div>
      <div class="tab-pane fade <?php if($content_type=='weekly')echo 'show active'; ?>" id="weekly_tab" role="tabpanel" aria-labelledby="weekly-tab">
        <div class="tab-con">
          <div class="row">
            <div id="weekly_auto" class=" col-md-8 d-flex mb-3 align-items-center">
              <ul class="weekly_bubble weekly">
              <?php 
                  $purchase_data = $response['purchased_data'];
                  $begin = $purchase_data['purchased_date'];
                  $end = $purchase_data['expiry_date'];

                  // $interval = DateInterval::createFromDateString('1 day');
                  // $period = DatePeriod($begin, $interval, $end);
                  $j = 1;
                  for($i = $begin; $i <= $end && $j<=7; $i = date("Y-m-d",strtotime($i.'+1 days'))){?>
                      <li class="show_box">
                        <a class="bubble_click bubble_<?php echo $j; $j++;if(date("Y-m-d",strtotime($i))==date("Y-m-d"))echo ' active';?>" data-date="<?php echo date("Y-m-d",strtotime($i)) ?>"> <?php echo date("D",strtotime($i)) ?> <br> <?php echo date(" d",strtotime($i)) ?></a>
                      </li>
                  
              
              <?php }for($k=$j;$k<=7;$k++){?>
                <li class="show_box" style="display:none">
                  <a class="bubble_click bubble_<?php echo $k;?>" data-date=""> SUN </a>
                </li>
               <?php }
            ?>
              </ul>
            </div>
            <div class="col-md-4">
              <div class="d-grid gap-2 d-md-flex justify-content-md-end ms-3">
              <button class="btn btn btn-outline-info me-md-2 prevw-btn common_category" data-common_type="prev week" data-type="weekly" data-date="<?php echo date('Y-m-d',strtotime('-7 days'));?>" type="button">Prev. Week</button>
              <button class="btn btn btn-outline-info me-md-2 thisw-btn common_category" data-common_type="this week" data-type="weekly" data-date="<?php echo date('Y-m-d');?>" type="button">This Week</button>
              <button class="btn btn btn-outline-info nextw-btn common_category" data-common_type="next week" data-type="weekly" data-date="<?php echo date('Y-m-d',strtotime('+7 days'));?>" type="button">Next Week</button>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 pb-3">
              <h5 class="card-title-weekly"> <?php $date=$horoscope_content!=''?date("l, d M Y"):date("l, d M Y") ;echo " ".$_GET['product_name']." weekly horoscope - ".$date." ";?> </h5>
              <p class="weekly_content_change content hideContent"><?php echo $horoscope_content['content'];?></p>
              <div class="show-more">
                <button class="moreless-button btn btn-primary">Show more</button>
              </div>            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade <?php if($content_type=='monthly')echo 'show active'; ?>" id="monthly_tab" role="tabpanel" aria-labelledby="monthly-tab">
        <div class="tab-con">
          <div class="row">
            <div class="col-md-12 pb-3">
              <div class="d-flex mb-3 justify-content-between align-items-center">
                <div class="calender-bg">
                  <form class="row">
                    <div class="col-auto" id="monthly_auto">
                    <?php 
                if ($return_code == '200') {
                  $purchase_data = $response['purchased_data'];
                  $begin = $purchase_data['purchased_date'];
                  $end = $purchase_data['expiry_date'];
                }?>
                      <div class="md-form md-outline input-with-post-icon datepicker" id="customDays">
                        <input placeholder="Select date" type="text" class="monthly_date_picker date_getter" data-content='monthly' name="date1" data-maxdate="<?php echo date("Y-m-d",strtotime($end)); ?>" data-mindate="<?php echo date("Y-m-d",strtotime($begin)); ?>" id="Customization" class="form-control" >
                        <!-- <i class="fas fa-calendar input-prefix" tabindex=0></i> -->
                      </div>
                    </div>
                  </form>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end ms-3">
                  <button class="btn btn btn-outline-info me-md-2 prevm-btn common_category" data-type="monthly" data-common_type="prev month" data-date="<?php echo date('Y-m-01',strtotime('-1 month'));?>" type="button">Prev. Month</button>
                  <button class="btn btn btn-outline-info me-md-2 thism-btn common_category" data-type="monthly" data-common_type="this month" data-date="<?php echo date('Y-m-01');?>" type="button">This month</button>
                  <button class="btn btn btn-outline-info nextm-btn common_category" data-type="monthly" data-common_type="next month" data-date="<?php echo date('Y-m-01',strtotime('+1 month'));?>" type="button">Next Month</button>
                </div>
              </div>
              <h5 class="card-title-monthly"> <?php $date=$horoscope_content!=''?date("l, d M Y"):date("l, d M Y") ;echo " ".$_GET['product_name']." monthly horoscope - ".$date." ";?> </h5>
              <p class="monthly_content_change content hideContent"><?php echo $horoscope_content['content'];?></p>
              <!-- <p class="moretext">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longerThis is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer..</p> -->
              <div class="show-more">
                <button class="moreless-button btn btn-primary">Show more</button>
              </div>            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade <?php if($content_type=='yearly')echo 'show active'; ?>" id="yearly_tab" role="tabpanel" aria-labelledby="yearly-tab">
        <div class="tab-con">
          <div class="row">
            <div class="col-md-12 pb-3">
              <div class="d-flex mb-3 justify-content-between align-items-center">
                <div class="calender-bg">
                  <form class="row g-3">
                    <div class="col-auto" id="yearly_auto">
                    <?php 
                if ($return_code == '200') {
                  $purchase_data = $response['purchased_data'];
                  $begin = $purchase_data['purchased_date'];
                  $end = $purchase_data['expiry_date'];
                }?>
                      <div class="md-form md-outline input-with-post-icon datepicker" id="customDays">
                        <input placeholder="Select date" type="text" class="yearly_date_picker date_getter" data-content='yearly' name="date1" data-maxdate="<?php echo date("Y-m-d",strtotime($end)); ?>" data-mindate="<?php echo date("Y-m-d",strtotime($begin)); ?>" id="Customization" class="form-control" >
                    </div>
              </div>
                  </form>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end ms-3">
                  <button class="btn btn btn-outline-info me-md-2 prevy-btn common_category" data-type="yearly" data-common_type="prev year" data-date="<?php echo date('Y-01-01',strtotime('-1 year'));?>" type="button"><?php echo date('Y',strtotime('-1 year'));?></button>
                  <button class="btn btn btn-outline-info me-md-2 thisy-btn common_category" data-type="yearly" data-common_type="this year" data-date="<?php echo date('Y-01-01');?>" type="button"><?php echo date('Y');?></button>
                  <button class="btn btn btn-outline-info nexty-btn common_category" data-type="yearly" data-common_type="next year" data-date="<?php echo date('Y-01-01',strtotime('+1 year'));?>" type="button"><?php echo date('Y',strtotime('+1 year'));?></button>
                </div>
              </div>
                <h5 class="card-title-yearly"> <?php $date=$horoscope_content!=''?date("l, d M Y"):date("l, d M Y") ;echo " ".$_GET['product_name']." yearly horoscope - ".$date." ";?> </h5>
              <p class="yearly_content_change content hideContent"><?php echo $horoscope_content['content'];?></p>
                <!-- <p class="moretext">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longerThis is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer..</p> -->
                <div class="show-more">
                <button class="moreless-button btn btn-primary">Show more</button>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	</div>
</div>
<div class="row horo-icon">
	<div class="col-md-3">
		<div class="card shadow-sm mb-4 bg1 change_content" data-type="love" data-date="<?php echo date("Y-m-d");?>" data-common_type="" data-common_content="no" data-content_type="<?php echo $_GET['content_type']?$_GET['content_type']:'daily';?>">
			<div class="card-body text-center">
				<img src="assets/images/horoscope/heart.gif" alt="" class="img-fluid" />
				<h5 class="card-title pt-2"><?php echo $_GET['product_name'];?> Love</h5>
			</div>
		</div>
	</div>
	<div class="col-md-3">
    <div class="card shadow-sm mb-4 bg2 change_content" data-date="<?php echo date("Y-m-d");?>" data-common_content="no" data-common_type="" data-content_type="<?php echo $_GET['content_type']?$_GET['content_type']:'daily';?>" data-type="career">
      <div class="card-body text-center">
        <img src="assets/images/horoscope/briefcase.gif" alt="" class="img-fluid" />
        <h5 class="card-title pt-2"><?php echo $_GET['product_name'];?> Career</h5>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm mb-4 bg3 change_content" data-date="<?php echo date("Y-m-d");?>" data-common_content="no" data-common_type="" data-content_type="<?php echo $_GET['content_type']?$_GET['content_type']:'daily';?>" data-type="finance">
      <div class="card-body text-center">
				<img src="assets/images/horoscope/line-chart.gif" alt="" class="img-fluid" />
				<h5 class="card-title pt-2"><?php echo $_GET['product_name'];?> Finance</h5>
      </div>
    </div>
  </div>
  <div class="col-md-3 ">
    <div class="card shadow-sm mb-4 bg4 change_content" data-date="<?php echo date("Y-m-d");?>" data-common_content="no" data-common_type="" data-content_type="<?php echo $_GET['content_type']?$_GET['content_type']:'daily';?>" data-type="health">
      <div class="card-body text-center">
				<img src="assets/images/horoscope/heartbeat.gif" alt="" class="img-fluid" />
				<h5 class="card-title pt-2"><?php echo $_GET['product_name'];?> Health</h5>
      </div>
    </div>
  </div>
</div>
</section>
</main>    
<?php include("./includes/footer.php");?>
<!-- Picker data -->

<script>

</script> 