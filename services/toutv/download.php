<?php


class Download {

    function getHttpRequest($url) {
        $ch = curl_init($url);
    
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => FALSE
        ));
        
        $str_response = curl_exec($ch);
        
        curl_close($ch);

        return $str_response;
    }

    function Encrypted($episode, $resp, $mpd_url) {
        foreach ($resp["params"] as $_ => $param) {
            if ($param["name"] == "widevineLicenseUrl") {
                $episode->licence_url = $param["value"];
            }
            if ($param["name"] == "widevineAuthToken") {
                $episode->request_token = $param["value"];
            }
        }

        $token = array("x-dt-auth-token" => $episode->request_token);

        $command = "/home/stjeanh25techinf/ABCMovies/main.py '".$mpd_url."' '".$episode->licence_url."' '".json_encode($token)."' '".$episode->name."'";

        shell_exec($command);

    }

    function Episode($episode) {

        $str_response = $this->getHttpRequest("https://services.radio-canada.ca/media/validation/v2/?output=json&appCode=toutv&tech=dash&idMedia=".$episode->id);

        $resp = json_decode($str_response, TRUE);

        $raw_url = $resp["url"];

        $clean_url = str_replace("filter=3000","filter=7000", $raw_url);

        if ($episode->contains_drm) {
            return $this->Encrypted($episode, $resp, $clean_url);
        }

        return $clean_url;
    }


}