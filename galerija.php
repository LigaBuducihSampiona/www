<?php

define('ALBUM_DIR', "img/galerija/");

function get_albums() {
	$albums = array();
	$dirname = ALBUM_DIR;
	
	if ($dir=opendir($dirname)) {
		while ($f = readdir($dir)) {
			if (is_dir("$dirname/$f") && !preg_match('/^\.+$/', $f)) {
				$albums[] = $f;
			}
		}
	}
	
	return $albums;
}

function read_album($album) {
	$dirname = ALBUM_DIR . $album;
	$photos = array();
	if ($dir=opendir($dirname)) {
		while ($f = readdir($dir)) {
			if (preg_match('/\.jpe?g$|\.png$|\.gif$/i', $f)) {
				$photos[] = $f;
			}
		}
	}
	
	return $photos;
}

function getAlbumsFirstPhoto($album) {
	$dirname = ALBUM_DIR . $album;
	$photo = array(1);
	if ($dir=opendir($dirname)) {
		while ($f = readdir($dir)) {
			if (preg_match('/\.jpe?g$|\.png$|\.gif$/i', $f)) {
				$photo[] = $f;
				break;
			}
		}
	}
	return $photo;
}


$title = "Galerija";
$getAlbum = @$_GET['album'];

?>
<!DOCTYPE html>
<html>
	<head>
		<?php include "template/head.php" ?>
		
		<link rel="stylesheet" href="plugins/PhotoSwipe/photoswipe.css"> 
		<link rel="stylesheet" href="plugins/PhotoSwipe/default-skin/default-skin.css">
	<link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		
		<script type="text/javascript" src="js/slimbox2.js"></script>
	<!--	<script src="js/jquery-2.1.4.min.js"></script> -->
	</head>
	<body>
		<div class="container">		
			<?php include "template/header.php" ?>
			
			<?php include "template/navigation.php" ?>
			<div id = "content">
				<h1>Galerija</h1>
				<div id="gallery">
				<? if(empty($getAlbum)): ?>
					<ul>
						<? foreach (get_albums() as $album): ?>
						<?
							$first_photo_array = getAlbumsFirstPhoto($album);
							$first_photo = $first_photo_array[1];
						?>
						<li>
							<a href="?album=<?= urlencode($album) ?>" width="110" height="90">
								<img src="<?= ALBUM_DIR ?><?= $getAlbum ?><?=urlencode($album)?>/<?=urlencode($first_photo)?>" alt="<?=urlencode($album)?>" >
								<div class="desc"><?= $album?></div>
							</a>
						</li>
						<? endforeach ?>
					</ul>
				<? else: ?>
					<h2><?= $getAlbum ?></h2>
					<p><a href="galerija.php">&laquo; back</a></p>
					<ul class="gallery">
						<? foreach (read_album($getAlbum) as $photo): ?>
						<li><a href="<?= ALBUM_DIR ?><?= $getAlbum ?>/<?= $photo ?>" rel="lightbox"><img src="<?= ALBUM_DIR ?><?= $getAlbum ?>/thumbs/<?= $photo ?>"></a></li>
						<? endforeach ?>
					</ul>
				<? endif ?>
				</div>
			</div>
							
			<?php include "template/footer.php" ?>
		</div>
	</body>
</html>