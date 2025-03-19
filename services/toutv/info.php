<?php

declare(strict_types=1);


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

        foreach ($resp["content"][0]["lineups"] as $_ => $s) {
            $season = new Season();
            array_push($show->seasons, $season);

            $season->title = $s["title"];
            $season->number = $s["seasonNumber"];

            foreach ($season as $_ => $e) {
                if ($e["mediaType"] == "Trailer") {
                    continue;
                }

                $episode = new Episode();
                array_push($season->episodes, $episode);

                $episode->id = $e["idMedia"];
                $episode->title = $e["title"];
                $episode->description = $e["description"];
                $episode->image = $e["images"]["card"]["url"];
                $episode->number = $e["episodeNumber"];
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

        $episode->id = $resp["Metas"]["idMedia"];
        $episode->title = $resp["Metas"]["Title"];
        $episode->description = $resp["Metas"]["Description"];
        $episode->image = $resp["Metas"]["imagePlayerNormalC"];
        $episode->number = (int)$resp["Metas"]["SrcEpisode"];
        $episode->service = "toutv";

        $episode->contains_drm = (bool)$resp["Metas"]["isDrmActive"];

        
    }
}