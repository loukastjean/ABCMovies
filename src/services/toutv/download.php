<?php

declare(strict_types=1);
header('Content-type: application/json');

// Vérifie que les paramètres requis sont bien présents
if (!isset($_GET["id"]) || !isset($_POST["Authorization"]) || !isset($_POST["x-claims-token"])) {
    error_log("download.php: There was a missing value in ".$_POST." or in ".$_GET);
    die();
}

try {

    // Récupère les paramètres d'entrée
    $id = $_GET["id"];
    $authorization_token = $_POST["Authorization"];
    $claims_token = $_POST["x-claims-token"];
    
    // Prépare les en-têtes HTTP à envoyer à l’API de Tou.tv
    $headers = [
        "Authorization: $authorization_token",
        "x-claims-token: $claims_token"
    ];
    
    // Initialise la requête cURL vers l'API d'infos DRM/DASH de Tou.tv
    $ch = curl_init("https://services.radio-canada.ca/media/validation/v2/?output=json&appCode=toutv&tech=dash&idMedia=".$id);
    
    curl_setopt_array(
        $ch,
        [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $headers,
        ]
    );
    
    // Exécute la requête et capture la réponse
    $str_response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code != 200) {
        error_log("download.php: Could not access $str_response on media id " . $id);
        error_log("The headers were :" . json_encode($headers));
        die($http_code);
    }
    
    $resp = json_decode($str_response, true);
    
    // Vérifie que l'URL du manifeste DASH est présente
    if (!isset($resp["url"])) {
        error_log("download.php: Could not access $str_response on media id " . $id);
        error_log("The headers were :" . json_encode($headers));
        die();
    }
    
    // Récupère l’URL du manifeste DASH
    $raw_mpd_url = $resp["url"];
    //$mpd_url = str_replace("filter=3000","filter=7000", $raw_url);
    $mpd_url = $raw_mpd_url;
    
    
    // Recherche les paramètres nécessaires au DRM
    foreach ($resp["params"] as $_ => $param) {
        if ($param["name"] === "widevineLicenseUrl") {
            $licence_url = $param["value"];
        }
        if ($param["name"] === "widevineAuthToken") {
            $request_token = $param["value"];
        }
    }
    
    // Prépare les en-têtes requis pour l’autorisation Widevine
    $mpd_headers = ["x-dt-auth-token" => $request_token];
    
    if (!isset($licence_url) || !isset($request_token)) {
        error_log("download.php: Could not access the license URL or the Auth token on media id " . $id);
        error_log("The headers were :" . json_encode($headers));
        die();
    }
    
    // Appelle le script Python en ligne de commande pour gérer le téléchargement et le DRM
    // Sécuritairement
    $command = sprintf(
        "~/ABCMovies/.venv/bin/python ~/ABCMovies/main.py %s %s %s %s",
        escapeshellarg($mpd_url),
        escapeshellarg($licence_url),
        escapeshellarg(json_encode($mpd_headers)),
        escapeshellarg("toutv-$id")
    );
    
    // Exécute la commande Python et récupère le output
    $output = shell_exec($command);
    
    $message = ["output" => trim($output)];
    
    echo json_encode($message);

} catch (Exception $e) {
    error_log("[".date("d/m/o H:i:s e", time())."] Tentative de telechargement n'a pas reussie: Client ".$_SERVER['REMOTE_ADDR']);
}