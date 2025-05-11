<?php

declare(strict_types=1);

// La réponse sera toujours en JSON
header('Content-type: application/json');

try {

    if (!isset($_GET["type"], $_GET["id"])) {
        echo json_encode(["error" => "Un parametre requis n'etait pas present."]);
        die();
    }

    // Récupère le type d'information demandé (show, season ou episode)
    $info_type = $_GET["type"];

    // Liste des types supportés
    $allowed_types = ["show", "season", "episode"];

    // Valide le type d'information
    if (!in_array($info_type, $allowed_types)) {
        echo json_encode(["error" => "La catégorie '$info_type' n'existe pas."]);
        die();
    }

    // Paramètres additionnels potentiels
    $id = $_GET["id"];


    // Route le traitement selon le type
    switch ($info_type) {
        case "show":
            echo Show($id);
            break;
        case "season":
            $season_number = $_GET["season"];
            echo Season(); // TOU.TV ne permet pas d'appeler une saison directement
            break;
        case "episode":
            echo Episode($id);
            break;
    }
} catch (Exception $e) {
    error_log("Erreur dans la recuperation d'informations dans l'url en GET: Client ".$_SERVER['REMOTE_ADDR']);
    echo json_encode(["error" => "Erreur dans la recuperation d'informations dans l'url en GET"]);
}

/**
 * Récupère les informations complètes d'une émission (show), y compris ses saisons et épisodes.
 *
 * @param string $id Identifiant de l'émission
 * @return string JSON représentant la structure de l'émission
 */
function Show(string $id): string
{
    if (!$id) {
        return json_encode(["error" => "Paramètre 'id' manquant pour une émission."]);
    }

    try {

        $ch = curl_init("https://services.radio-canada.ca/ott/catalog/v2/toutv/show/$id?device=web");

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
        ]);

        $str_response = curl_exec($ch);
        $resp = json_decode($str_response, true);
        curl_close($ch);

        if (!$resp || !isset($resp["title"])) {
            return json_encode(["error" => "Impossible de récupérer les données de l’émission."]);
        }

        $show = [
            "title" => $resp["title"],
            "description" => $resp["description"],
            "image" => $resp["images"]["background"]["url"],
            "seasons" => []
        ];

        // Parcours les saisons
        foreach ($resp["content"][0]["lineups"] as $s) {
            $season = [
                "title" => $s["title"],
                "number" => $s["seasonNumber"],
                "episodes" => []
            ];

            // Filtre les bandes-annonces ou vidéos sans média (genre gros événements qui arrivent bientôt)
            foreach ($s["items"] as $e) {
                if ($e["mediaType"] === "Trailer" || !isset($e["idMedia"])) {
                    continue;
                }

                $season["episodes"][] = [
                    "id" => (string) $e["idMedia"],
                    "title" => $e["title"],
                    "description" => isset($e["description"]) ? $e["description"] : $show["description"], // Au cas ou il n'a pas de description
                    "image" => $e["images"]["card"]["url"],
                    "number" => $e["episodeNumber"],
                    "service" => "toutv"
                ];
            }

            $show["seasons"][] = $season;
        }

        return json_encode($show);
    } catch (Exception $e) {
        error_log("Tentative de recuperation d'informations sur le show $id: Client ".$_SERVER['REMOTE_ADDR']);
        return json_encode(["error" => "Erreur dans la recuperation d'informations du show $id"]);
    }
}

/**
 * Message d’erreur pour les requêtes directes de saison (non supportées par TOU.TV).
 *
 * @return string JSON avec message d’erreur
 */
function Season(): string
{
    return json_encode([
        "error" => "TOU.TV ne supporte pas l'accès direct aux saisons. Utilisez ?type=show&id=... à la place."
    ]);
}

/**
 * Récupère les métadonnées détaillées d’un épisode.
 *
 * @param string $id ID de l’épisode
 * @return string JSON des informations de l’épisode
 */
function Episode(string $id): string
{
    if (!$id) {
        return json_encode(["error" => "Paramètre 'id' manquant pour un épisode."]);
    }

    try {

        $url = "https://services.radio-canada.ca/media/meta/v1/index.ashx?appCode=toutv&output=jsonObject&idMedia=$id";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
        ]);

        $str_response = curl_exec($ch);
        $resp = json_decode($str_response, true);
        curl_close($ch);

        if (!$resp || !isset($resp["Metas"])) {
            return json_encode(["error" => "Impossible de récupérer les données de l’épisode."]);
        }

        $meta = $resp["Metas"];

        $episode = [
            "id" => $meta["idMedia"],
            "title" => $meta["Title"],
            "description" => $meta["Description"],
            "image" => $meta["imagePlayerNormalC"],
            "number" => (int) $meta["SrcEpisode"],
            "service" => "toutv",
            "contains_drm" => (bool) $meta["isDrmActive"],
            "availability" => filter_var($meta["IsFree"], FILTER_VALIDATE_BOOLEAN) ? "free" : "paid"
            // Faux, je ne sais pas si c'est Member ou premium, mais je peux pas savoir :(
        ];

        return json_encode($episode);
    } catch (Exception $e) {
        error_log("Tentative de recuperation d'informations sur l'episode $id: Client ".$_SERVER['REMOTE_ADDR']);
        return json_encode(["error" => "Erreur dans la recuperation d'informations de l'episode $id"]);
    }
}
