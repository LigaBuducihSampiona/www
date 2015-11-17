<?php

	$title = "Home";

?>
<!DOCTYPE html>
<html>
	<head>
		<?php include "template/head.php" ?>
		<script src="plugins/imgslider/themes/1/js-image-slider.js" type="text/javascript"></script>
	</head>
	<body>
		<div class="container">
			<?php include "template/header.php" ?>
			<?php include "template/navigation.php" ?>
			
			<div id = "content">
				<section>					
					<!--  ***************slider*************-->
					<h3>Najnovije vesti</h3>
					<!--<div id="sliderFrame">
						<div id="slider">
							<a href="http://www.menucool.com/javascript-image-slider" target="_blank">
								<img src="plugins/imgslider/images/image-slider-1.jpg" alt="" />
							</a>
							<img src="plugins/imgslider/images/image-slider-2.jpg" alt="" />
							<img src="plugins/imgslider/images/image-slider-3.jpg" alt="" />
							<img src="plugins/imgslider/images/image-slider-4.jpg" alt="" />
							<img src="plugins/imgslider/images/image-slider-5.jpg" />
						</div>
						<div id="htmlcaption" style="display: none;">
							<em>HTML</em> caption. Link to <a href="http://www.google.com/">Google</a>.
						</div>
						<br/><br/>
					</div>-->
					<p>
						Pregled prvog kola
					</p>
					 <iframe style="padding-left:15px;" class="youtube-video" src="https://www.youtube.com/embed/tCjcz5KJnpI" frameborder="0" allowfullscreen></iframe>
					 
					 <p>
						<br>
						Odigrano je 1. kolo lige budućih šampiona.<br/>
						Na stranici <a href="rezultati.php">rezultata</a> možete pogledati tabele i rezultate.
					</p>
					 <iframe style="padding-left:15px;" class="youtube-video" src="https://www.youtube.com/embed/MoDWueAq7dU" frameborder="0" allowfullscreen></iframe>
					 
					 
					
				</section>
				<!--<aside><? include "template/tabela.php" ?></aside>-->
			</div>
			
			<?php include "template/footer.php" ?>
		</div>
	</body>
</html>