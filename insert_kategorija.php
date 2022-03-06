<?php
include 'header.php';
?>
			<!-------------- start content ------------------------------->
				
				<div class="container container-fluid">
					<div class="row">
						<div class="col-sm-8">
							<h1>Ubacivanje nove kategorije</h1>
								<div class="form-group">
									<label for="kategorija">Naziv kategorije:</label>
									<input type="text" class="form-control" id="kategorija">
								</div>
								<div class="form-group">
									<label for="stranica">Stranica:</label>
									<input type="text" class="form-control" id="stranica">
								</div>
								<div class="form-group">
									<button class="btn btn-primary" style="margin:1px;" id="insert_kat" onclick="insert_kategorija()">Ubaci</button>
								</div>
							<div id="poruka">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-8">
							<div class="col-sm-12" id="tabela">
								<?php
									$conn = connect();
						
									$menu = getKategorijaTable($conn);
											
									disconnect($conn);
									
									echo $menu;
								?>
							</div>
						</div>
					</div>
				</div>
				
				<script>
					function insert_kategorija(){
						
						var text = $("#kategorija").val();
						var str = $("#stranica").val();
						if (text === "" || str === "") {
							
							$("#poruka").html("<div class=\"alert alert-danger alert-dismissible\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\<strong>Greška!</strong> Morate da navedete naziv kategorije i stranicu.</div>");
						}
						else {

							$.ajax({
								
								type:'POST',
								url: 'obrada.php',
								data: {insert_kat: 'True', kat: text, str: str},
								success: function(data) {
									var obj = $.parseJSON(data.trim());
									$("#poruka").html(obj.poruka);
									$("#tabela").html(obj.tabela);
								},
								error: function (data) {
									
									$("#poruka").html("<div class=\"alert alert-danger alert-dismissible\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\<strong>Greška!</strong> Problem u konekciji sa bazom.</div>");
								}
							});
						}
					}
				</script>
			<!-------------- end content ------------------------------->
<?php
include 'footer.php';
?>