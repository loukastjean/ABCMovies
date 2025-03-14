<?php

declare(strict_types=1);

class Info{
    function Episode($episode): void {

        $ch = curl_init("https://services.radio-canada.ca/media/meta/v1/index.ashx?appCode=medianet&output=jsonObject&idMedia=".$episode->id);
    
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
        $episode->image = $resp["Metas"]["imageHR"];
        $episode->service = "radiocan";
    }
}