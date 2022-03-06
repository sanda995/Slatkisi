<?php
header('Content-type: text/html; charset=utf-8');

function connect(){
	
	$server = "localhost";
	$user = "root";
	$password = "";
	$db = "slatkisi";
	
	$conn = mysqli_connect($server, $user, $password, $db);
	
	if (mysqli_connect_errno()) {
		
		echo "Nije moguća konekcija sa bazom podataka: ".mysqli_connect_error();
	}
	
	return $conn;
}

function disconnect($conn){
	
	mysqli_close($conn);
}

function getKategorijaNavbar($conn) {

	$sql = "select id as ID, Naziv as kat, Stranica as s from kategorija order by id";
	$kategorije = "";
	if ($result = mysqli_query($conn, $sql)) {
		
		if (mysqli_num_rows($result) > 0) {
			
			$kategorije = "<nav class=\"navbar navbar-expand-md bg-light justify-content-center navbar-light\">
								<button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#collapsibleNavbar\">
									<span class=\"navbar-toggler-icon\"></span>
								</button>
								<div class=\"collapse navbar-collapse\" id=\"collapsibleNavbar\">
									<ul class=\"navbar-nav nav-tabs nav-justified w-100\">";
			while ($row = mysqli_fetch_assoc($result)) {
				$a = "";
				if (isset($_SESSION["kategorija"])) {
					if ($_SESSION["kategorija"] == $row["ID"])
						$a = "active";
				}
				$kat = "<li class=\"nav-item\">
							<a class=\"nav-link text-uppercase font-weight-bold kategorije ".$a."\" id=\"".$row["ID"]."\">".$row["kat"]."</a>
						</li>";
						
				$kategorije = $kategorije.$kat;
			}
			$kategorije = $kategorije.'
						</ul>
					</div>
					<script>
						$(document).ready(function(e){
								$(".kategorije").click(function(){
									
									var id = this.id;
									$.ajax({
									
									type: "POST",
									url: "obrada.php",
									data: {setKategorijaSesija: "True", katID: id},
									success: function(arg) {
										window.location.href = "prikazKategorija.php";
									},
									error: function(arg) {
										
										alert(arg);
									}
									});
								});
							});						
					</script>
				</nav>';
		}
		else {
			
			die("Greška u komunikaciji sa bazom podataka");
		}
		
		mysqli_free_result($result);
	}
	else {
			
		die("Greška pri konekciji sa bazom podataka: ".mysqli_error($conn));
	}
	
	return $kategorije;
}

function insertKategorija($conn, $kat, $str){
	
	
	$sql = "INSERT INTO `kategorija`(`ID`, `Naziv`, `Stranica`) VALUES (null,'$kat', '$str')";
	$result = mysqli_query($conn, $sql);
	$kategorija = "";
	if ($result == false) {
		
		die("Ubacivanje u bazu nije uspelo: ".mysqli_error($conn));
	}
	else {
		$kategorija =  "<div class=\"alert alert-success alert-dismissible\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
					<strong>Bravo!</strong> Ubačena je nova kategorija.
			</div>";
	}
	
	return $kategorija;
}

function getKategorijaTable($conn) {
	
	$sql = "select Naziv as n, Stranica as s from kategorija order by Naziv";
	$result = mysqli_query($conn, $sql);
	
	$kategorije = "";
	if ($result = mysqli_query($conn, $sql)) {
		
		if (mysqli_num_rows($result) > 0) {
				
				$kategorije = "<table class=\"table table-striped table-hover\">
									<thead>
										<tr>
											<th>Trenutne kategorije</th>
											<th>Stranica</th>
										</tr>
									</thead>
									<tbody>
								";
				while ($row = mysqli_fetch_assoc($result)){
					
					$kategorije = $kategorije."
										<tr>
											<td>".$row["n"]."</td>
											<td>".$row["s"]."</td>
										</tr>
										";
				}
				$kategorije = $kategorije."
									</tbody>
								</table>";
		}
		else {
			
			die("Greška u komunikaciji sa bazom podataka");
		}
		
		mysqli_free_result($result);
	}
	else {
		
		die("Greška pri konekciji sa bazom podataka: ".mysqli_error($conn));
	}
	
	return $kategorije;
}

function showSidePane() {
	
	
	if (isset($_SESSION["user"])) {
		
		$kor_id = $_SESSION["id"];
		
		$sql = "select ime as i, prezime as p, grad as g, datum_rodjenja as dr, datum_registracije as dreg, slika as s, dodatno as d
				from kor_info
				where korisnik_ID = $kor_id
		       ";
		$conn = connect();
		$result = mysqli_query($conn, $sql);
		
		if ($result) {
			
			if (mysqli_num_rows($result) == 1) {
				
				$row = mysqli_fetch_assoc($result);
				
				$recepti = getMyRecepti($conn, $kor_id);
				$lajkovi = getMyLajkovi($conn, $kor_id);
				
				$panel = '	<div class="row border p-1">
								<div class="row mx-auto w-100">
									<div class="col-sm-12">
										<h3>Zdravo, '.$_SESSION['name'].'!</h3>
									</div>
								</div>
								<div class="row mx-auto w-100">
									<div class="col-sm-12">
										<img src="'.$row["s"].'" class="img-fluid img-rounded mx-auto d-block w-100 mt-1" alt="slika.jpg">
									</div>
								</div>
								<div class="row mx-auto w-100">
									<div class="col-sm-12">
										<p class="m-0 p-0">Ime i prezime:</p>
										<p>'.$row["i"].' '.$row["p"].'</p>
									</div>
								</div>
								<div class="row mx-auto w-100">
									<div class="col-sm-12">
										<p class="m-0 p-0">Grad:</p>
										<p>'.$row["g"].'</p>
									</div>
								</div>
								<div class="row mx-auto w-100">
									<div class="col-sm-12">
										<p class="m-0 p-0">Datum rođenja:</p>
										<p>'.$row["dr"].'</p>
									</div>
								</div>
								<div class="row mx-auto w-100">
									<div class="col-sm-12">
										<p class="m-0 p-0">Datum registracije:</p>
										<p>'.$row["dreg"].'</p>
									</div>
								</div>
								<div class="row mx-auto w-100">
									<div class="col-sm-12">
										<p class="m-0 p-0">Dodatne informacije:</p>
										<p>'.$row["d"].'</p>
									</div>
								</div>
								<div class="row w-100">
									<div class="col-sm-12">
										<div class="form-group w-100">
											<button type="button" class="btn btn-primary mx-auto d-block" name="log_out" onclick="log_out()">Odjavi se</button>
										</div>
									</div>
								</div>
							</div>
							<div class="row border p-1 mt-1">
								'.$recepti.'
							</div>
							<div class="row border p-1 mt-1">
								'.$lajkovi.'
							</div>
								<script>
									function log_out() {
										
										$.ajax({
											type: "POST",
											url: "obrada.php",
											data: {log_out: true},
											success: function (data) {
												window.location.href = "index.php";
											}
										});
									}
								</script>';
			}
			
			mysqli_free_result($result);
		}
		
		disconnect($conn);

		echo $panel;
	}
	else {
		
		echo '<h1>Prijava</h1>
		<div class="form-group">
			<label for="usr">Korisničko ime:</label>
			<input type="text" class="form-control" name="usr" id="usr">
		</div>
		<div class="form-group">
			<label for="pwd">Šifra:</label>
			<input type="password" class="form-control" name="pwd" id="pwd">
		</div> 
		<div class="form-group">
			<button type="submit" class="btn btn-primary float-right m-1" name="sign_in" onclick="prijava()">Prijavi se</button>
			<button type="submit" class="btn btn-link float-right m-1" name="sign_up" onclick="registracija()">Registracija</button>
		</div>
		<div class="modal fade" role="dialog" id="modalID">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="naslov_modal">Greška</h5>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-content text-center" id="sadrzaj_modal">
					</div>
					<div class="modal-footer" id="futer_modal">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Zatvori</button>
					</div>
				</div>
			</div>
		</div>
		
		<script>
			function registracija() {
				
				window.location.href = "registracija.php";
			}
			
			function prijava(){
					var usr = $("#usr").val().trim();
					var pwd = $("#pwd").val().trim();
					
					if (usr === "" || pwd === "") {
						$("#sadrzaj_modal").html("Nedostaju korisničko ime ili šifra! Popunite oba polja i pokušajte ponovo!");
						$("#modalID").modal({
							show: true
						});
					}
					else {
						
						$.ajax({
							type:"POST",
							url:"obrada.php",
							data: {sign_in: "True", usr: usr, pwd: pwd},
							success: function(data){
								var obj = $.parseJSON(data.trim());
								
								if (obj.status) {
									window.location.href="index.php";
								}
								else {
									
									$("#sadrzaj_modal").html("Pogrešni korisničko ime i/ili lozinka! Pokušajte ponovo!");
									$("#modalID").modal({
										show: true
									});
								}
							}
						});
					}
			}
		</script>';
	}
}

function getMyRecepti($conn, $kor_id) {
	
	$sql = "select ID as i, Naziv as n, recept as r
			from recept
			where korisnik_ID = $kor_id
			";
	$result = mysqli_query($conn, $sql);
	
	$retVal = "";
	if ($result) {
		if (mysqli_num_rows($result) > 0) {
			$retVal = '<div class="row mx-auto w-100">
							<div class="col-sm-12 border-bottom">
								<h5>Moji recepti</h5>
							</div>
						</div>';
			while ($row = mysqli_fetch_assoc($result)){
				$recept = '
							<div class="row w-100">
								<div class="col-sm-12">
									<button class="btn btn-link recepti text-left" id="'.$row["i"].'">'.$row["n"].'</button>
								</div>
							</div>
						  ';
				$retVal = $retVal.$recept;
			}
			$retVal = $retVal.'
						<div class="row w-100">
							<div class="col-sm-12 w-100 text-center">
									<button type="button" class="btn btn-primary mt-1 mx-auto" name="novi_rec" onclick="novi_recept()">Objavi recept</button>
							</div>
						</div>
						<script>
							$(document).ready(function(e){
								$(".recepti").click(function(){
									
									var id = this.id;
									prikazi_recept(id);
								});
							});
						
							function novi_recept() {
								
								window.location.href = "receptObjava.php";
							}
							
							function prikazi_recept(id) {
								
								//var id = this.id;
								$.ajax({
									
									type: "POST",
									url: "obrada.php",
									data: {setReceptSesija: "True", recID: id},
									success: function(arg) {
										window.location.href = "prikazRecept.php";
									},
									error: function(arg) {
										
										alert(arg);
									}
								});
								
								
							}
						</script>
					';
		}
		else {
			
			$retVal = '<div class="row mx-auto w-100">
							<div class="col-sm-12 border-bottom">
								<h5>Nema objavljenih recepata</h5>
							</div>
						</div>
						<div class="row w-100">
							<div class="col-sm-12">
								<div class="form-group w-100">
									<button type="button" class="btn btn-primary mx-auto mt-1 d-block" name="novi_rec" onclick="novi_recept()">Objavi recept</button>
								</div>
							</div>
						</div>
						<script>
							function novi_recept() {
								
								window.location.href = "receptObjava.php";
							}
						</script>';
		}
		
		mysqli_free_result($result);
	}
	else {
		$retVal = "Greška u komunikaciji sa bazom!";
	}
	
	return $retVal;
}

function getMyLajkovi($conn, $kor_id) {
	
	$sql = "select r.Naziv as n, r.recept as rec, r.slika as s, r.ID as recID
	        from lajkovi l join korisnik k on l.korisnik_ID = k.ID join recept r on l.recept_ID = r.ID
			where l.korisnik_ID = $kor_id
		   ";
	$result = mysqli_query($conn, $sql);
   
	$retVal = "";
	if ($result) {
	   
	   if (mysqli_num_rows($result) > 0) {
		   
		   $retVal = '<div class="row mx-auto w-100">
									<div class="col-sm-12 border-bottom">
										<h5>Recepti koji mi se sviđaju</h5>
									</div>
								</div>
					';
			$vrteska = '
						<div class="row mx-auto w-100">
							<div id="vrteska_lajkovi" class="carousel slide" data-ride="carousel">
						';
			$indikatori = '<ul class="carousel-indicators">';
			$slajdovi = '<div class="carousel-inner w-100 align-middle h-100">';
			$j = 0;
			while ($row = mysqli_fetch_assoc($result)){
				
				if ($j == 0)
					$indikatori = $indikatori.'<li data-target="#vrteska_lajkovi" data-slide-to="'.$j.'" class="active"></li>';
				else 
					 $indikatori = $indikatori.'<li data-target="#vrteska_lajkovi" data-slide-to="'.$j.'"></li>';
				 
				if ($j == 0)
					$slajdovi = $slajdovi.'
											 <div class="carousel-item active"  style="height: 180px !important;">
												<div class="container-fluid m-0 p-0">
												  <a class="slika_lajk" id="'.$row["recID"].'"><img class="d-block img-fluid align-middle text-center w-100 h-100" src="'.$row["s"].'" alt="img" style="background: cover;"></a>
												  <div class="carousel-caption">
													<p>'.$row["n"].'</p>
												  </div>
												 </div>
												</div>
										';
				else 
					$slajdovi = $slajdovi.'
											 <div class="carousel-item" style="height: 180px !important;">
												<div class="container-fluid m-0 p-0">
												  <a class="slika_lajk" id="'.$row["recID"].'"><img class="d-block img-fluid align-middle text-center w-100 h-100" src="'.$row["s"].'" alt="img" style="background: cover;"></a>
												  <div class="carousel-caption">
													<p>'.$row["n"].'</p>
												  </div>
												</div>
												</div>
										';
				$j = $j+1;
			}
			$slajdovi = $slajdovi.'</div>';
			$indikatori = $indikatori.'</ul>';
			$vrteska = $vrteska.$indikatori.$slajdovi.'
								<a class="carousel-control-prev" href="#vrteska_lajkovi" data-slide="prev">
									<span class="carousel-control-prev-icon"></span>
								  </a>
								  <a class="carousel-control-next" href="#vrteska_lajkovi" data-slide="next">
									<span class="carousel-control-next-icon"></span>
								  </a>
							</div>
						</div>
						<script>
							$(document).ready(function(e){
								$(".slika_lajk").click(function(){
									
									var id = this.id;
									prikazi_recept_lajk(id);
								});
							});
							
							function prikazi_recept_lajk(id) {
								
								//var id = this.id;
								$.ajax({
									
									type: "POST",
									url: "obrada.php",
									data: {setReceptSesija: "True", recID: id},
									success: function(arg) {
										window.location.href = "prikazRecept.php";
									},
									error: function(arg) {
										
										alert(arg);
									}
								});
								
								
							}
						</script>
						';
						
			$retVal = $retVal.$vrteska;
	   }
	   else {
			$retVal = '<div class="row mx-auto w-100">
									<div class="col-sm-12">
										<h5>Ništa mi se ne sviđa</h5>
									</div>
								</div>';
	   }
	}
	else {
		$retVal = "Greška u komunikaciji sa bazom!";
	}
	
	return $retVal;
}

function getKategorijaOpcije() {
	
	$conn = connect();
	
	$sql = "select ID as i, Naziv as n from kategorija order by ID";
	$result = mysqli_query($conn, $sql);
	
	$retVal = "";
	
	if ($result) {
		
		if (mysqli_num_rows($result) > 0) {
			
			while ($row = mysqli_fetch_assoc($result)){
				$retVal = $retVal.'
						<option id="'.$row["i"].'">'.$row["n"].'</option>
						';
			}
		}
		else {
			
			$retVal = "<option>Nema kategorije!</option>";
		}
		
		mysqli_free_result($result);
	}
	else {
		$retval = "<option>Grеška u komunikaciji sa bazom!</option>";
	}
	
	disconnect($conn);
	
	return $retVal;
}

function showObjavaRecept(){
	
	$retVal = "";
	if (isset($_SESSION["user"])) {
		
		$kategorija = getKategorijaOpcije();
		
		$sastojak = '<div class="form-group col-sm-8 p-0 float-left"> \
					<label for="s" class="col-sm-1">-</label> \
					<input type="text" class="form-control col-sm-11 float-right" id="s" name="s[]" >\
				</div>\
				<div class="form-group col-sm-4 p-0 float-right">\
					<label for="k" class="col-sm-5">Količina:<span style="color:red">*</span></label>\
					<input type="text" class="form-control col-sm-6 float-right mr-1" id="k" name="k[]">\
				</div>';
		$korak = '<div class="form-group col-sm-12 p-0"> \
					<label for="korak" class="col-sm-2 float-left">Korak:</label> \
					<textarea type="input" class="form-control col-sm-10 p-0 float-right mr-1" rows="3" id="korak" name="korak[]"></textarea> \
				</div>';
		
		$retVal = '<div class="row" id="sadrzaj">
						<form enctype="multipart/form-data" id="reg_form" method="POST" action="obrada.php" class="col-sm-12">						
							<div class="row p-0 col-sm-12 border">
								<div class="col-sm-12 border-bottom">
									<h1>Novi recept</h1>
								</div>
	
								<div class="row p-0 col-sm-12 mx-auto mt-1">
									<div class="form-group col-sm-5 p-0">
										<label for="naslov" class="col-sm-3">Ime:<span style="color:red">*</span></label>
										<input type="text" class="form-control col-sm-9 float-right" id="naslov" name="naslov">
									</div>
									<div class="form-group col-sm-7 p-0">
										<label for="kategorija" class="col-sm-3">Kategorija:<span style="color:red">*</span></label>
										<select class="form-control col-sm-8 float-right mr-1" id="kategorija" name="kategorija">
											'.$kategorija.'
										</select>
									</div>
								</div>
							</div>
							
							<div class="row p-0 col-sm-12 border mt-1">
								<div class="col-sm-12 border-bottom">
									<h1>Sastojci</h1>
								</div>
							
								<div class="row p-0 col-sm-12 mx-auto mt-1">
							<div class="container container-fluid p-0 m-0" id="sastojci">
									<div class="form-group col-sm-8 p-0 float-left">
										<label for="s" class="col-sm-1">-</label>
										<input type="text" class="form-control col-sm-11 float-right" id="s" name="s[]">
									</div>
									<div class="form-group col-sm-4 p-0 float-right">
										<label for="k" class="col-sm-5">Količina:<span style="color:red">*</span></label>
										<input type="text" class="form-control col-sm-6 float-right mr-1" id="k" name="k[]">
									</div>
								</div>	
									<div class="col-sm-12 p-0">
										<button type="button" class="btn btn-primary float-right m-1" name="dodaj_s" id="dodaj_s" onclick="dodaj_sastojak()">Dodaj sastojak</button>
									</div>
								</div>
							</div>
							
							<div class="row p-0 col-sm-12 border mt-1">
								<div class="col-sm-12 border-bottom">
									<h1>Priprema</h1>
								</div>
								
								<div class="row p-0 col-sm-12 mx-auto mt-1">
							<div class="container container-fluid p-0 m-0" id="koraci">
									<div class="form-group col-sm-12 p-0">
										<label for="korak" class="col-sm-2 float-left">Korak:</label>
										<textarea type="input" class="form-control col-sm-10 p-0 float-right mr-1" rows="3" id="korak" name="korak[]"></textarea>
									</div>
							</div>		
									<div class="col-sm-12 p-0">
										<button type="button" class="btn btn-primary float-right m-1" name="dodaj_k" id="dodaj_k" onclick="dodaj_korak()">Dodaj korak</button>
									</div>
								</div>
							</div>
							
							<div class="row p-0 col-sm-12 border mt-1">
								<div class="col-sm-12 border-bottom">
									<h1>Slika</h1>
								</div>
								
								<div class="row p-0 col-sm-12 mx-auto mt-1">
							<div class="container container-fluid p-0 m-0" id="koraci">
									<div class="form-group col-sm-12 p-0">
										<label for="slika" class="col-sm-2 float-left">Izaberi sliku:</label>
										<input type="file" accept="image/*" class="form-control-file col-sm-9" id="slika" name="slika">
									</div>
							</div>		
									
								</div>
							</div>
							
							<div class="row p-0 col-sm-12 mx-auto mt-1">
								<div class="row p-0 col-sm-12 mt-1">
									<div class="form-group col-sm-12 p-0">
										<button type="submit" class="btn btn-primary float-right" name="novi_recept" id="novi_recept">Objavi recept</button>
									</div>
								</div>
							</div>
						</form>
						<div class="modal fade" role="dialog" id="modalID">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="naslov_modal">Greška</h5>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									<div class="modal-content text-center" id="sadrzaj_modal">
									</div>
									<div class="modal-footer" id="futer_modal">
										<button type="button" class="btn btn-primary" data-dismiss="modal">Zatvori</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<script>
						$(document).ready(function(e){
							$("#reg_form").on("submit", function(e){
								e.preventDefault();
								
								var ime = $("#naslov").val().trim();
								
								if (ime === "") {
									
									$("#naslov_modal").html("Greška");
									$("#sadrzaj_modal").html("Navedite naziv!");
									$("#modalID").modal({
										show: true
									});
									
									return;
								}
								
								var sastojci = $(\'[id="s"]\');
								var kolicine = $(\'[id="k"]\');
								var koraci = $(\'[id="korak"]\');
								
								var s_ok = 0;
								var k_ok = 0;
								
								var text = "";
								var text1 = "";
								
								for (i = 0; i < sastojci.length; i++) {
									
									var s1 = sastojci[i].value.trim();
									var k1 = kolicine[i].value.trim();
									
									text = text + s1 + " " + k1 + "\n";
									
									if (s1 != "")
										s_ok++;
									
									if (k1 != "")
										k_ok++;
								} 
								
								if ((k_ok == 0 && s_ok == 0)) {
									
									$("#naslov_modal").html("Greška");
									$("#sadrzaj_modal").html("Navedite barem jedan sastojak!");
									$("#modalID").modal({
										show: true
									});
									
									return;
								}
								else if (s_ok != k_ok) {
									$("#naslov_modal").html("Greška");
									$("#sadrzaj_modal").html("Navedite količinu za svaki sastojak!");
									$("#modalID").modal({
										show: true
									});
									
									return;
								}
								
								k_ok = 0;
								
								for (i = 0; i < koraci.length; i++) {
									
									var k1 = koraci[i].value.trim();
									
									if (k1 != "")
										k_ok++;
								} 
								
								if (k_ok == 0) {
									
									$("#naslov_modal").html("Greška");
									$("#sadrzaj_modal").html("Navedite postupak pripreme!");
									$("#modalID").modal({
										show: true
									});
									
									return;
								}
								
								var formData = new FormData(this);
								var katId = $(":selected", "#kategorija").attr("id");
								formData.append("novi_recept","true");
								formData.append("katID", katId);
								$.ajax({
									type:"POST",
									url:"obrada.php",
									data: formData,
									contentType: false,
									cache: false,
									processData: false,
									beforeSend: function(){
										$("#novi_recept").attr("disabled","disabled");
										$("#reg_form").css("opacity",".5");
									},
									success: function(arg) {

										$("#reg_form").css("opacity","1");
										$("#novi_recept").removeAttr("disabled");
										
										$("#naslov_modal").html("Bravo");
										$("#sadrzaj_modal").html("Objavili ste novi recept!");
										$("#modalID").modal({
											show: true
										});
										
										window.location.href = "prikazRecept.php";
									},
									error: function(arg) {
										alert(arg);
									}
								});
							});
							
							$("slika").change(function() {
								
								var file = this.files[0];
								var imagefile = file.type;
								var match= ["image/jpeg","image/png","image/jpg"];
								
								if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]))) {
									
									$("#naslov_modal").html("Greška");
									$("#sadrzaj_modal").html("Slika mora biti u .jpg ili .png formatu!");
									$("#modalID").modal({
										show: true
									});
									$("#slika").val("");
									return false;
								}
							});
						});
					
						function dodaj_sastojak(){

							$("#sastojci").append(\''.$sastojak.'\');
						}
						
						function dodaj_korak(){
														
							$("#koraci").append(\''.$korak.'\');
						}
						
						
					</script>
					';
	}
	else {
		$retVal = '<div class="row">
						<h1>Morate biti ulogovani da biste objavili recept!</h1>
					</div>
					';
	}
	
	echo $retVal;
}

function showRecept(){
	
	$receptID = $_SESSION["recept_id"];
	
	$conn = connect();
	
	$sql = "select rec.recept as r, ki.ime as i, ki.prezime as p, rec.Datum as d
			from recept rec join kor_info ki on rec.korisnik_ID = ki.korisnik_ID
			where rec.ID = $receptID
			";
	$result = mysqli_query($conn, $sql);
	
	$r = "Greska u konekciji sa bazom";
	if ($result) {
		
		$row = mysqli_fetch_assoc($result);
		
		$r = file_get_contents($row["r"]);
		
		$r = $r.'
				<div class="row mr-2 mt-2 w-100">
					<div class="col-sm-12 w-100">
						<h6>Autor: '.$row["i"].' '.$row["p"].' <small><i>Objavljeno: '.$row["d"].'</i></small></h6>
					</div>
				</div>
			';
		
		mysqli_free_result($result);
	}
	
	disconnect($conn);
	
	echo $r;
}

function showKomentari(){
	
	$recID =  $_SESSION["recept_id"];
	$retVal = '<div class="row w-100 mt-2">
					<div class="col-sm-12 border-bottom">
						<h3>Komentari</h3>
					</div>
				</div>
				';
	$conn = connect();
	
	if (isset($_SESSION["user"])) {
		
		$korID = $_SESSION["id"];
		
		$br_lajkova = "Nijedan korisnik nije označio da mu se dopada ovaj recept.";
		
		$sql = "select count(*) as br from lajkovi where recept_ID = $recID";
		$result = mysqli_query($conn, $sql);
		
		if ($result) {
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				$br_lajkova = $row["br"]. " korisnika kaže da im se sviđa ovaj recept.";
			}
		}
		else {
			$br_lajkova = "Nije moguće dohvatiti broj sviđanja iz baze.";
		}
		
		$dugme = "Sviđa mi se";
		
		$sql = "select id as ID from lajkovi where korisnik_ID = $korID and recept_ID = $recID";
		$result = mysqli_query($conn, $sql);
		
		if ($result) {
			if (mysqli_num_rows($result) > 0) {
				$dugme = 'Ne sviđa mi se';
			}
			else {
				$dugme = 'Sviđa mi se';
			}
		}
		
		$retVal = $retVal.'
					<div class="row w-100">
						<div class="col-sm-12 w-100">
							 <div class="form-group">
							  <label for="komentar">Novi komentar:</label>
							  <textarea class="form-control" rows="5" id="komentar" name="komentar"></textarea>
							</div> 
							<button type="button" class="btn btn-primary float-right" id="dodaj_komentar" onclick="dodaj_komentar()">Dodaj komentar</button>
							<button type="button" class="btn btn-outline-primary float-right mr-1" id="dodaj_lajk" onclick="dodaj_lajk()">'.$dugme.'</button>
							<p class="float-left" id="lajkovi">'.$br_lajkova.'</p>
							<div class="modal fade" role="dialog" id="modalID">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="naslov_modal">Greška</h5>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									<div class="modal-content text-center" id="sadrzaj_modal">
									</div>
									<div class="modal-footer" id="futer_modal">
										<button type="button" class="btn btn-primary" data-dismiss="modal">Zatvori</button>
									</div>
								</div>
							</div>
						</div>
						</div>
					</div>
					<script>
						function dodaj_lajk(){
							
							var text = $("#dodaj_lajk").text();
							var dataToSend = "True";
							if (text === "Ne sviđa mi se") {
								dataToSend = "False";
							}
							
							$.ajax({
								
								type: "POST",
								url: "obrada.php",
								data:{dodaj_lajk:dataToSend},
								success: function(data) {
									
									var obj = $.parseJSON(data.trim());
									
									if (obj.status) {
										
										if (obj.op) {
											$("#lajkovi").html(obj.val + " korisnika kaže da im se sviđa ovaj recept.");
											$("#dodaj_lajk").text("Ne sviđa mi se");
										}
										else {
											$("#lajkovi").html(obj.val + " korisnika kaže da im se sviđa ovaj recept.");
											$("#dodaj_lajk").text("Sviđa mi se");
										}
									}
									else {
										$("#lajkovi").html("Nije moguće prikazati broj lajkova.");
									}
								},
								error: function(data) {
									alert(data);
								}
							});
						}
					
						function dodaj_komentar(){
							
							var text = $("#komentar").val();
							if (text === "") {
								$("#naslov_modal").html("Greška");
								$("#sadrzaj_modal").html("Unesite komentar!");
								$("#modalID").modal({
									show: true
								});
							}
							else {
								
								$.ajax({
									
									type: "POST",
									url: "obrada.php",
									data: {dodaj_komentar: "True", kom: text},
									success: function (data){
										var obj = $.parseJSON(data.trim());
										if (obj.status){
											window.location.href="prikazRecept.php";
										}
										else {
											$("#naslov_modal").html("Greška");
											$("#sadrzaj_modal").html("Pamcenje komentara nije uspelo!");
											$("#modalID").modal({
												show: true
											});
										}
										
									},
									error: function (data) {
										
										alert(data);
									}
								});
								
								//alert(text);
							}
						}
					</script>
				';
	}
	else {
		
		$retVal = $retVal.'
						<div class="row w-100">
							<div class="col-sm-12 w-100">
								<h3>Da biste ostavili komentar morate biti ulogovani</h3>
							</div>
						</div>
						';
	}
	
	$sql = " select k.komentar as kom, ki.ime as i, ki.prezime as p, ki.slika as s, k.datum as d
			 from komentari k join kor_info ki on k.korisnik_ID = ki.korisnik_ID
			 where k.recept_ID = $recID
			 order by k.datum desc
		   ";
	$result = mysqli_query($conn, $sql);
	
	if ($result) {
		
		if (mysqli_num_rows($result) > 0){
			
			$komentar = "";
			$retVal = $retVal.'
								<div class="row w-100 m-1">
									<div class="col-sm-12 w-100">
								';
			while ($row = mysqli_fetch_assoc($result)) {
				
				$komentar = '<div class="row w-100">
								<div class="media border p-3 w-100 mt-1">
									<img src="'.$row["s"].'" alt="korisnik_slika" class="mr-3 mt-3 img-thumbnail" style="width:60px;">
									<div class="media-body">
										<h6>'.$row["i"].' '.$row["p"].' <small><i>Objavljeno: '.$row["d"].'</i></small></h6>
										<p>'.$row["kom"].'</p>
									</div>
								</div>	
							</div>	
							';
				
				$retVal = $retVal.$komentar;
			}
			$retVal = $retVal.'</div></div>';
		}
		else {
			$retVal = $retVal.'
							<div class="row w-100">
								<div class="col-sm-12 w-100">
									<h3>Korisnici nisu ostavili komentare.</h3>
								</div>
							</div>
							';
		}
		
		mysqli_free_result($result);
	}
	else {
		
		$retVal = $retVal.'
							<div class="row w-100">
							<div class="col-sm-12 w-100">
									<h3>Nije moguće izdvojiti komentare za ovaj recept</h3>
								</div>
							</div>
							';
	}
	
	disconnect($conn);
	
	echo $retVal;
}

function showNewest() {
	
	$retVal = "";
	
	$conn = connect();
	$sql = "select r.ID as i, r.Slika as s, r.Datum as d, r.Naziv as n from recept r order by Datum desc limit 5";
	
	$result = mysqli_query($conn, $sql);
	
	if ($result) {
		
		if (mysqli_num_rows($result) > 0) {
			
			$vrteska = '
						<div class="row mx-auto w-100">
							<div id="vrteska_najnoviji" class="carousel slide w-100 mx-2" data-ride="carousel">
						';
			$indikatori = '<ul class="carousel-indicators">';
			$slajdovi = '<div class="carousel-inner">';
			$j = 0;
			while ($row = mysqli_fetch_assoc($result)){
				
				if ($j == 0)
					$indikatori = $indikatori.'<li data-target="#vrteska_najnoviji" data-slide-to="'.$j.'" class="active"></li>';
				else 
					 $indikatori = $indikatori.'<li data-target="#vrteska_najnoviji" data-slide-to="'.$j.'"></li>';
				 
				if ($j == 0)
					$slajdovi = $slajdovi.'
											 <div class="carousel-item active" style="height: 480px !important;">
												<div class="container-fluid m-0 p-0">
												  <a class="slika_najnoviji" id="'.$row["i"].'"><img class="d-block img-fluid align-middle text-center w-100 h-100" src="'.$row["s"].'" alt="img" style="background: cover;"></a>
												  <div class="carousel-caption">
													<p>'.$row["n"].'</p>
												  </div>
												 </div>
												</div>
										';
				else 
					$slajdovi = $slajdovi.'
											 <div class="carousel-item" style="height: 480px !important;">
												<div class="container-fluid m-0 p-0">
												  <a class="slika_najnoviji" id="'.$row["i"].'"><img class="d-block img-fluid align-middle text-center w-100 h-100" src="'.$row["s"].'" alt="img" style="background: cover;"></a>
												  <div class="carousel-caption">
													<p>'.$row["n"].'</p>
												  </div>
												</div>
												</div>
										';
				$j = $j+1;
			}
			$slajdovi = $slajdovi.'</div>';
			$indikatori = $indikatori.'</ul>';
			$vrteska = $vrteska.$indikatori.$slajdovi.'
								<a class="carousel-control-prev" href="#vrteska_najnoviji" data-slide="prev">
									<span class="carousel-control-prev-icon"></span>
								  </a>
								  <a class="carousel-control-next" href="#vrteska_najnoviji" data-slide="next">
									<span class="carousel-control-next-icon"></span>
								  </a>
							</div>
						</div>
						<script>
							$(document).ready(function(e){
								$(".slika_najnoviji").click(function(){
									
									var id = this.id;
									prikazi_recept_n(id);
								});
							});
							
							function prikazi_recept_n(id) {
								
								//var id = this.id;
								$.ajax({
									
									type: "POST",
									url: "obrada.php",
									data: {setReceptSesija: "True", recID: id},
									success: function(arg) {
										window.location.href = "prikazRecept.php";
									},
									error: function(arg) {
										
										alert(arg);
									}
								});
								
								
							}
						</script>
						';
						
			$retVal = $retVal.$vrteska;
		}
		else {
			$retVal = "Nema recepata u bazi.";
		}
	}
	else {
		
		$retVal = "Nije moguće dohvatiti najnovije recepte.";
	}
	
	disconnect($conn);
	
	echo $retVal;
}

function showMostLiked() {
	
	$retVal = "";
	
	$conn = connect();
	$sql = "SELECT r.Naziv as n, r.Slika as s, r.ID as i, count(l.korisnik_id) as br 
			from recept r join lajkovi l on l.recept_ID = r.ID 
			group by l.recept_ID 
			order by br desc
			limit 5";
	
	$result = mysqli_query($conn, $sql);
	
	if ($result) {
		
		if (mysqli_num_rows($result) > 0) {
			
			$vrteska = '
						<div class="row mx-auto w-100">
							<div id="vrteska_najlajkovaniji" class="carousel slide w-100 mx-2" data-ride="carousel">
						';
			$indikatori = '<ul class="carousel-indicators">';
			$slajdovi = '<div class="carousel-inner">';
			$j = 0;
			while ($row = mysqli_fetch_assoc($result)){

				if ($j == 0)
					$indikatori = $indikatori.'<li data-target="#vrteska_najlajkovaniji" data-slide-to="'.$j.'" class="active"></li>';
				else 
					 $indikatori = $indikatori.'<li data-target="#vrteska_najlajkovaniji" data-slide-to="'.$j.'"></li>';
				 
				if ($j == 0)
					$slajdovi = $slajdovi.'
											 <div class="carousel-item active" style="height: 480px !important;">
												<div class="container-fluid m-0 p-0">
												  <a class="slika_najlajkovaniji" id="'.$row["i"].'"><img class="d-block img-fluid align-middle text-center w-100 h-100" src="'.$row["s"].'" alt="img" style="background: cover;"></a>
												  <div class="carousel-caption">
													<p>'.$row["n"].'</p>
												  </div>
												 </div>
												</div>
										';
				else 
					$slajdovi = $slajdovi.'
											 <div class="carousel-item" style="height: 480px !important;">
												<div class="container-fluid m-0 p-0">
												  <a class="slika_najlajkovaniji" id="'.$row["i"].'"><img class="d-block img-fluid align-middle text-center w-100 h-100" src="'.$row["s"].'" alt="img" style="background: cover;"></a>
												  <div class="carousel-caption">
													<p>'.$row["n"].'</p>
												  </div>
												</div>
												</div>
										';
				$j = $j+1;
			}
			$slajdovi = $slajdovi.'</div>';
			$indikatori = $indikatori.'</ul>';
			$vrteska = $vrteska.$indikatori.$slajdovi.'
								<a class="carousel-control-prev" href="#vrteska_najlajkovaniji" data-slide="prev">
									<span class="carousel-control-prev-icon"></span>
								  </a>
								  <a class="carousel-control-next" href="#vrteska_najlajkovaniji" data-slide="next">
									<span class="carousel-control-next-icon"></span>
								  </a>
							</div>
						</div>
						<script>
							$(document).ready(function(e){
								$(".slika_najlajkovaniji").click(function(){
									
									var id = this.id;
									prikazi_recept_l(id);
								});
							});
							
							function prikazi_recept_l(id) {
								
								//var id = this.id;
								$.ajax({
									
									type: "POST",
									url: "obrada.php",
									data: {setReceptSesija: "True", recID: id},
									success: function(arg) {
										window.location.href = "prikazRecept.php";
									},
									error: function(arg) {
										
										alert(arg);
									}
								});
								
								
							}
						</script>
						';
						
			$retVal = $retVal.$vrteska;
		}
		else {
			$retVal = "Nema recepata u bazi.";
		}
	}
	else {
		
		$retVal = "Nije moguće dohvatiti recepte sa najviše sviđanja.";
	}
	
	disconnect($conn);
	
	echo $retVal;
}

function showMostActive() {
	
	$retVal = "";
	
	$conn = connect();
	$sql = "SELECT count(r.id) as br, ki.ime as i, ki.prezime as p, ki.slika as s, ki.datum_registracije as d
			from recept r join kor_info ki on ki.korisnik_ID = r.korisnik_ID
			group by r.korisnik_ID
			order by br DESC
			limit 3";
	
	$result = mysqli_query($conn, $sql);
	
	if ($result) {
		
		if (mysqli_num_rows($result) > 0) {
			$kartice = '<div class="row mx-auto w-100">';
			while ($row = mysqli_fetch_assoc($result)){
				$kartice = $kartice.'
									<div class="col-sm-4 float-left">
										<div class="card p-1 w-100 h-100">
										  <img class="card-img-top h-50" src="'.$row["s"].'" alt="Card image">
										  <div class="card-body">
													<div class="row mx-auto w-100">
														<div class="col-sm-12">
															<p class="m-0 p-0">Ime i prezime:</p>
															<p>'.$row["i"].' '.$row["p"].'</p>
														</div>
													</div>
													<div class="row mx-auto w-100">
														<div class="col-sm-12">
															<p class="m-0 p-0">Datum registracije:</p>
															<p>'.$row["d"].'</p>
														</div>
													</div>
													<div class="row mx-auto w-100">
														<div class="col-sm-12">
															<p class="m-0 p-0">Broj recepata:</p>
															<p>'.$row["br"].'</p>
														</div>
													</div>
										  </div>
										</div>
									</div>
								';
			}
			$kartice = $kartice.'</div>';
			$retVal = $retVal.$kartice;
		}
		else {
			$retVal = "Nema registrovanih korisnika u bazi.";
		}
	}
	else {
		
		$retVal = "Nije moguće dohvatiti informacije o korisnicima.";
	}
	
	disconnect($conn);
	
	echo $retVal;
}

function showKategorija(){
	
	$recID = $_SESSION["kategorija"];
	$retVal = "";
	
	$conn = connect();
	
	$sql = "SELECT r.Naziv as n, r.Slika as s, r.Datum as d , r.ID as id, ki.ime as i, ki.prezime as p, count(*) as br
			FROM recept r join lajkovi l on l.recept_ID = r.ID join kor_info ki on r.korisnik_ID = ki.korisnik_ID
			where r.kategorija_ID = $recID
			group by l.recept_ID
			
			UNION

			Select r.Naziv as n, r.Slika as s, r.Datum as d, r.ID as id, ki.ime as i, ki.prezime as p,0 as br
			from recept r join kor_info ki on r.korisnik_ID = ki.korisnik_ID
			where r.kategorija_ID = $recID and not exists (select * 
														  from lajkovi l 
														  where l.recept_ID = r.ID)
	       ";
	$result = mysqli_query($conn, $sql);
	
	if ($result) {
		
		if (mysqli_num_rows($result) > 0) {
			$recept = "";
			$retVal = $retVal.'
								<div class="row w-100 m-1">
									<div class="col-sm-12 w-100">
								';
			while ($row = mysqli_fetch_assoc($result)) {
				
				$recept = '<div class="row w-100">
								<div class="media border p-3 w-100 mt-1">
									<img src="'.$row["s"].'" alt="recept_slika" class="mr-3 rounded-circle w-25 h-100 nazivi" id="'.$row["id"].'">
									<div class="media-body">
										<a href="prikazRecept.php" class="nazivi" id="'.$row["id"].'" style="color: black;"><h4 class="border-bottom">'.$row["n"].'<h4/></a>
										<h6>Autor: '.$row["i"].' '.$row["p"].'</h6>
										<h6>Datum objave: <small><i>'.$row["d"].'</i></small></h6>
										<h6>'.$row["br"].' korisnika kaže da im se sviđa ovaj recept.</h6>
									</div>
								</div>								
							</div>	
							';
				
				$retVal = $retVal.$recept;
			}
			$retVal = $retVal.'</div></div>
							<script>
								$(document).ready(function(e){
								$(".nazivi").click(function(){
									
									var id = this.id;
									$.ajax({
									
									type: "POST",
									url: "obrada.php",
									data: {setReceptSesija: "True", recID: id},
									success: function(arg) {
										window.location.href = "prikazRecept.php";
									},
									error: function(arg) {
										
										alert(arg);
									}
									});
								});
							});			
							</script>
							';
		}
		else {
			$retVal = "Nijedan korisnik nije objavio recepte u izabranoj kategoriji.";
		}
	}
	else {
		$retVal = "Nije moguće pristupiti bazi.";
	}
	
	disconnect($conn);
	
	echo $retVal;
}

?>
