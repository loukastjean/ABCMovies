<?php

declare(strict_types=1);
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/show.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/season.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/episode.php';

class Info{
    function Show($show): void {

        $ch = curl_init("https://services.radio-canada.ca/ott/catalog/v2/toutv/show/".$show->id."?device=web");
    
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => FALSE
        ));
        
        $str_response = curl_exec($ch);
        
        $resp = json_decode($str_response, TRUE);
        
        curl_close($ch);

        $show->title = $resp["title"];
        $show->description = $resp["description"];
        $show->image = $resp["images"]["background"]["url"];
        $show->seasons = [];

        foreach ($resp["content"][0]["lineups"] as $_ => $s) {
            $season = new Season();
            array_push($show->seasons, $season);
            $season->episodes = [];

            $season->title = $s["title"];
            $season->number = $s["seasonNumber"];

            foreach ($s["items"] as $_ => $e) {
                if ($e["mediaType"] == "Trailer") {
                    continue;
                }

                $episode = new Episode();
                array_push($season->episodes, $episode);

                $episode->id = (string)$e["idMedia"];
                $episode->title = $e["title"];
                $episode->description = $e["description"];
                $episode->image = $e["images"]["card"]["url"];
                $episode->number = $e["episodeNumber"];

                $episode->service = "toutv";
            }
        }
    }

    function Episode($episode): void {

        $ch = curl_init("https://services.radio-canada.ca/media/meta/v1/index.ashx?appCode=toutv&output=jsonObject&idMedia=".$episode->id);
    
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => FALSE
        ));
        
        $str_response = curl_exec($ch);
        
        $resp = json_decode($str_response, TRUE);
        
        curl_close($ch);

        error_log("Error handling media with URL: "."https://services.radio-canada.ca/media/meta/v1/index.ashx?appCode=toutv&output=jsonObject&idMedia=".$episode->id);

        $episode->id = $resp["Metas"]["idMedia"];
        $episode->title = $resp["Metas"]["Title"];
        $episode->description = $resp["Metas"]["Description"];
        $episode->image = $resp["Metas"]["imagePlayerNormalC"];
        $episode->number = (int)$resp["Metas"]["SrcEpisode"];
        $episode->service = "toutv";

        $episode->contains_drm = (bool)$resp["Metas"]["isDrmActive"];

        
    }
}