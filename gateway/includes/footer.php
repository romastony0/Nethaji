<!-- FOOTER -->
<footer class="footer mt-auto py-3 " id="footer">
      <div class="container-fluid d-flex justify-content-between bd-highlight mb-3">
        <div class="p-2 bd-highlight">
          <h5 class="text-white">About Astromart</h5>
          <div>
            <span id="copyright"> &#169; <script>
                document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
              </script>
            </span> Digital Invascom . All Rights Reserved
          </div>
        </div>
        <div class="d-flex align-items-center social">
          <div class="me-3">
            <a href="#">
              <span class="ti-facebook"></span>
            </a>
          </div>
          <div class="me-3">
            <a href="#">
              <span class="ti-twitter"></span>
            </a>
          </div>
          <div>
            <a href="#">
              <span class="ti-instagram"></span>
            </a>
          </div>
        </div>
        <div class="d-flex align-items-center social">	<a href="privacy_policy.php" style="text-decoration:none;font-size:17px;"> Privacy </a>| <a href="terms_of_use.php" style="text-decoration:none;font-size:17px;">Terms</a> </div>
    </footer>
</div>
     
    <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
    <script src="./assets/slick/slick.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
      $('.single-item').slick({
        arrows: false,
        autoplay: true,
        autoplaySpeed: 3000,
		
		
      });
      $('.zodiac').slick({
		arrows: true,
        slidesToShow: 10,
        slidesToScroll: 4,
        autoplay: false,
        autoplaySpeed: 3000,
		
		responsive: [
			{
			  breakpoint: 1024,
			  settings: {
				slidesToShow: 10,
				slidesToScroll: 3,
				infinite: true,
				dots: true
			  }
			},
			{
			  breakpoint: 600,
			  settings: {
				slidesToShow: 2,
				slidesToScroll: 2
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
				slidesToShow: 3,
				slidesToScroll: 3
			  }
			}
			// You can unslick at a given breakpoint now by adding:
			// settings: "unslick"
			// instead of a settings object
		  ]
      });
      $("#panchang").click(function() {
        $(".hover-info").toggle(1000);
      });
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function() {
        'use strict'
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms).forEach(function(form) {
          form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }
            form.classList.add('was-validated')
          }, false)
        })
      })()
    </script>
 
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script type="text/javascript" src="./assets/js/pignose.calendar.full.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.6/simplePagination.min.css">

    <script type="text/javascript" src="./assets/js/scripts.js"></script>
			<!--<script src="./assets/js/bootstrap452.min.js"></script>-->
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
	<link href= "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	 <script src= "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.6/jquery.simplePagination.min.js"></script>



  </body>
</html>
<?php include("./modal_popup.php"); ?>
