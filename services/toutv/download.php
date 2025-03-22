<?php
declare(strict_types=1);
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/toutv/login.php';

class Download {


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

        #error_log("The x-dt-auth-token is ".$token["x-dt-auth-token"]);

        $command = "/home/stjeanh25techinf/ABCMovies/main.py '$mpd_url' '$episode->licence_url' '".json_encode($token)."' '$episode->name' &";

        $output = shell_exec($command);

    }

    function Episode($episode) {

        $raw_headers = get_tokens();

        $ch = curl_init("https://services.radio-canada.ca/media/validation/v2/?output=json&appCode=toutv&tech=dash&idMedia=".$episode->id);
    
        $headers = array();

        $headers[] = "Authorization: ".$raw_headers["Authorization"];
        $headers[] = "x-claims-token: ".$raw_headers["x-claims-token"];

        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => FALSE,
            CURLOPT_HTTPHEADER => $headers
        ));
        
        $str_response = curl_exec($ch);

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);

        $resp = json_decode($str_response, TRUE);

        #error_log("Got http response code ".$http_code." on media id ".$episode->id);
        #error_log("The headers were :".json_encode($headers));

        if (!isset($resp["url"])) {
            error_log("Could not access $str_response on media id ".$episode->id);
            error_log("The headers were :".json_encode($headers));
            return;
        }

        

        $raw_url = $resp["url"];

        $clean_url = $raw_url;
        //$clean_url = str_replace("filter=3000","filter=7000", subject: $raw_url);
        
        error_log("The url is: ".$clean_url);

        if ($episode->contains_drm) {
            return $this->Encrypted($episode, $resp, $clean_url);
        }

        return $clean_url;
    }


}