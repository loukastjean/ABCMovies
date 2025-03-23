<?php
declare(strict_types=1);

$id;
$login_headers;

if (isset($_GET["id"])) {
    $id = $_GET["id"];
}

if (isset($_GET["tokens"])) {
    $login_headers = $_GET["tokens"];
}

#$login_headers = $_POST["tokens"];
#
#if (isset($_POST["tokens"])) {
#    $login_headers = $_POST["tokens"];
#}

$login_headers = json_decode($login_headers, true);

$ch = curl_init(
    "https://services.radio-canada.ca/media/validation/v2/?output=json&appCode=toutv&tech=dash&idMedia=".$id
);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => false,
    CURLOPT_HTTPHEADER => $login_headers,
]);

$str_response = curl_exec($ch);

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

$resp = json_decode($str_response, true);


if (!isset($resp["url"])) {
    error_log("Could not access $str_response on media id " . $id);
    error_log("The headers were :" . json_encode($login_headers));
    return;
}

$raw_mpd_url = $resp["url"];

$mpd_url = $raw_mpd_url;
//$clean_url = str_replace("filter=3000","filter=7000", subject: $raw_url);


foreach ($resp["params"] as $_ => $param) {
    if ($param["name"] == "widevineLicenseUrl") {
        $licence_url = $param["value"];
    }
    if ($param["name"] == "widevineAuthToken") {
        $request_token = $param["value"];
    }
}

$mpd_headers = ["x-dt-auth-token" => $request_token];

$command = "/home/stjeanh25techinf/ABCMovies/main.py '$mpd_url' '$licence_url' '".json_encode($mpd_headers)."' 'toutv-$id'";

$output = shell_exec($command);

$message = array("output" => trim($output));

echo json_encode($message);