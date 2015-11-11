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
					<div id="sliderFrame">
						<div id="slider">
							<a href="http://www.menucool.com/javascript-image-slider" target="_blank">
								<img src="plugins/imgslider/images/image-slider-1.jpg" alt="Welcome to Menucool.com" />
							</a>
							<img src="plugins/imgslider/images/image-slider-2.jpg" alt="Proizvoljan opis" />
							<img src="plugins/imgslider/images/image-slider-3.jpg" alt="Proizvoljan opis." />
							<img src="plugins/imgslider/images/image-slider-4.jpg" alt="#htmlcaption" />
							<img src="plugins/imgslider/images/image-slider-5.jpg" />
						</div>
						<div id="htmlcaption" style="display: none;">
							<em>HTML</em> caption. Link to <a href="http://www.google.com/">Google</a>.
						</div>
						<br/><br/>
					</div>
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam faucibus bibendum odio, quis convallis erat faucibus vitae. Morbi iaculis sagittis libero, eu ultrices urna finibus sed. Aenean tincidunt in lectus id faucibus. Nulla facilisi. Proin at dui ultricies, molestie quam a, viverra dui. Pellentesque blandit diam sed turpis imperdiet, a imperdiet ante ultricies. Donec at quam erat. Sed varius, ipsum vitae porttitor gravida, turpis lectus vehicula urna, eu commodo ante erat vel ligula. Mauris commodo pellentesque lorem, in tristique tellus ornare quis. Nam eu tempor ligula. Integer tortor orci, fermentum et euismod non, bibendum et enim. Aliquam lobortis efficitur sem eget egestas. 
					</p>
				</section>
				<aside><? include "template/tabela.php" ?></aside>
			</div>
			
			<?php include "template/footer.php" ?>
		</div>
	</body>
</html>