<?php
include("includes/header.php");
$post_data = array(
  'oauth'      => '7ff7c3ed4e791da7e48e1fbd67dd5b72',
  'user_id'     => $_SESSION['user_id'],
  'action'     => "get_festival_data"
);

$response     = curl_hit($post_data);
$festival_data = $response['returndata'];


$curr_mon_year = date("F - Y");

$post_data = array(
  'oauth'      => '7ff7c3ed4e791da7e48e1fbd67dd5b72',
  'user_id'     => $_SESSION['user_id'],
  'action'     => "get_panchang_location"
);

$get_location_details     = curl_hit($post_data);
$panchang_location_data = $get_location_details['returndata'];

?>

<!-- Festival Info  -->
<section id="info">
  <div class="container">
    <div class="row">
      <!-- Festival Calendar -->
      <div class="col-sm-6">
        <div class="card">
          <h5 class="card-header bg-dark text-center text-white">Festival Calendar | <span class="festival_month_year"><?php echo $curr_mon_year; ?></span></h5>
          <div class="card-body bg-white festival_reset">
            <?php $i = 1;
            foreach ($festival_data as $val) {
              if ($i % 2 == 0) { ?>
                <div class="cardinfo row ">
                  <div class="col-md-8">
                    <span><?php echo $val['festival_date']; ?></span>
                    <h5><?php echo $val['festival_name']; ?></h5>
                  </div>
                  <div class="col-md-4">
                    <img src="./assets/images/festival_data/<?php echo $val['festival_image']; ?>">
                  </div>
                </div>
              <?php } else { ?>
                <div class="cardinfo row ">
                  <div class="col-md-4">
                    <img src="./assets/images/festival_data/<?php echo $val['festival_image']; ?>">
                  </div>
                  <div class="col-md-8">
                    <span><?php echo $val['festival_date']; ?></span>
                    <h5><?php echo $val['festival_name']; ?></h5>
                  </div>

                </div>

            <?php }
              $i++;
            } ?>
          </div>
          <div class="card-footer d-flex justify-content-between">
            <button class="btn btn-info fest_month pre_month" data-date=<?php echo date("Y-m-d"); ?>>
              <span class="ti-angle-left "></span> Pre Month </button>
            <button class="btn btn-info fest_month next_month" data-date=<?php echo date("Y-m-d"); ?>> Next Month <span class="ti-angle-right"></span>
            </button>
          </div>
        </div>
      </div>
      <!-- Festival Calendar // -->

      <!-- PANCHANG Calendar -->
      <div class="col-sm-6">
        <div class="card ">
          <div class="card-header bg-dark text-center text-white">
            <!--  <h5  data-bs-toggle="modal" data-bs-target="#panchang_date">PANCHANG-->
            <h5>PANCHANGAM
              <span id="panchangam_ch_date"></span>
              <span>
                <img src="./assets/images/icons/arrow.png" class="panchang_date">
              </span>
              <!--  <small  data-bs-toggle="modal" data-bs-target="#panchang_location">-->
              <small>
                <span id="panchangam_ch_loc"></span> <span>
                  <img src="./assets/images/icons/arrow.png" class="panchang_location">
                </span>
              </small>
            </h5>
          </div>
          <div class="card-body" id="panchangam">

            <!-- <div class="card-body" id="panchangam" style="padding: 0px !important;"> -->
            <!-- <div id="panchangam"> </div> -->
          </div>
        </div>
      </div>
      <!-- PANCHANG Calendar -->
    </div>
  </div>
</section>
<?php include("includes/footer.php"); ?>