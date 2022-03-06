<?php
include 'header.php';
?>

			<!-------------- start content ------------------------------->
			<div class="container container-fluid">
				<div class="row">
					<div class="col-sm-8">
						<h1 class="border-bottom mb-2">Formular za registraciju</h1>
						<form enctype="multipart/form-data" id="reg_form" method="POST" action="obrada.php">
							<div class="row p-0">
								<div class="form-group col-sm-5 p-0">
									<label for="ime" class="col-sm-3">Ime:<span style="color:red">*</span></label>
									<input type="text" class="form-control col-sm-9 float-right" id="ime" name="ime">
								</div>
								<div class="form-group col-sm-7 p-0">
									<label for="prezime" class="col-sm-3">Prezime:<span style="color:red">*</span></label>
									<input type="text" class="form-control col-sm-9 float-right" id="prezime" name="prezime">
								</div>
							</div>
							<div class="row p-0">
								<div class="form-group col-sm-5 p-0">
									<label for="grad" class="col-sm-3">Grad:</label>
									<input type="text" class="form-control col-sm-9 float-right" id="grad" name="grad">
								</div>
								<div class="form-group col-sm-7 p-0">
									<label for="mejl" class="col-sm-3">Mejl:<span style="color:red">*</span></label>
									<input type="text" class="form-control col-sm-9 float-right" id="mejl" name="mejl">
								</div>
							</div>
							<div class="row form-group">
								<label for="usr" class="col-sm-3">Korisničko ime:<span style="color:red">*</span></label>
								<input type="text" class="form-control col-sm-9" id="usr" name="usr">
							</div>
							<div class="row form-group">
								<label for="pass" class="col-sm-3">Šifra:<span style="color:red">*</span></label>
								<input type="password" class="form-control col-sm-9" id="pass" name="pass">
							</div>
							<div class="row form-group">
								<label for="dr" class="col-sm-3">Datum rođenja:<span style="color:red">*</span></label>
								<input type="date" class="form-control col-sm-9" id="dr" name="dr">
							</div>
							<div class="row form-group">
								<label for="slika" class="col-sm-3">Fotografija:</label>
								<input type="file" accept="image/*" class="form-control-file col-sm-9" id="slika" name="slika">
							</div>
							<div class="row form-group">
								<label for="info" class="col-sm-3">Dodatne informacije:</label>
								<textarea type="input" class="form-control col-sm-9" rows="5" id="info" name="info"></textarea>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary float-right" name="registracija" id="reg_btn">Registruj se</button>
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
				</div>
			</div>	
			
			<script>
				$(document).ready(function(e){
					$("#reg_form").on('submit', function(e){
						e.preventDefault();
						
						var ime = $("#ime").val().trim();
						var prezime = $("#prezime").val().trim();
						var grad = $("#grad").val().trim();
						var mejl = $("#mejl").val().trim();
						var usr = $("#usr").val().trim();
						var pass = $("#pass").val().trim();
						var dr = $("#dr").val().trim();
						var info = $("#info").val().trim();
						var slika = $("#slika").val().trim();
						
						if (ime === "" || prezime === "" || mejl === "" || usr === "" || pass === "" || dr ==="") {
							$("#sadrzaj_modal").html("Nedostaju obavezna polja! Popunite ih i pokušajte ponovo!");
							$('#modalID').modal({
								show: true
							});
						}
						else {
						
							var formData = new FormData(this);
							formData.append("registracija","true");
							$.ajax({
								type:'POST',
								url:'obrada.php',
								data: formData,
								contentType: false,
								cache: false,
								processData: false,
								beforeSend: function(){
									$('#reg_btn').attr("disabled","disabled");
									$("#reg_form").css("opacity",".5");
								},
								success: function(data){
									
									var obj = $.parseJSON(data.trim());
									
									if (obj.status) {
										if (obj.exists) {
											$("#naslov_modal").html("Greška");
											$("#sadrzaj_modal").html("Korisničko ime ili mejl adresa su zauzeti! Pokušajte ponovo!");
											$('#modalID').modal({
												show: true
											});
										}
										else {
											if (obj.inserted) {
												$("#naslov_modal").html("Dobrodošli " + usr);
												$("#sadrzaj_modal").html("Registracija uspešna!");
												$('#modalID').modal({
													show: true
												});

												$("#modalID").on("hide.bs.modal", function(e) {
													window.location.href = 'index.php';
												});
											}
											else {
												$("#naslov_modal").html("Greška");
												$("#sadrzaj_modal").html("Unos u bazu podataka nije uspeo! Pokušajte ponovo!");
												$('#modalID').modal({
													show: true
												});
											}
										}
									}
									else {
										$("#naslov_modal").html("Greška");
										$("#sadrzaj_modal").html("Greška u komunikaciji sa bazom! Pokušajte ponovo!");
											$('#modalID').modal({
												show: true
											});
									}

									$("#reg_form").css("opacity","1");
									$('#reg_btn').removeAttr("disabled");
								},
								error: function (data){
									alert("Connection error");
								}
							});
						}
					});
					
					$("#slika").change(function() {
						var file = this.files[0];
						var imagefile = file.type;
						var match= ["image/jpeg","image/png","image/jpg"];
						if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]))){
							$("#naslov_modal").html("Greška");
							$("#sadrzaj_modal").html("Slika mora biti u .jpg ili .png formatu!");
								$('#modalID').modal({
									show: true
								});
							$("#slika").val('');
							return false;
						}
					});					
				});
			
			</script>
			<!-------------- end content ------------------------------->
<?php
include 'footer.php';
?>