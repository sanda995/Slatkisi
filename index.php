<?php
include 'header.php';
?>

			<!-------------- start content ------------------------------->
			<div class="container container-fluid">
				<div class="row">
					<div class="col-sm-8">
						<div class="row">
								<div class="col-sm-12">
									<h1 class="border-bottom mb-2">Najnoviji recepti</h1>
								</div>
								<?php
									showNewest();
								?>
						</div>
						
						<div class="row">
								<div class="col-sm-12">
									<h1 class="border-bottom mb-2">Recepti sa najviše lajkova</h1>
								</div>
								<?php
									showMostLiked();
								?>
						</div>
						<div class="row">
								<div class="col-sm-12">
									<h1 class="border-bottom mb-2">Najaktivniji korisnici</h1>
								</div>
								<?php
									showMostActive();
								?>
						</div>
					</div>
					<div class="col-sm-3">
							<?php
								showSidePane();
							?>						
					</div>
				</div>
			</div>
			
			<!-------------- end content ------------------------------->
<?php
include 'footer.php';
?>