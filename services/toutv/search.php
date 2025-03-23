<?php

declare(strict_types=1);

$query = $_GET["q"];

$shows = array();

$ch = curl_init(
    "https://services.radio-canada.ca/ott/catalog/v1/toutv/search?device=web&pageNumber=1&pageSize=999999999&term=$query"
);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => false,
]);

$str_response = curl_exec($ch);

$resp = json_decode($str_response, true);

curl_close($ch);

foreach ($resp["result"] as $_ => $s) {

    # Si le contenu est un show (emission sans vrai sens aux episodes)
    # ou Season (emission avec un ordre, genre grey's anatomy)
    if (!in_array($s["type"], ["Show", "Season"])) {
        continue;
    }

    $show = array();

    $show["id"] = $s["url"];
    $show["title"] = $s["title"];
    $show["image"] = $s["image"]["url"];
    $show["type"] = $s["type"];

    array_push($shows, $show);
}

echo json_encode($shows);
