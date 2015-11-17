<?php
	require "lib/db.php";
	require "lib/functions.php";
	
	$title = "Rezultati";
	$db = new DB();
	
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
				AND raspored.kolo IN (SELECT MAX(kolo) FROM raspored)
			ORDER BY datum DESC
		";
		$last = $db->query($sql);
		
		$sezona		= $last[0]['sezona'];
		$liga 		= $last[0]['liga'];
		$generacija = $last[0]['generacija'];
		$kolo 		= $last[0]['kolo'];
	}
	
	$rezultati = $db->query("
		SELECT au.ime domacin,au2.ime gost, raspored.datum, raspored.rezultat_domaci,raspored.rezultat_gosti
		FROM raspored
		LEFT JOIN timovi AS au
		ON raspored.tim_domaci_id = au.id
		LEFT JOIN timovi AS au2
		ON raspored.tim_gosti_id = au2.id
		JOIN generacije AS gen
		ON raspored.generacija_id = gen.id
		JOIN sezone AS sez
		ON raspored.sezona_id = sez.id
		WHERE au.liga_id IN (SELECT id FROM lige WHERE id = {$liga}) AND gen.id = {$generacija} AND sez.id = {$sezona} AND raspored.kolo = {$kolo} AND raspored.rezultat_domaci IS NOT NULL
		ORDER BY datum"
	);
	
	$tabela = $db->query("
SELECT
  domaci.ime ime, SUM(IF(rezultat_domaci>rezultat_gosti, 3, IF(rezultat_domaci=rezultat_gosti, 1, 0))) bodovi
FROM (((( raspored 
      JOIN  generacije   generacija 
        ON (( generacija.id  =  raspored.generacija_id )))
     JOIN  timovi   domaci 
       ON (( raspored.tim_domaci_id  =  domaci.id )))
    JOIN  timovi   gosti 
      ON (( raspored.tim_gosti_id  =  gosti.id )))
   JOIN  sezone   sezona 
     ON (( raspored.sezona_id  =  sezona.id )))
WHERE domaci.liga_id IN (SELECT id FROM lige WHERE id = {$liga}) AND generacija.id = {$generacija} AND sezona.id = {$sezona}
GROUP BY domaci.ime

UNION

SELECT
  gosti.ime ime, SUM(IF(rezultat_gosti>rezultat_domaci, 3, IF(rezultat_domaci=rezultat_gosti, 1, 0))) bodovi
FROM (((( raspored 
      JOIN  generacije   generacija 
        ON (( generacija.id  =  raspored.generacija_id )))
     JOIN  timovi   domaci 
       ON (( raspored.tim_domaci_id  =  domaci.id )))
    JOIN  timovi   gosti 
      ON (( raspored.tim_gosti_id  =  gosti.id )))
   JOIN sezone sezona
     ON ((raspored.sezona_id = sezona.id)))
WHERE domaci.liga_id IN (SELECT id FROM lige WHERE id = {$liga}) AND generacija.id = {$generacija} AND sezona.id = {$sezona}
GROUP BY gosti.ime
ORDER BY bodovi DESC;");

	$lige 		= $db->query("SELECT id,ime FROM lige");
	$generacije = $db->query("SELECT id, ime FROM generacije");
	$sezone		= $db->query("SELECT id, ime FROM sezone");
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
				<h1>Rezultati 1. kola:</h1>
			
				<form method="post" class="filter">
					<label>Sezona:
					<select name="sezona">
						<? foreach ($sezone as $s): ?>
						<option value="<?= $s['id'] ?>"><?= $s['ime'] ?></option>
						<? endforeach ?>
					</select></label>
					<label>Liga:
					<select name="liga">
						<? foreach ($lige as $l): ?>
						<option value="<?= $l['id'] ?>"><?= $l['ime'] ?></option>
						<? endforeach ?>
					</select></label>
					<label>Generacija:
					<select name="generacija">
						<? foreach ($generacije as $g): ?>
						<option value="<?= $g['id'] ?>"><?= $g['ime'] ?></option>
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
				
				<?if(!empty($rezultati)):?>
					<h2>Rezultati za <?=$kolo?>. kolo.</h2>
					<table>
						<tr>
							<th style="text-align:right">Domaći</th>
							<th colspan="3">Rezultat</th>
							<th style="text-align:left">Gosti</th>
							<th>Datum</th>
						</tr>
						<? foreach ($rezultati as $r): ?>
						<tr>
							<td align="right"><?= $r['domacin']  ?></td>
							<td align="right"><?= $r['rezultat_domaci']?></td>
							<td align="center">:</td>
							<td align="left"><?= $r['rezultat_gosti']?></td>
							<td align="left"><?= $r['gost']  ?></td>
							<td align="center"><?= format_date($r['datum']) ?></td>
						</tr>
						<? endforeach ?>
					<table>
				<?else:?>
					<h2>Nema rezultata.</h2>
				<?endif?>
				
				<?if(!empty($tabela)):?>
					<h2>Tabela <?=$generacije[0]['ime']?>. godište</h2>
					<table>
						<tr>
							<th width="1%"></th>
							<th style="text-align:left">Ime</th>
							<th width="2%">Bodovi</th>
						</tr>
						<?$counter=1;?>
						<? foreach ($tabela as $tab): ?>
						<tr>
							<td><?=$counter?>
							<td><?= $tab['ime']  ?></td>
							<td align="center"><?= $tab['bodovi']  ?></td>
							<?$counter++;?>
						</tr>
						<? endforeach ?>
					<table>
				<?else:?>
					<h2>Nema rezultata iz tabele.</h2>
				<?endif?>
				
				<!--<h4>2005. godište:<span style="margin-left:180px;">Tabela</span></h4>
				<table border="1" cellspacing="5" style="float:left;">
					<tr>
						<td>Domacin</td>
						<td>Gost</td>
						<td>Rezultat</td>
					</tr>
					<tr>
						<td>Sale Radivojević</td>
						<td>OFK Zmajevi</td>
						<td>1:3</td>
					</tr>
					<tr>
						<td>Poletarac Dorćol</td>
						<td>KMF Lion</td>
						<td>2:4</td>
					</tr>
					<tr>
						<td>Akademija Ilijev</td>
						<td>Karaoke</td>
						<td>5:2</td>
					</tr>
					<tr>
						<td>Lane</td>
						<td>Fortuna Prima</td>
						<td>5:3</td>
					</tr>
				</table>
				
				<table border="1" cellspacing="5" style="float:left;margin-left:15px;" >
					<tr>
						<td>
						<td>Tim</td>
						<td>O</td>
						<td>P</td>
						<td>N
						<td>I
						<td>B
						<td>D
						<td>P
						<td>GR
					</tr>
					<tr>
						<td>1.</td>
						<td>Akademija Ilijev</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>5
						<td>2
						<td>+3
					</tr>
					<tr>
						<td>2.</td>
						<td>Lane</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>5
						<td>3
						<td>+2
					</tr>
					<tr>
						<td>3.</td>
						<td>KMF Lion</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>4
						<td>2
						<td>+2
					</tr>
					<tr>
						<td>4.</td>
						<td>OFK Zmajevi</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>3
						<td>1
						<td>+2
					</tr>
					<tr>
						<td>5.</td>
						<td>Fortuna Prima</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>3
						<td>5
						<td>-2
					</tr>
					<tr>
						<td>6.</td>
						<td>Poletarac Dorćol</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>2
						<td>4
						<td>-2
					</tr>
					<tr>
						<td>7.</td>
						<td>Sale Radivojević</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>1
						<td>3
						<td>-2
					</tr>
					<tr>
						<td>8.</td>
						<td>Karioke</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>2
						<td>5
						<td>-3
					</tr>
					<tr>
						<td>9.</td>
						<td>Vojvodina Stenli</td>
						<td>0</td>
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
					</tr>
				</table>
				<div class="float_clear"></div></br>
				
				<h4>2006. godište:<span style="margin-left:180px;">Tabela</span></h4>
				<table border="1" cellspacing="5"style="float:left;" >
					<tr>
						<td>Domacin</td>
						<td>Gost</td>
						<td>Rezultat</td>
					</tr>
					<tr>
						<td>Dribling</td>
						<td>Zvezdica</td>
						<td>6:8</td>
					</tr>
					<tr>
						<td>OFK Zmajevi</td>
						<td>KMF Lion</td>
						<td>8:2</td>
					</tr>
					<tr>
						<td>Akademija Ilijev</td>
						<td>Fortuna Prima</td>
						<td>6:3</td>
					</tr>
					<tr>
						<td>Poletarac Dorćol</td>
						<td>OFK Lavovi</td>
						<td>(odl)</td>
					</tr>
				</table>
				
				<table border="1" cellspacing="5" style="float:left;margin-left:15px;" style="float:left;">
					<tr>
						<td>
						<td>Tim</td>
						<td>O</td>
						<td>P</td>
						<td>N
						<td>I
						<td>B
						<td>D
						<td>P
						<td>GR
					</tr>
					<tr>
						<td>1.</td>
						<td>OFK Zmajevi</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>8
						<td>2
						<td>+6
					</tr>
					<tr>
						<td>2.</td>
						<td>Akademija Ilijev</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>6
						<td>3
						<td>+3
					</tr>
					<tr>
						<td>3.</td>
						<td>Zvezdica</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>8
						<td>6
						<td>+2
					</tr>
					<tr>
						<td>4.</td>
						<td>Dribling</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>6
						<td>8
						<td>-2
					</tr>
					<tr>
						<td>5.</td>
						<td>Fortuna Prima</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>3
						<td>6
						<td>-3
					</tr>
					<tr>
						<td>6.</td>
						<td>KFM Lion</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>2
						<td>8
						<td>-6
					</tr>
					<tr>
						<td>7.</td>
						<td>Poletarac Dorćol</td>
						<td>0</td>
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
					</tr>
					<tr>
						<td>8.</td>
						<td>OFK Lavovi</td>
						<td>0</td>
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
					</tr>
					<tr>
						<td>9.</td>
						<td>Lane</td>
						<td>0</td>
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
					</tr>
				</table>
				<div class="float_clear"></div></br>
				
				<h4>2007. godište:<span style="margin-left:195px;">Tabela</span></h4>
				<table border="1" cellspacing="5"  style="float:left;">
					<tr>
						<td>Domacin</td>
						<td>Gost</td>
						<td>Rezultat</td>
					</tr>
					<tr>
						<td>Akademija Ilijev</td>
						<td>Fortuna Prima 2</td>
						<td>14:1</td>
					</tr>
					<tr>
						<td>Fortuna Prima 1</td>
						<td>OFK Zmajevi</td>
						<td>2:4</td>
					</tr>
					<tr>
						<td>Lanosa</td>
						<td>Čukarički</td>
						<td>2:3</td>
					</tr>
					<tr>
						<td>Poletarac Dorćol</td>
						<td>KMF Lion</td>
						<td>0:4</td>
					</tr>
				</table>
				
				<table border="1" cellspacing="5" style="float:left;margin-left:15px;" style="float:left;">
					<tr>
						<td>
						<td>Tim</td>
						<td>O</td>
						<td>P</td>
						<td>N
						<td>I
						<td>B
						<td>D
						<td>P
						<td>GR
					</tr>
					<tr>
						<td>1.</td>
						<td>Akademija Iliev</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>14
						<td>1
						<td>+13
					</tr>
					<tr>
						<td>2.</td>
						<td>KFM Lion</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>4
						<td>0
						<td>+4
					</tr>
					<tr>
						<td>3.</td>
						<td>OFK Zmajevi</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>4
						<td>2
						<td>+2
					</tr>
					<tr>
						<td>4.</td>
						<td>FK Čukarički</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>3
						<td>2
						<td>+1
					</tr>
					<tr>
						<td>5.</td>
						<td>Lanosa</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>2
						<td>3
						<td>-1
					</tr>
					<tr>
						<td>6.</td>
						<td>Fortuma Prima 1</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>2
						<td>4
						<td>-2
					</tr>
					<tr>
						<td>7.</td>
						<td>Poletarac Dorćol</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>0
						<td>4
						<td>-4
					</tr>
					<tr>
						<td>8.</td>
						<td>Fortuna Prima 2</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>1
						<td>14
						<td>-13
					</tr>
					<tr>
						<td>9.</td>
						<td>Sale Radivojević</td>
						<td>0</td>
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
						<td>0
					</tr>
				</table>
				<div class="float_clear"></div></br>
				
				<h4>2008. godište:<span style="margin-left:195px;">Tabela</span></h4>
				<table border="1" cellspacing="5" cellpadding="10" style="float:left;" >
					<tr>
						<td>Domacin</td>
						<td>Gost</td>
						<td>Rezultat</td>
					</tr>
					<tr>
						<td>Akademija Ilijev </td>
						<td>OFK Zmajevi</td>
						<td>2:1</td>
					</tr>
					<tr>
						<td>Sale Radivojević</td>
						<td>Fortuna Prima 1</td>
						<td>2:7</td>
					</tr>
					<tr>
						<td>Fortuna Prima 2</td>
						<td>Lane</td>
						<td>1:17</td>
					</tr>
					<tr>
						<td>Lanosa</td>
						<td>Voja Gačić</td>
						<td>1:1</td>
					</tr>
				</table>
				
				<table border="1" cellspacing="5" style="float:left;margin-left:15px;" style="float:left;">
					<tr>
						<td>
						<td>Tim</td>
						<td>O</td>
						<td>P</td>
						<td>N
						<td>I
						<td>B
						<td>D
						<td>P
						<td>GR
					</tr>
					<tr>
						<td>1.</td>
						<td>Lane</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>17
						<td>1
						<td>+16
					</tr>
					<tr>
						<td>2.</td>
						<td>Fortuna Prima</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>7
						<td>1
						<td>+6
					</tr>
					<tr>
						<td>3.</td>
						<td>Akademija Iliev</td>
						<td>1</td>
						<td>1
						<td>0
						<td>0
						<td>3
						<td>2
						<td>1
						<td>+1
					</tr>
					<tr>
						<td>4.</td>
						<td>Voja Gačić</td>
						<td>1</td>
						<td>0
						<td>1
						<td>0
						<td>1
						<td>1
						<td>1
						<td>0
					</tr>
					<tr>
						<td>5.</td>
						<td>Lanosa</td>
						<td>1</td>
						<td>0
						<td>1
						<td>0
						<td>1
						<td>1
						<td>1
						<td>0
					</tr>
					<tr>
						<td>6.</td>
						<td>OFK Zmajevi</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>1
						<td>2
						<td>-1
					</tr>
					<tr>
						<td>7.</td>
						<td>Sale Radivojević</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>2
						<td>7
						<td>-5
					</tr>
					<tr>
						<td>8.</td>
						<td>Fortuna Prima 2</td>
						<td>1</td>
						<td>0
						<td>0
						<td>1
						<td>0
						<td>1
						<td>17
						<td>-16
					</tr>
				</table>
				-->
			</div>
							
			<?php include "template/footer.php" ?>
		</div>
	</body>
</html>