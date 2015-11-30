<?php
$timovi = $db->query("SELECT id,ime FROM timovi");
//var_dump($timovi);
//$timovi = array();

$table = array();

foreach ($timovi as $tim) {
	$table[$tim['id']] = array(

        'ime'        => $tim['ime'],

        'odigrano'    => 0,

        'pobeda'      => 0,

        'nereseno'    => 0,

        'izgubljeno'  => 0,

        'golova_dato' => 0,

        'golova_primljeno' => 0,

        'bodova'      => 0
      );

}


//au.ime domacin,au2.ime gost, raspored.datum, raspored.rezultat_domaci,raspored.rezultat_gosti
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
		WHERE au.liga_id IN (SELECT id FROM lige WHERE id = {$liga}) AND gen.id = {$generacija} AND sez.id = {$sezona} AND raspored.rezultat_domaci IS NOT NULL
		");
	
	
    foreach ($rezultati as $rezultat) {

      // odigrano

      $table[$rezultat['tim_domaci_id']]['odigrano'] += 1;

      $table[$rezultat['tim_gosti_id']]['odigrano'] += 1;

      // pobeda

	  if ($rezultat['rezultat_domaci'] > $rezultat['rezultat_gosti'])
	  {
		$table[$rezultat['tim_domaci_id']]['pobeda'] += 1;
		$table[$rezultat['tim_gosti_id']]['izgubljeno'] += 1;
	  }
	  
	  if ($rezultat['rezultat_gosti'] > $rezultat['rezultat_domaci'])
	  {
		$table[$rezultat['tim_gosti_id']]['pobeda'] += 1;
		$table[$rezultat['tim_domaci_id']]['izgubljeno'] += 1;
	  }
	  
	  
	// nereseno
	if ($rezultat['rezultat_domaci'] == $rezultat['rezultat_gosti'])
	{
		$table[$rezultat['tim_domaci_id']]['nereseno'] += 1;
		$table[$rezultat['tim_gosti_id']]['nereseno'] += 1;
	}
      // izgubljeno ima u pobeda

	// golova dato
	
		$table[$rezultat['tim_domaci_id']]['golova_dato'] 		+= $rezultat['rezultat_domaci'];
		$table[$rezultat['tim_gosti_id']]['golova_dato'] 		+= $rezultat['rezultat_gosti'];;
	
	//golova primljeno
	$table[$rezultat['tim_domaci_id']]['golova_primljeno'] 	+= $rezultat['rezultat_gosti'];
	$table[$rezultat['tim_gosti_id']]['golova_primljeno'] 	+= $rezultat['rezultat_domaci'];
		  
 
	// bodova
      if ($rezultat['rezultat_domaci'] > $rezultat['rezultat_gosti']){$table[$rezultat['tim_domaci_id']]['bodova'] += 3;}
	  
	  if ($rezultat['rezultat_gosti'] > $rezultat['rezultat_domaci']){$table[$rezultat['tim_gosti_id']]['bodova'] += 3;}
	  
	  if ($rezultat['rezultat_domaci'] == $rezultat['rezultat_gosti'])
	  {
		$table[$rezultat['tim_domaci_id']]['bodova'] += 1;
		$table[$rezultat['tim_gosti_id']]['bodova'] += 1;
      }
	}
?>