<?php

declare(strict_types=1);
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/show.php';

function get_movies() {

    $movies = array();

    $ch = curl_init("https://services.radio-canada.ca/ott/catalog/v2/toutv/category/film?device=web&pageNumber=1&pageSize=999999999");
    
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HEADER => FALSE
    ));
    
    $str_response = curl_exec($ch);
    
    $resp = json_decode($str_response, TRUE);
    
    curl_close($ch);

    foreach ($resp["content"][0]["items"]["results"] as $_ => $result) {
        $show = new Show();
        array_push($movies, $show);

        $show->id = $result["url"];
        $show->title = $result["title"];
        $show->description = $result["description"];
        $show->image = $result["images"]["background"]["url"];
        $show->type = $result["type"];
    }

    return $movies;
}