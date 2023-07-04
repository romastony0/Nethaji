			</div>
		</main>
		<!-- Content End -->
		
		<!-- Footer Start -->
		<footer id="site-footer" role="contentinfo">
		</footer>
		<!-- Footer End -->
		<script type="text/javascript" src="<?php echo APPLICATION_URL.'resources/js/bootstrap.min.js'; ?>"></script>	
		<script src="<?php echo APPLICATION_URL.'resources/js/jquery.table2excel.js'; ?>"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.js"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
		<script type="text/javascript" src="<?php echo APPLICATION_URL.'resources/js/scripts_new.js?v=3.0'; ?>"></script>
		
		<script type="text/javascript" src="<?php echo APPLICATION_URL.'resources/js/bootstrap-dialog.js'; ?>"></script>
		

		<?php if (isset($_SESSION['userid'])) { ?>
			<?php
				$response = array();
				$response['basedata'] = strval($_SESSION['userid']);
				$response['version'] = '';
				$enc_response = json_encode($response);
			?>
			<script>
				(function($) {
					data = JSON.parse(<?php echo $enc_response; ?>);
					data = data.basedata;
				});
			</script>
		<?php } ?>
	</div>
</body>
</html>