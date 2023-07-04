<?php 
include("includes/header.php");
$post_data = array(
			'oauth'			=> '7ff7c3ed4e791da7e48e1fbd67dd5b72',
			'user_id' 		=> $_SESSION['user_id'],
			'tarot_type' 		=> $_GET['category'],
			'name_of_card' 		=> $_POST['category_card_name'],
			'action' 		=> "get_tarot_category_content");

$response 		= curl_hit($post_data);
$tarot_category_content = $response['returndata'];
/* 
echo '<pre>';
print_r($tarot_category_content);  */
?>
	<!-- Mani  -->
	<main class="flex-shrink-0 container mb-3">
	  <section id="tarot">
	    <div class="container">
	      <nav class="my-5" aria-label="breadcrumb">
	        <ol class="breadcrumb">
	          <li class="breadcrumb-item">
	            <a href="tarot.php">Tarot</a>
	          </li>
	          <li class="breadcrumb-item">
	            <a href="tarot_card.php?category=<?php echo $tarot_category_content['tarot_type']; ?>"><?php echo $tarot_category_content['tarot_type']; ?> Tarot </a>
	          </li>
	          <li class="breadcrumb-item active" aria-current="page">Your Reading</li>
	        </ol>
	      </nav>
	      <div class="row d-flex align-items-center justify-content-center mb-5">
	        <div class="col-md-4 text-center">
	          <img src="./assets/images/tarot/tarot_cards/<?php echo $tarot_category_content['tarot_image_name']; ?>" class="img-fluid" alt="">
	        </div>
	        <div class="col-md-8">
	          <h2><?php echo strtoupper ($tarot_category_content['tarot_type']); ?> TAROT READING </h2>
	          <h4 class="py-4"><?php echo $tarot_category_content['name_of_card']; ?></h4>
	          <p><?php echo $tarot_category_content['tarot_content']; ?></p>
	          <button class="btn btn-primary btn-lg"><a href="tarot_card.php?category=<?php echo $tarot_category_content['tarot_type']; ?>"> Pick Another Card</a></button>
	        </div>
	      </div>
	    </div>
	  </section>
	</main>
	
   
 <?php include("includes/footer.php");?>	