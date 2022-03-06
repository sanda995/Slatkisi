<?php
	
	// pokreni novu ili nastavi trenutnu sesiju
	session_start();
?>
<!DOCTYPE html>
<html lang="sr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container-fluid p-0 m-0">
			<!-------------- start header ------------------------------->
			<div class="jumbotron jumbotron-fluid text-center" style="background:url(images/images1.jpg); background-repeat: repeat-x; padding-bottom:0; font-family: Algerian;">
				
					<a href="index.php" style="color:black"><h1 class="display-1">Slatkiši</h1></a>
					<?php
						include 'common.php';
						
						$conn = connect();
						
						$menu = getKategorijaNavbar($conn);
						
						disconnect($conn);
						
						echo $menu;
					?>
			
			</div>
			<!-------------- end header ------------------------------->