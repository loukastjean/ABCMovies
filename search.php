<?php
declare(strict_types=1);
require_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/toutv/search.php';
require_once __DIR__."/classes/session.include.php";

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/ABCMovies/common/nav.html");

ResumeSession("logged");

$query = $_GET["query"];

$toutv_shows = get_search_results($query)
?>



<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ABCMovies</title>
    <link rel="stylesheet" href="./css/style.css"/>
  </head>
  <body>
    <?php
    echo $header;
    ?>
    <main>
    <?php
    echo "<br/>";
    
    foreach ($toutv_shows as $i => $show) {
        echo "<p>Title: ".$show->title."</p>";
        echo "<p>URL: ".$show->id."</p>";
        echo "<p>Type: ".$show->type."</p>";
    }
    ?>
    </main>
  </body>
</html>