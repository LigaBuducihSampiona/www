<?php
	require "lib/db.php";
	require "lib/functions.php";
	
	$title = "Raspored";
	$db = new DB();
	
	// user submitted filter
	if (isset($_POST['submit'])) {
		$sezona		= $_POST['sezona'];
		$liga 		= $_POST['liga'];
		$generacija = $_POST['generacija'];
		$kolo 		= $_POST['kolo'];
	// no filter
	} else {
		$sql = "
			SELECT sezona_id sezona, lige.id liga, generacija_id generacija, kolo
			FROM raspored, timovi, lige
			WHERE raspored.tim_domaci_id=timovi.id
				AND timovi.liga_id=lige.id
				AND rezultat_domaci IS NOT NULL
			ORDER BY datum DESC
		";
		$last = $db->query($sql);
		$sezona		= $last[0]['sezona'];
		$liga 		= $last[0]['liga'];
		$generacija = $last[0]['generacija'];
		$kolo 		= $last[0]['kolo'];
	}
	
	$lige 		= $db->query("SELECT id,ime FROM lige");
	$generacije = $db->query("SELECT id, ime FROM generacije");
	$sezone		= $db->query("SELECT id, ime FROM sezone");
	
	//nije spojen FK raspored.tim_gosti_id
	
	$raspored = $db->query("
		SELECT au.ime domacin,au2.ime gost, raspored.datum
		FROM raspored
		LEFT JOIN timovi AS au
		ON raspored.tim_domaci_id = au.id
		LEFT JOIN timovi AS au2
		ON raspored.tim_gosti_id = au2.id
		JOIN generacije AS gen
		ON raspored.generacija_id = gen.id
		JOIN sezone AS sez
		ON raspored.sezona_id = sez.id
		WHERE au.liga_id IN (SELECT id FROM lige WHERE id = {$liga}) AND gen.id = {$generacija} AND sez.id = {$sezona} AND raspored.kolo = {$kolo}
		ORDER BY datum"
	);

	
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include "template/head.php" ?>
	</head>
	<body>
		<div class="container">		
			<?php include "template/header.php" ?>
			
			<?php include "template/navigation.php" ?>
			<div id = "content">
				<h2>Odaberite raspored:</h2><br>
				<form method="post" class="filter">
					<label>Sezona:
					<select name="sezona">
						<? foreach ($sezone as $s): ?>
						<option value="<?= $s['id'] ?>" <? if($s['id']==$sezona): ?> selected="selected"<? endif ?>><?= $s['ime'] ?></option>
						<? endforeach ?>
					</select></label>
					<label>Liga:
					<select name="liga">
						<? foreach ($lige as $l): ?>
						<option value="<?= $l['id'] ?>"<? if($l['id']==$liga): ?> selected="selected"<? endif ?>><?= $l['ime'] ?></option>
						<? endforeach ?>
					</select></label>
					<label>Generacija:
					<select name="generacija">
						<? foreach ($generacije as $g): ?>
						<option value="<?= $g['id'] ?>"<? if($g['id']==$generacija): ?> selected="selected"<? endif ?>><?= $g['ime'] ?></option>
						<? endforeach ?>
					</select></label>
					<label>Kolo:
					<select name="kolo">
						<? for($i=1; $i<=18; $i++): ?>
						<option value="<?= $i ?>"<? if($i==$kolo): ?> selected="selected"<? endif ?>><?= $i ?></option>
						<? endfor ?>
					</select></label>
					<input type="submit" name="submit" value="Prikaži" />
				</form>
				
				<?//if( empty( $raspored ) )
				//	echo "asdf";
				?>
				<?//if($raspored[0]=="")
					//echo "asdf";
				?>
				
				<?if(!empty($raspored)):?>
				<?//if(isset($raspored)):?>
					<h2>Raspored za <?=$kolo?>. kolo.</h2>
					<table>
						<tr>
							<th style="text-align:right">Domacin</th>
							<th></th>
							<th style="text-align:left">Gost</th>
							<th width="20%">Datum</th>
						</tr>
						<? foreach ($raspored as $r): ?>
						<tr>
							<td align="right"><?= $r['domacin']  ?></td>
							<td></td>
							<td><?= $r['gost']  ?></td>
							<td align="center"><?= format_date($r['datum'])  ?></td>
						</tr>
						<? endforeach ?>
					<table>
				<?else:?>
					<h2>Nema rezultata.</h2>
				<?endif?>

				
				<!--<h2>Raspored subota 14.11.2015.</h2>
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
				
				-->
				
			</div>
							
			<?php include "template/footer.php" ?>
		</div>
	</body>
</html>
