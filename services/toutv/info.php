<?php
declare(strict_types=1);
header('Content-type: application/json');

$info_type = $_GET["type"];

$allowed_types = ["show", "season", "episode"];

if (!in_array($info_type, $allowed_types)) {
    echo json_encode(["error" => "La categorie $info_type n'existe pas"]);
    die();
}

$id;
$season_number;

if (isset($_GET["id"])) {
    $id = $_GET["id"];
}

if (isset($_GET["season"])) {
    $season_number = $_GET["season"];
}



switch ($info_type) {
    case "show":
        echo Show($id);
        break;

    case "season":
        echo Season();
        break;

    case "episode":
        echo Episode($id);
        break;
}

function Show($id): string
{
    $ch = curl_init(
        "https://services.radio-canada.ca/ott/catalog/v2/toutv/show/" .
            $id .
            "?device=web"
    );

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
    ]);

    $str_response = curl_exec($ch);

    $resp = json_decode($str_response, true);

    curl_close($ch);

    $show = [];

    $show["title"] = $resp["title"];
    $show["description"] = $resp["description"];
    $show["image"] = $resp["images"]["background"]["url"];
    $show["seasons"] = [];

    foreach ($resp["content"][0]["lineups"] as $_ => $s) {
        $season = [];

        $season["episodes"] = [];

        $season["title"] = $s["title"];
        $season["number"] = $s["seasonNumber"];

        foreach ($s["items"] as $_ => $e) {
            if ($e["mediaType"] == "Trailer" || !isset($e["idMedia"])) {
                continue;
            }

            $episode = [];

            $episode["id"] = (string) $e["idMedia"];
            $episode["title"] = $e["title"];
            $episode["description"] = $e["description"];
            $episode["image"] = $e["images"]["card"]["url"];
            $episode["number"] = $e["episodeNumber"];

            $episode["service"] = "toutv";

            array_push($season["episodes"], $episode);
        }

        array_push($show["seasons"], $season);
    }

    return json_encode($show);
}

function Season(): string
{
    $error_message = [
        "error" =>
            "Toutv ne supporte pas de recueillir les saisons directement, il faut plutot les recueillir a partir de ?id=show",
    ];
    return json_encode($error_message);
}

function Episode($id): string
{
    $ch = curl_init(
        "https://services.radio-canada.ca/media/meta/v1/index.ashx?appCode=toutv&output=jsonObject&idMedia=" .
            $id
    );

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
    ]);

    $str_response = curl_exec($ch);

    $resp = json_decode($str_response, true);

    curl_close($ch);

    $episode = [];

    $episode["id"] = $resp["Metas"]["idMedia"];
    $episode["title"] = $resp["Metas"]["Title"];
    $episode["description"] = $resp["Metas"]["Description"];
    $episode["image"] = $resp["Metas"]["imagePlayerNormalC"];
    $episode["number"] = (int) $resp["Metas"]["SrcEpisode"];

    $episode["service"] = "toutv";

    $episode["contains_drm"] = (bool)$resp["Metas"]["isDrmActive"];

    if (filter_var($resp["Metas"]["IsFree"], FILTER_VALIDATE_BOOLEAN)) {
        $episode["availability"] = "free";
    }
    else {
        # Faux, je ne sais pas si c'est Member ou premium, mais je peux pas savoir :(
        $episode["availability"] = "paid";
    }

    return json_encode($episode);
}
