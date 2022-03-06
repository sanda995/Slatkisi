<?php

include 'common.php';

session_start();

if (isset($_POST["insert_kat"])) {
	
	$conn = connect();
	
	$kat=$_POST["kat"];
	$str=$_POST["str"];
	
	$poruka = insertKategorija($conn, $kat, $str);
	$tabela = getKategorijaTable($conn);
	
	disconnect($conn);

	echo json_encode(array("poruka"=>$poruka,"tabela"=>$tabela));
}
else if (isset($_POST["log_out"])){
	
	session_destroy();
	
	echo "Success";
}
else if (isset($_POST["sign_in"])){
	
	$retVal = array("status"=>false, "ID"=>0);
	
	if (isset($_POST["usr"]) and isset($_POST["pwd"])) {
		
		$usr = $_POST["usr"];
		$pwd = $_POST["pwd"];
		
		$conn = connect();
		
		$sql = "select ID as id from korisnik where username='$usr' and lozinka='$pwd'";
		$result = mysqli_query($conn,$sql);
		
		if ($result) {
			
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				
				$_SESSION["user"] = true;
				$_SESSION["name"] = $usr;
				$_SESSION["id"] = $row["id"];
				
				$retVal["ID"] = $row["id"];
				$retVal["status"] = true;
			}
			
			mysqli_free_result($result);
		}
		
		disconnect($conn);
	}
	
	echo json_encode($retVal);
}
else if (isset($_POST["sign_up"])){
	
	header("Refresh: 0, url=registracija.php");
}
else if (isset($_POST["registracija"])) {
	
	
	$usr = $_POST["usr"];
	$pass = $_POST["pass"];
	$mejl = $_POST["mejl"];
	
	$retVal = array("status"=>false, "exists"=>false, "inserted"=>false);
	
	$conn = connect();
	
	$sql = "select * from korisnik where username = '$usr' or mejl = '$mejl'";
	$result = mysqli_query($conn, $sql);
	
	if (!$result) {
		$retVal["status"] = false;
		$retVal["exists"] = false;
		
	}
	else {
		if (mysqli_num_rows($result) > 0) {
			
			$retVal["status"] = true;
			$retVal["exists"] = true;
		}
		else {
			
			$sql = "INSERT INTO `korisnik` (`ID`, `username`, `lozinka`, `mejl`) VALUES (null, '$usr', '$pass', '$mejl')";
			$result1 = mysqli_query($conn, $sql);
			
			if (!$result1) {
				
				$retVal["status"] = true;
				$retVal["exists"] = false;
				$retVal["inserted"] = false;
			}
			else {
				
				$sql = "select id as id from korisnik where username='$usr' and mejl='$mejl'";
				$result2 = mysqli_query($conn, $sql);
				
				if (!$result2) {
					$retVal["status"] = true;
					$retVal["exists"] = false;
					$retVal["inserted"] = false;
				}
				else {
					if (mysqli_num_rows($result2) != 1) {
						
						$retVal["status"] = true;
						$retVal["exists"] = false;
						$retVal["inserted"] = false;
					}
					else {
						
						$row = mysqli_fetch_assoc($result2);
						$id = $row["id"];
						
						$grad = $_POST["grad"];
						$ime = $_POST["ime"];
						$prezime = $_POST["prezime"];
						
						
						$dr = $_POST["dr"];
						$info = $_POST["info"];
						
						$uploadFileName = "";
						if (isset($_FILES['slika']['name']) && !empty($_FILES['slika']['name'])) {
			
			
							$slika = $_FILES['slika']['name'];
							
							$ext = pathinfo($slika, PATHINFO_EXTENSION);
							
							$targetfolder = "./users/";
							
							$targetfolder = $targetfolder.date("YmdHis").substr($slika,0,5).".".$ext;
							
							if(move_uploaded_file($_FILES['slika']['tmp_name'], $targetfolder)) {

								$uploadFileName = $targetfolder;
							 }
							 else {

								die("Problem uploading file");
							 }
						}
						else {
							$uploadFileName = "./users/unknown.png";
						}
						
						$sql = "INSERT INTO `kor_info`(`ID`, `korisnik_ID`, `ime`, `prezime`, `grad`, `datum_rodjenja`, `slika`, `dodatno`) 
								VALUES (null, $id, '$ime','$prezime','$grad', '$dr', '$uploadFileName', '$info')";
						$result3 = mysqli_query($conn, $sql);
						
						if ($result3) {
						
							$retVal["status"] = true;
							$retVal["exists"] = false;
							$retVal["inserted"] = true;

							$_SESSION["user"] = true;
							$_SESSION["name"] = $usr;
							$_SESSION["id"] = $id;
						}
						else {
							$retVal["status"] = true;
							$retVal["exists"] = false;
							$retVal["inserted"] = false;
						}
					}
					
					mysqli_free_result($result2);
				}
			}
		}
		
		mysqli_free_result($result);
	}
	disconnect($conn);

	echo json_encode($retVal);
}
else if (isset($_POST["redirect"])) {
	
	header("Refresh: 0, url=".$_POST["page"]);
}
else if (isset($_POST["novi_recept"])) {
	
	$naziv = $_POST["naslov"];
	$kategorija = $_POST["kategorija"];
	$katID = $_POST["katID"];
	
	$sastojci="";
	
	$j = 0;
	for ($i = 0; $i < count($_POST["s"]); $i++){
		
		if (!empty($_POST["s"][$i])) {
			
			$sastojak = '<div class="row w-100">
							<div class="col-sm-7">
								- '.$_POST["s"][$i].':
							</div>
							<div class="col-sm-5 float-right">
								'.$_POST["k"][$i].'
							</div>
						</div>
						';
			$sastojci = $sastojci.$sastojak;
			$j++;
		}
	}

	$koraci = "";
	
	$j = 0;
	for ($i = 0; $i < count($_POST["korak"]); $i++){
		
		if (!empty($_POST["korak"][$i])) {

			$korak = '<div class="row w-100">
							<div class="col-sm-12">
								'.($j+1).'. '.$_POST["korak"][$i].'
							</div>
						</div>
						';
			$koraci = $koraci.$korak;
			$j++;
		}
	}
	
	$uploadFileName = "";
	if (isset($_FILES['slika']['name']) && !empty($_FILES['slika']['name'])) {


		$slika = $_FILES['slika']['name'];
		
		$ext = pathinfo($slika, PATHINFO_EXTENSION);
		
		$targetfolder = "./recepti/";
		
		$targetfolder = $targetfolder.date("YmdHis").substr($slika,0,5).".".$_SESSION["id"].".".$ext;
		
		if(move_uploaded_file($_FILES['slika']['tmp_name'], $targetfolder)) {
			
			$uploadFileName = $targetfolder;
		 }
		 else {

			die("Problem uploading file");
		 }
	}
	else {
		$uploadFileName = "./recepti/default.jpg";
	}
	
	$retVal = '<div class="row mr-2 w-100">
					<div class="col-sm-12 border-bottom">
						<h1>'.$naziv.'</h1>
					</div>
				</div>
				<div class="row mr-2 w-100">
					<div class="col-sm-6">
						<img src="'.$uploadFileName.'" class="img-fluid img-rounded mx-auto d-block w-100 mt-1" alt="slika.jpg">
					</div>
					<div class="col-sm-6">
						<h3>Sastojci</h3>
						'.$sastojci.'
					</div>
				</div>
				<div class="row mr-2 w-100">
					<div class="col-sm-12 border-bottom">
						<h3>Priprema</h3>
					</div>
						'.$koraci.'
				</div>
			';
	
	$file_name = "./recepti/".date("YmdHis")."recept".$_SESSION["id"].".php";
	$file = fopen($file_name, "w");
	fwrite($file, $retVal);
	fclose($file);
	
	$conn = connect();
	$id = $_SESSION["id"];
	$sql = "insert into `recept`(`ID`, `kategorija_ID`, `korisnik_ID`, `recept`, `Naziv`, `Slika`) 
			VALUES (null, $katID, $id, '$file_name', '$naziv','$uploadFileName')
			";
	$result = mysqli_query($conn, $sql);
	
	$status = array("status"=>false);
	
	if ($result) {
		
		$sql = "select ID as i
				from recept
				where recept = '$file_name'";
		$result = mysqli_query($conn, $sql);
		
		if ($result) {
			$row = mysqli_fetch_assoc($result);
			
			$_SESSION["recept_id"] = $row["i"];
			$status["status"] = true;
			
			mysqli_free_result($result);
		}	
	}
	
	disconnect($conn);

	echo json_encode($status);
}
else if (isset($_POST["setReceptSesija"])){
	
	$_SESSION["recept_id"] = $_POST["recID"];
	
	echo "true";
}
else if (isset($_POST["setKategorijaSesija"])){
	
	$_SESSION["kategorija"] = $_POST["katID"];
	
	echo "true";
}
else if (isset($_POST["dodaj_komentar"])) {
	
	$kom = $_POST["kom"];
	$rec_id = $_SESSION["recept_id"];
	$id = $_SESSION["id"];
	
	$conn = connect();
	
	$sql = "insert into `komentari`(`ID`,`korisnik_ID`,`recept_ID`, `komentar`) values (null, $id, $rec_id, '$kom')";
	$result = mysqli_query($conn, $sql);
	$retVal = array("status"=>false);
	if ($result) {
		$retVal["status"]=true;
	}
	
	disconnect($conn);
	
	echo json_encode($retVal);
}
else if (isset($_POST["dodaj_lajk"])){
	
	$status = $_POST["dodaj_lajk"];
	
	$id = $_SESSION["id"];
	$rec = $_SESSION["recept_id"];
	
	$conn = connect();
	$ins = true;
	$result = false;
	if (strcmp($status, "True") == 0) {
		$sql = "insert into `lajkovi`(`ID`,`korisnik_ID`,`recept_ID`) values (null, $id, $rec)";
		$result = mysqli_query($conn, $sql);
	}
	else {
		
		$sql = "delete from lajkovi where korisnik_ID = $id and recept_ID = $rec";
		$result = mysqli_query($conn, $sql);
		$ins = false;
	}
	
	mysqli_commit($conn);
	
	$retVal = array("status" => false, "val"=>0, "op" => $ins);
	if ($result) {
		
		$sql = "select count(*) as broj from lajkovi where recept_ID = $rec";
		$result1 = mysqli_query($conn, $sql);
		
		if ($result1) {
			
			if (mysqli_num_rows($result1) > 0) {
				
				$row = mysqli_fetch_assoc($result1);
				
				$retVal["status"] = true;
				$retVal["val"] = $row["broj"];
			}
			else {
				$retVal["status"] = true;
				$retVal["val"] = 0;
			}
		}
	}
	
	disconnect($conn);
	
	echo json_encode($retVal);
}
?>