<?php

declare(strict_types=1);

class Info
{
    public function Episode($episode): void
    {

        $ch = curl_init("https://services.radio-canada.ca/media/meta/v1/index.ashx?appCode=medianet&output=jsonObject&idMedia=".$episode->id);

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

        $episode->id = $resp["Metas"]["idMedia"];
        $episode->title = $resp["Metas"]["Title"];
        $episode->description = $resp["Metas"]["Description"];
        $episode->image = $resp["Metas"]["imageHR"];
        $episode->service = "radiocan";
    }
}
