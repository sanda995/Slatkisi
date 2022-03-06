<?php
include 'header.php';
?>
			<!-------------- start content ------------------------------->
			
			<div class="container container-fluid">
				<div class="row">
					<div class="col-sm-8">
							<h1 class="border-bottom mb-2">Objavljeni recepti</h1>
							<?php
								showKategorija();
							?>
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