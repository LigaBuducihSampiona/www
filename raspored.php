<?php
	include "lib/db.php";
	
	$title = "Raspored";
	//mozda je bolje iz cmda ?

	$db = new DB();
	$generacije = $db->query("SELECT id,ime from generacije");
	$lige 		= $db->query("SELECT id,ime from lige");
	
	
	
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include "template/head.php" ?>
	</head>
	<body>
		<div class="container">		
			<?php include "template/header.php" ?>
			
			<?php include "template/navigation.php"?>
			<div id = "content">
				<h2>Odaberite raspored:</h2><br>
				<form method="post" class="filter">
					Liga:&nbsp;
					<select name="liga">
						<? foreach ($lige as $l): ?>
						<option value="<?= $l['id'] ?>"><?= $l['ime'] ?></option>
						<? endforeach ?>
					</select>&nbsp;
					generacija:&nbsp;
					<select name="generacija">
						<? foreach ($generacije as $g): ?>
						<option value="<?= $g['id'] ?>"><?= $g['ime'] ?></option>
						<? endforeach ?>
					</select>
					<label for="kolo">kolo:</label>
					<select name="kolo" id="kolo">
						<!--<option value="0">*</option>-->
						<? for($i=1; $i<=18; $i++): ?>
						<option value="<?= $i ?>"><?= $i ?></option>
						<? endfor ?>
					</select>
					<input type="submit" name="submit" value="Prikaži" />
				</form>
				<?php
					if(isset($_POST['submit'])){
						$liga = $_POST['liga'];
						$generacija = $_POST['generacija'];
						$kolo = $_POST['kolo'];
						
					}
				?>
							
				<h2>Raspored subota 14.11.2015.</h2>
				<br>
				<h4>2007. godište:<span style="margin-left:225px;">2008. godište:</span></h4>
				<table border="1" cellspacing="5" style="float:left;" >
					<tr>
						<td>Domacin</td>
						<td>Gost</td>
						<td>Vreme</td>
					</tr>
					<tr>
						<td>KMF Lion</td>
						<td>TS Sale Radivojević</td>
						<td>09:00</td>
					</tr>
					<tr>
						<td>Lanosa</td>
						<td>Akademija Iliev</td>
						<td>12:00</td>
					</tr>
					<tr>
						<td>Čukarički </td>
						<td>Poletarac Dorćol</td>
						<td>14:00</td>
					</tr>
					<tr>
						<td>Prima Fortuna 2</td>
						<td>OFK Zmajevi</td>
						<td>16:00</td>
					</tr>
				</table>
				
				<table border="1" cellspacing="5" cellpadding="10" style="float:left;margin-left:40px;">
					<tr>
						<td>Domacin</td>
						<td>Gost</td>
						<td>Vreme</td>
					</tr>
					<tr>
						<td>Lanosa</td>
						<td>Lane</td>
						<td>10:00</td>
					</tr>
					<tr>
						<td>Prima Fortuna 2</td>
						<td>TS S.Radivojević</td>
						<td>11:00</td>
					</tr>
					<tr>
						<td>Fortuna Prima 1</td>
						<td>Akademija Iliev</td>
						<td>13:00</td>
					</tr>
					<tr>
						<td>Voja Gačić </td>
						<td>OFK Zmajevi</td>
						<td>15:00</td>
					</tr>
				</table>
				
				<div class="float_clear"><div>
				<br>
				<h2>Raspored nedelja 15.11.2015.</h2>
				<br>
				<h4>2005. godište:<span style="margin-left:210px;">2006. godište:</span></h4>
				<table border="1" cellspacing="5" style="float:left;" >
					<tr>
						<td>Domacin</td>
						<td>Gost</td>
						<td>Vreme</td>
					</tr>
					<tr>
						<td>Poletarac Dorćol </td>
						<td>Lane</td>
						<td>11:00</td>
					</tr>
					<tr>
						<td>Vojvodina Stenli</td>
						<td>Sale Radivojević</td>
						<td>12:00</td>
					</tr>
					<tr>
						<td>KFM Lion </td>
						<td>Akademija Iliev</td>
						<td>14:00</td>
					</tr>
					<tr>
						<td>Prima Fortuna</td>
						<td>Karioke</td>
						<td>15:00</td>
					</tr>
				</table>
				
				<table border="1" cellspacing="5" cellpadding="10" style="float:left;margin-left:40px;">
					<tr>
						<td>Domacin</td>
						<td>Gost</td>
						<td>Vreme</td>
					</tr>
					<tr>
						<td>Dribling</td>
						<td>Poletarac Dorćol</td>
						<td>9:00</td>
					</tr>
					<tr>
						<td>Lane</td>
						<td>OFK Lavovi</td>
						<td>10:00</td>
					</tr>
					<tr>
						<td>Zvezdica</td>
						<td>Akademija Iliev</td>
						<td>13:00</td>
					</tr>
					<tr>
						<td>Prima Fortuna </td>
						<td>OFK Zmajevi</td>
						<td>16:00</td>
					</tr>
				</table>
				
				
				
			</div>
							
			<?php include "template/footer.php" ?>
		</div>
	</body>
</html>