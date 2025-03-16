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
            $show->seasons[] = $season;

            $season->title = $s["title"];
            $season->number = $s["seasonNumber"];

            foreach ($season as $_ => $e) {
                if ($e["mediaType"] == "Trailer") {
                    continue;
                }

                $episode = new Episode();
                $season->episodes[] = $episode;

                $episode->id = $e["idMedia"];
                $episode->title = $e["title"];
                $episode->description = $e["description"];
                $episode->image = $e["images"]["card"]["url"];
                $episode->number = $e["episodeNumber"];
            }
        }
    }

    

}