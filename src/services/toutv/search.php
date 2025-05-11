<?php

declare(strict_types=1);

header('Content-type: application/json');

if (!isset($_GET["q"])) {
    echo json_encode(["error" => "Aucune requête trouvée (?q)"]);
    die();
}

$query;

try {
    // Encode le paramètre de requête pour pouvoir faire la requête a TOU.TV
    $query = urlencode($_GET["q"]);

    $shows = array();

    // Faire une recherche à l'aide de l'API de TOU.TV
    $ch = curl_init(
        "https://services.radio-canada.ca/ott/catalog/v1/toutv/search?device=web&pageNumber=1&pageSize=999999999&term=" . $query
    );

    curl_setopt_array(
        $ch,
        [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
        ]
    );

    $str_response = curl_exec($ch);

    $resp = json_decode($str_response, true);

    curl_close($ch);

    // Parcourt chaque résultat de recherche
    foreach ($resp["result"] as $_ => $s) {

        // On ne garde que les éléments de type "Show" (émissions sans ordre particulier)
        // ou "Season" (séries avec une structure d'épisodes, ex : Grey’s Anatomy)
        if (!in_array($s["type"], ["Show", "Season"])) {
            continue; // Ignore les types non pertinents
        }

        $show = array();

        $show["id"] = $s["url"];
        $show["title"] = $s["title"];
        $show["image"] = $s["image"]["url"];
        $show["type"] = $s["type"];

        array_push($shows, $show);
    }

    echo json_encode($shows);
} catch (Exception $e) {
    echo json_encode(["error" => "Erreur dans la recherche de shows $query"]);
}
