<?php

declare(strict_types=1);

class Downloads
{
    public function Episode($id): string
    {

        $url = "https://services.radio-canada.ca/media/validation/v2/?appCode=medianet&output=json&tech=hls&manifestVersion=2&idMedia=".$id;

        $ch = curl_init($url);

        curl_setopt_array(
            $ch,
            array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false
            )
        );

        $str_response = curl_exec($ch);

        $resp = json_decode($str_response, true);

        curl_close($ch);

        $raw_url = $resp["url"];
        $cleaned_url = explode("?", $raw_url)[0];

        return $cleaned_url;
    }
}
