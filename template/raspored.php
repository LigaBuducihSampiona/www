<?

	require "lib/db.php";
	require "lib/functions.php";
	
	$title = "Raspored";
	$db = new DB();
	
	$max_kolo_query = $db->query("SELECT MAX(kolo) max_kolo FROM raspored");
	$max_kolo = $max_kolo_query[0]['max_kolo'];
	
	$max_sezona_query = $db->query("SELECT MAX(id) max_sezona FROM sezone");
	
	$raspored_title = "";
	// user submitted filter
	if (isset($_POST['submit'])) {
	
		$sezona = $max_sezona_query[0]['max_sezona'];
		//$sezona		= $_POST['sezona'];
		$liga 		= $_POST['liga'];
		$generacija = $_POST['generacija'];
		$kolo 		= $_POST['kolo'];
		
		$raspored = $db->query("
		SELECT au.ime domacin,au2.ime gost, raspored.datum, gen.ime generacija_ime
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
		$raspored_title = "Raspored {$kolo}. kola za {$raspored[0]['generacija_ime']}. godište.";
	} else 
	{
		// no filter
		
		//$sql = "
		//	SELECT sezona_id sezona, lige.id liga, generacija_id generacija, kolo
		//	FROM raspored, timovi, lige
		//	WHERE raspored.tim_domaci_id = timovi.id
		//		AND timovi.liga_id = lige.id
		//		AND raspored.kolo = {$max_kolo}
		//	ORDER BY datum DESC
		//";
		
		
	//	$last = $db->query($sql);
		
		//$sezona		= $last[0]['sezona'];
		//$liga 		= $last[0]['liga'];
		//$generacija = $last[0]['generacija'];
		//$kolo 		= $last[0]['kolo'];
		$sezona = $max_sezona_query[0]['max_sezona'];
		$liga 		= '1';
		$generacija = '1';
		$kolo 		= $max_kolo;
		
		$raspored = $db->query("
		SELECT au.ime domacin,au2.ime gost, raspored.datum, gen.ime generacija_ime
		FROM raspored
		LEFT JOIN timovi AS au
		ON raspored.tim_domaci_id = au.id
		LEFT JOIN timovi AS au2
		ON raspored.tim_gosti_id = au2.id
		JOIN generacije AS gen
		ON raspored.generacija_id = gen.id
		JOIN sezone AS sez
		ON raspored.sezona_id = sez.id
		WHERE au.liga_id IN (SELECT id FROM lige WHERE id = {$liga}) AND sez.id = {$sezona} AND raspored.kolo = {$kolo}
		ORDER BY datum, generacija_ime"
		);
		$raspored_title = "Raspored {$kolo}. kola.";
	}
	
	$lige 		= $db->query("SELECT id,ime FROM lige");
	$generacije = $db->query("SELECT id, ime FROM generacije");
	$sezone		= $db->query("SELECT id, ime FROM sezone");
	
?>

<form method="post" class="filter">
	<table style="color:white;background:#427E26;">
	<!--<label>Sezona:
	<select name="sezona">
	<? foreach ($sezone as $s): ?>
	<option value="<?= $s['id'] ?>" <? if($s['id']==$sezona): ?> selected="selected"<? endif ?>><?= $s['ime'] ?></option>
	<? endforeach ?>
	</select></label>-->
	<tr>
		<td>Liga:</td>
		<td>
		<select name="liga">
			<? foreach ($lige as $l): ?>
			<option value="<?= $l['id'] ?>"<? if($l['id']==$liga): ?> selected="selected"<? endif ?>><?= $l['ime'] ?></option>
			<? endforeach ?>
		</select></label>
		</td>
	</tr>
	<tr>
		<td>Generacija:</td>
		<td>
			<select name="generacija">
				<? foreach ($generacije as $g): ?>
				<option value="<?= $g['id'] ?>"<? if($g['id']==$generacija): ?> selected="selected"<? endif ?>><?= $g['ime'] ?></option>
				<? endforeach ?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Kolo:</td>
		<td>
			<select name="kolo">
				<? for($i=1; $i<=18; $i++): ?>
				<option value="<?= $i ?>"<? if($i==$kolo): ?> selected="selected"<? endif ?>><?= $i ?></option>
				<? endfor ?>
			</select>
		</td>
	<tr>
	<tr><td colspan="2" align="right"><input type="submit" name="submit" value="Prikaži" /></td></tr>
	
	</table>
</form>

<?if(!empty($raspored)):?>
	
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