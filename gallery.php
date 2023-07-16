<!DOCTYPE html>
<html lang="en">

<head>
	<title>Gallery</title>
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
				<div class="container  px-0 w-100-container">
					<div class="row d-flex no-gutters">
						<!-- dynamic image -->
						<?php
						$select = mysqli_query($conn, "SELECT * FROM products");
						?>

						<?php
						$limit = 12; // Set the number of products to display per page
						$page = isset($_GET['page']) ? $_GET['page'] : 1; // Get the current page number
						$offset = ($page - 1) * $limit; // Calculate the offset for the query
						
						$result = mysqli_query($conn, "SELECT * FROM products LIMIT $offset, $limit");

						if ($result) {
							if (mysqli_num_rows($result) > 0) {
								while ($row = mysqli_fetch_assoc($result)) {
									// Your existing code for displaying products information
									?>
									<div class="col-md-6 portfolio-wrap-2">
										<div class="row no-gutters align-items-center">
											<div class="img w-100 js-fullheight d-flex align-items-center img-modal"
												data-toggle="modal" data-target="#imageModal"
												style="background-image: url(uploaded_img/<?php echo $row['image']; ?>);">
												<div class="text p-4 p-md-5 ftco-animate">
													<div class="desc">
														<div class="top">
															<span class="subheading">
																<?php echo $row['subname']; ?>
															</span>
															<h2 class="mb-4">
																<a href="">
																	<?php echo $row['name']; ?>
																</a>
															</h2>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<?php
								}
							} else {
								echo '<div>no data found</div>'; // Display an empty div
							}
						} else {
							// Display an error message or handle the error in an appropriate way
							echo "Query failed: " . mysqli_error($conn);
						}

						$total_products = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM products"));
						$total_pages = ceil($total_products / $limit); // Calculate the total number of pages
						
						if ($page < $total_pages) { // Display the "Load More" button if there are more products to show
							$next_page = $page + 1;
							?>
						<a href="?page=<?php echo $next_page; ?>"
							class="btn-custom-load d-block w-100 text-center py-4"><span class="fa fa-refresh">Load
								More</a>
						<?php
						} else if ($page == $total_pages && $total_pages > 1) { // Display the "Previous" button on the last page if there are more than one page
							$previous_page = $page - 1;
							?>
								<a href="?page=<?php echo $previous_page; ?>"
									class="btn-custom-load d-block w-100 text-center py-4"><span
										class="fa fa-chevron-left">Previous</a>
								<?php
						}
						?>




						<!-- dyanamic image -->
					</div>

				</div>
			</section>
		</div><!-- END COLORLIB-MAIN -->
	</div><!-- END COLORLIB-PAGE -->
	<!-- Modal -->
	<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<img id="modalImage" class="img-fluid" src="" alt="">
				</div>
			</div>
		</div>
	</div>

	<!-- loader -->
	<div id="ftco-loader" class="show fullscreen"><lottie-player
			src="https://assets8.lottiefiles.com/packages/lf20_ypj6q6tf.json" background="transparent" speed="1"
			style="width: 300px; height: 300px;" loop autoplay></lottie-player>
	</div>
	<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

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
	<script>
		$(document).ready(function () {
			$('.img-modal').click(function () {
				var imgSrc = $(this).css('background-image').replace(/url\(['"]?(.*?)['"]?\)/i, "$1");
				$('#modalImage').attr('src', imgSrc);
				$('#imageModal').modal('show');
			});
		});	</script>
</body>

</html>