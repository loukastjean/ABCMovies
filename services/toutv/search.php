<?php

declare(strict_types=1);
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/show.php';

function get_search_results($show_name): array{
    $available_shows = array();

    $ch = curl_init("https://services.radio-canada.ca/ott/catalog/v1/toutv/search?device=web&pageNumber=1&pageSize=999999999&term=$show_name");
    
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HEADER => FALSE
    ));
    
    $str_response = curl_exec($ch);
    
    $resp = json_decode($str_response, TRUE);
    
    curl_close($ch);

    foreach ($resp["result"] as $_ => $result) {
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

    return $available_shows;
}
?>