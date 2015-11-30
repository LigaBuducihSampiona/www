<?php
	require "lib/db.php";
	require "lib/functions.php";
	
	$title = "Rezultati";
	$db = new DB();
	
	$max_kolo_query = $db->query("SELECT MAX(kolo) max_kolo FROM raspored");
	$max_kolo = $max_kolo_query[0]['max_kolo'];
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
	team,
	team_name, 
	COUNT( * ) played, 
	COUNT( CASE WHEN goalsfor > goalsagainst THEN 1 END ) wins, 
	COUNT( CASE WHEN goalsagainst > goalsfor THEN 1 END ) lost, 
	COUNT( CASE WHEN goalsfor = goalsagainst THEN 1 END ) draws, 
	SUM( goalsfor ) goalsfor, 
	SUM( goalsagainst ) goalsagainst, 
	SUM( goalsfor ) - SUM( goalsagainst ) goal_diff, 
	SUM( 
	CASE WHEN goalsfor > goalsagainst
	THEN 3 
	ELSE 0 
	END + 
	CASE WHEN goalsfor = goalsagainst
	THEN 1 
	ELSE 0 
	END ) score
FROM 
(
	SELECT tim_domaci_id team,domaci.ime team_name, rezultat_domaci goalsfor, rezultat_gosti goalsagainst
	FROM raspored
    JOIN  timovi domaci 
       ON raspored.tim_domaci_id  =  domaci.id
	WHERE generacija_id LIKE  {$generacija}
    		AND sezona_id = {$sezona}
    		AND domaci.liga_id IN (SELECT id FROM lige WHERE id = {$liga}) AND rezultat_gosti IS NOT NULL
	
	UNION ALL 
	
	SELECT tim_gosti_id,gosti.ime team_name, rezultat_gosti goalsagainst, rezultat_domaci goalsfor
	FROM raspored
    JOIN  timovi gosti 
       ON raspored.tim_gosti_id  =  gosti.id
	WHERE generacija_id LIKE  {$generacija} AND sezona_id = {$sezona} AND gosti.liga_id IN (SELECT id FROM lige WHERE id = {$liga}) AND rezultat_gosti IS NOT NULL
)a
GROUP BY team
ORDER BY score DESC , goal_diff DESC");


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
				<h1>Rezultati:</h1>
			
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
						<? for($i=1; $i<=$max_kolo; $i++): ?>
						<option value="<?= $i ?>"<? if($i==$kolo): ?> selected="selected"<? endif ?>><?= $i ?></option>
						<? endfor ?>
					</select></label>
					<input type="submit" name="submit" value="Prikaži" />
				</form>

				
				<?if(!empty($rezultati)):?>
					<h2>Rezultati <?=$kolo?>. kola</h2>
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
					<h2>Tabela</h2>
					<table>
						<tr>
							<th width="1%"></th>
							<th style="text-align:left">Ime</th>
							<th>O</th>
							<th>P</th>
							<th>I</th>
							<th>N</th>
							<th>GD</th>
							<th>GP</th>
							<th>GR</th>
							<th align="center">Bodovi</th>
						</tr>
						<?$counter=1;?>
						<? foreach ($tabela as $tab): ?>
						<tr>
							<td><?=$counter?>
							<td><?= $tab['team_name']?></td>
							<td align="center"><?= $tab['played']?></td>
							<td align="center"><?= $tab['wins']?></td>
							<td align="center"><?= $tab['lost']?></td>
							<td align="center"><?= $tab['draws']?></td>
							<td align="center"><?= $tab['goalsfor']?></td>
							<td align="center"><?= $tab['goalsagainst']?></td>
							<td align="center"><?= $tab['goal_diff']?></td>
							<td align="center"><?= $tab['score']?></td>
							<?$counter++;?>
						</tr>
						<? endforeach ?>
					<table>
				<?else:?>
					<h2>Nema rezultata iz tabele.</h2>
				<?endif?>
			</div>
							
			<?php include "template/footer.php" ?>
		</div>
	</body>
</html>