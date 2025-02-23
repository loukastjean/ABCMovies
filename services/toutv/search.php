<?php

declare(strict_types=1);
include $_SERVER['DOCUMENT_ROOT'].'/common/show.php';

$available_shows = array();

$ch = curl_init("https://services.radio-canada.ca");

$query = $_GET["query"];

curl_setopt_array($ch, array(
CURLOPT_URL => "https://services.radio-canada.ca/ott/catalog/v1/toutv/search?device=web&pageNumber=1&pageSize=999999999&term=$query",
CURLOPT_RETURNTRANSFER => TRUE,
CURLOPT_HEADER => FALSE
));

$str_response = curl_exec($ch);

$resp = json_decode($str_response, TRUE);

curl_close($ch);

?>

<!DOCTYPE html>
input:button
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="./search.php" method="get">
        <input type="text" name="query" id="query">
        <input type="submit" value="Envoye criss">
    </form>
    <?php
    echo "<br/>";
    foreach ($resp["result"] as $i => $result) {
        $show = new Show();
        $show->id = $result["url"];
        $show->title = $result["title"];
        $show->type = $result["type"];
        
        # Si le contenu est un show (emission sans vrai sens aux episodes)
        # ou Season (emission avec un ordre, genre grey's anatomy)
        if (in_array($show->type, array("Show", "Season"))) {
            array_push($available_shows, $show);
        }
    }
    
    foreach ($available_shows as $i => $show) {
        echo "<p>Title: ".$show->title."</p>";
        echo "<p>URL: ".$show->id."</p>";
        echo "<p>Type: ".$show->type."</p>";
    }
    ?>
</body>
</html>