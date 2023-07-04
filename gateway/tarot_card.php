<?php 
include("includes/header.php");

$post_data = array(
			'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
			'user_id' 		=> $_SESSION['user_id'],
			'tarot_type' 		=> $_GET['category'],
			'action' 		=> "get_tarot_category_name");

$response 		= curl_hit($post_data);
$tarot_category_name = $response['returndata'];
/* echo '<pre>';
print_r($tarot_category_name);
 */
?>
<form action="tarot_single.php?category=<?php echo $_GET['category'];?>" method="POST" id="tarot_form_submit">
 <input type="hidden" id="category_card_name" name="category_card_name" >

 <main class="flex-shrink-0 container mb-3">
   <section id="tarot">
     <div class="container">
       <div class="text-center justify-content-center  pt-5">
         <h3>TAROT</h3>
         <h6>What does the future have in store for you? Get the answers you <br />need with these Tarot readings. </h6>
       </div>
       <div class="row text-center py-5">
         <!-- -->
		 <?php foreach($tarot_category_name as $val){?>
         <div class="col-md-1 card-container">
           <div class="card card-flip">
             <div class="front card-block">
               <img src="./assets/images/tarot/tarot-front.png" alt="" title="">
             </div>
             <div class="back card-block">
				
			   <a class="tarot_submit"  data-name_of_card="<?php echo $val['name_of_card'];?>">
			  
                 <img src="./assets/images/tarot/tarot-front.png" alt="" title="">
               </a>
             </div>
           </div>
         </div>
		 <?php }?>
         <!--// -->
         
      
     </div>
     </div>
   </section>
 </main>
 </form>



 <?php include("includes/footer.php");?>	
<script type="text/javascript">	
	
<!-- NO JS VERSION: https://codepen.io/nicolaskadis/full/brQEOd/ -->


$(".tarot_submit").click(function() {
	
var card_name =$(this).data('name_of_card');	
	$('#category_card_name').val(card_name);
	
		$('#tarot_form_submit').submit();
		
	});

$(document).ready(function() {
  var front = document.getElementsByClassName("front");
  var back = document.getElementsByClassName("back");

  var highest = 0;
  var absoluteSide = "";

  for (var i = 0; i < front.length; i++) {
    if (front[i].offsetHeight > back[i].offsetHeight) {
      if (front[i].offsetHeight > highest) {
        highest = front[i].offsetHeight;
        absoluteSide = ".front";
      }
    } else if (back[i].offsetHeight > highest) {
      highest = back[i].offsetHeight;
      absoluteSide = ".back";
    }
  }
  $(".front").css("height", highest);
  $(".back").css("height", highest);
  $(absoluteSide).css("position", "absolute");
});
 </script>