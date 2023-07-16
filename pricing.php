<!DOCTYPE html>
<html lang="en">

<head>
	<title>Pricing</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<?php
	include('style.php')
		?>
	<?php

	$conn = mysqli_connect('localhost', 'root', '', 'tattoo_db');

	?>
</head>

<body>

	<div id="colorlib-page">
		<a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle"><i></i></a>
		<?php
		include('sidebar.php')
			?>
		<div id="colorlib-main">
			<section class="ftco-section ftco-no-pt ftco-no-pb">
				<div class="container px-md-0">
					<div class="row d-flex no-gutters">

						<?php
						$select_query = "SELECT * FROM price";
						$select_result = mysqli_query($conn, $select_query);

						// Check for query execution errors
						if (!$select_result) {
							echo "Error executing select query: " . mysqli_error($conn);
							exit;
						}

						$rows = mysqli_fetch_all($select_result, MYSQLI_ASSOC);
						foreach ($rows as $row) {
							?>
							<div class="col-md-3 pricing">
								<div class="row no-gutters align-items-center">

									<div href="#" class="img w-100 js-fullheight d-flex align-items-center"
										style="background-image: url(<?php echo 'uploaded_img/' . $row['image']; ?>);">
										<div class="text p-4 ftco-animate">
											<h3>
												<?php echo $row['servicename']; ?>
											</h3>
											<ul>
												<li><span>Price:
													</span>
													<?php echo $row['pricevalue']; ?>
												</li>
											</ul>
											<p><a href="contact.php" class="btn-custom">Book Now</a></p>
										</div>
									</div>

								</div>
							</div>
							<?php
						}
						?>


					</div>
				</div>
			</section>
		</div><!-- END COLORLIB-MAIN -->
	</div><!-- END COLORLIB-PAGE -->

	<!-- loader -->
	<div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
			<circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
			<circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
				stroke="#F96D00" />
		</svg></div>


	<script src="js/jquery.min.js"></script>
	<script src="js/jquery-migrate-3.0.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.easing.1.3.js"></script>
	<script src="js/jquery.waypoints.min.js"></script>
	<script src="js/jquery.stellar.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/jquery.animateNumber.min.js"></script>
	<script src="js/scrollax.min.js"></script>
	<script
		src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
	<script src="js/google-map.js"></script>
	<script src="js/main.js"></script>

</body>

</html>