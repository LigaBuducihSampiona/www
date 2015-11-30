<?php

	$title = "Kontakt";
	
	$data = @$_POST['data'];
	$alert = "";
	if (!empty($data)) {
		if (empty($data['name'])) {
			$alert = "Morate uneti ime.";
		} else if(empty($data['email'])) {
			$alert = "Morate email.";
		} else if(empty($data['message'])) {		
			$alert = "Morate uneti tekst poruke.";
		} else {
			$to = "office@ligabuducihsampiona.rs";
			$subject = "Kontakt sa sajta od: ";
			$subject .= $data['email'];
			$message = $data['message'];
			$header = "Content-type:text/html;charset=UTF-8" . "\r\n";
			$header .= "From: no-reply@" . $_SERVER['SERVER_NAME'];
			
			if (mail($to, $subject, $message, $header)) {
				$alert = "Poslato.";
			} else {
				$alert = "Nije poslato.";
			}
		}
	}

?><!DOCTYPE html>
<html>
	<head>
		<?php include "template/head.php" ?>	
		
		<script src="https://maps.googleapis.com/maps/api/js"></script>
		<script>
		  function initialize() {
			var mapCanvas = document.getElementById('map');
			var mapOptions = {
			  center: new google.maps.LatLng(44.5403, -78.5463),
			  zoom: 8,
			  mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			var map = new google.maps.Map(mapCanvas, mapOptions)
		  }
		  google.maps.event.addDomListener(window, 'load', initialize);
		  
		  function formReset(){document.getElementById("form").reset();}
		</script>		
	</head>
	<body>
		<div class="container">
			<?php include "template/header.php" ?>
			<?php include "template/navigation.php" ?>
			
			<div id = "content">
				<section class="p50">
					<h3>Kontakt</h3>
					<p>U svakom trenutku nas možete kontaktirati.</p>
					<form id="form" method="post">
						<fieldset>
							<label><input type="text" placeholder="Ime" name="data[name]"></label>
							<label><input type="text" placeholder="Email"name="data[email]"></label>
							<!--<label><input type="text" value="Telefon" onBlur="if(this.value=='') this.value='Telefon'" onFocus="if(this.value =='Telefon' ) this.value=''"></label>-->
							<label><textarea placeholder="Poruka" name="data[message]"></textarea></label>
					
							<div class="btns">
								<a href="#" class="button" onClick="document.getElementById('form').submit()">Pošalji</a>
								<a href="#" onclick="formReset()" class="button">Obriši</a>
							</div>
							<div class="alert"><?php echo $alert ?></div>
						</fieldset>  
				</form>
				</section>
				<div class="map">
					<h3>Pronađite nas</h3>
					<!--<div id="map"></div>-->
					<table cellspacing="10">
						<tr>
							<td>Sedište:
							<td>Trebinjska, Beograd
						</tr>
						<tr>
							<td colspan="2">Kontakt telefoni:
						</tr>
						<tr>
							<td>Za područje Vojvodine
							<td>+38163 1071 333
						</tr>
						<tr>
							<td>Za područje Srbije
							<td>+38164 111 4954
						</tr>
						<tr>
							<td>Za područje Beograda
							<td>+38165 355 8930
						</tr>
						<!--<tr>
							<td colspan="2">E-mail: <a href="mailto:office@ligabuducihsampiona.rs">office@ligabuducihsampiona.rs</a>
						</tr>-->
					</table>
					
					<!--<p>Ulica: Trebinjska, Beograd<br>
					Telefoni:<br>&emsp;Za područje Vojvodine: +38163 1071 333
							<br>&emsp;Za područje Srbije: +38164 111 4954
							<br>&emsp;Za Beograd: +38165 355 8930<br>
				   E-mail: <a href="mailto:office@ligabuducihsampiona.rs">office@ligabuducihsampiona.rs</a>
					</p>-->
					<iframe style="margin-top:10px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2831.511932085126!2d20.484505015777536!3d44.79075468614566!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x475a707ca41c98d3%3A0xdf4ab715c7910b8a!2sSOS+kanal!5e0!3m2!1sen!2srs!4v1444503071631" width="400" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
				</div>
			</div>
			
			<?php include "template/footer.php" ?>
		</div>
	</body>
</html>