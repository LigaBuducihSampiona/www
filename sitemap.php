<?php
  header("Content-Type: application/xml");
  // header("Content-Disposition: inline; filename=ligabuducihsampiona.xml");

  $server = "http://".$_SERVER['SERVER_NAME'];
  $lastmod = date("Y-m-d\TH:i:sP", strtotime("-8days"));


  $pages = array(
    'index.php',
    'o_nama.php',
    'galerija.php',
    'rezultati.php',
    'raspored.php',
    'kontakt.php'
  );

?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
          xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
          xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
                              http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
  
  <? foreach($pages as $page): ?>
  <url>
    <loc><?= $server ?>/<?= $page ?></loc>
    <lastmod><?= $lastmod ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.5</priority>
  </url>
  <? endforeach ?>
</urlset>