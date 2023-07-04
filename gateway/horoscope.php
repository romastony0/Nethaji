<?php 
include("./includes/header.php");
$post_data = array(
  'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
  'user_id' 		=> $_SESSION['user_id'],
  'action' 		=> "get_horoscope_details",
  'content_type' => $_GET['content_type']?$_GET['content_type']:"daily",
  'date' => date('Y-m-d'),
  );
  $response 		= curl_hit($post_data);
  //print_r($response);
  $return_code = $response['returncode'];
//echo $return_code;
  $horoscope_content = $response['returndata'];
?>
      <!-- zodiac  //-->
      <style>
        .justify-content-end{
          justify-content:flex-end !important;
        }
        #pagination {
          display:flex;
          width:auto;
          /* margin-left:74%; */
          margin-bottom:20px;
        }
        .page-link, .current{
          padding: 0.375rem 0.75rem !important;
        }
        .light-theme span,a{
          margin:0 !important;
          border-radius:0 !important;
        }
        .light-theme a{
          background:white!important;
          color:#0d6efd!important;
        }
        </style>
      <input type="hidden" name="user_id" id="user_id" value = "<?php echo $_SESSION['user_id'];?>">
      <input type="hidden"  id="mob_no_ses" value="<?php echo $_SESSION['mobile_no']; ?>">
      <section id="horoscope">
        <div class="container">
          <h4 class="py-3">Your Horoscope for Today</h4>
          <div class="row">
            <div class="col-md-9 order-sm-first order-last">
            <div class="wrapper">
              <?php $i = 1; foreach($horoscope_content as $horo_content){?>
                <div class="item">
              <div class="col-md-12 pb-3">
                <div class="card h-100 bg<?php echo $i++;?>">
                <?php if($i==7)$i=1;?>
                  <div class="card-body">
                    <h5 class="card-title"> <?php echo $horo_content['product_name'];?></h5>
                    <p style="display: -webkit-box;overflow : hidden;text-overflow: ellipsis;-webkit-line-clamp: 2;-webkit-box-orient: vertical; "><?php echo $horo_content['content']?></p>
                    <a href="<?php echo  APPLICATION_URL;?>gateway/horoscope-content.php?product_id=<?php echo $horo_content['product_id'];?>&product_name=<?php echo $horo_content['product_name'];?>&content_type=daily#daily_auto" class="read_more_landing" >Read More&nbsp<i class="fa-solid fa-arrow-right"></i></a>
                  </div>
                </div>
              </div>
              </div>
              <?php }?>
              </div>
              <div id="pagination" class="justify-content-end "></div>
            </div>
            
            <div class="col-md-3 horo-icon ">
              <div class="card shadow-sm mb-4">
				          <div class="card-body d-flex align-items-center justify-content-between horoscope_content_redirect" data-content_type="weekly">
                  <h3 class="card-title">Weekly</h3>
                  <img src="assets/images/horoscope/week-icon.png" alt="" class="img-fluid" />
                </div>
              </div>
              <div class="card shadow-sm mb-4">
                <div class="card-body d-flex align-items-center justify-content-between horoscope_content_redirect" data-content_type="monthly">
                  <h3 class="card-title">Monthly</h3>
                  <img src="assets/images/horoscope/month-icon.png" alt="" class="img-fluid" />
                </div>
              </div>
              <div class="card shadow-sm mb-4">
                <div class="card-body d-flex align-items-center justify-content-between horoscope_content_redirect1" data-content_type="yearly">
                  <h3 class="card-title">Yearly</h3>
                  <img src="assets/images/horoscope/year-icon.png" alt="" class="img-fluid" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
    <?php include("./includes/footer.php");?>

    <script>
      $(".wrapper .item").slice(3).hide();
        $('#pagination').pagination({
  
            // Total number of items present
            // in wrapper class
            items: 12,
  
            // Items allowed on a single page
            itemsOnPage: 3, 
            onPageClick: function (noofele) {
                $(".wrapper .item").hide()
                    .slice(3*(noofele-1),
                    3+ 3* (noofele - 1)).show();
            }
        });
  </script>