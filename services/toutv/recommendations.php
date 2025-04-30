<?php

declare(strict_types=1);
header('Content-type: application/json');

$show_type = $_GET["category"];

$allowed_categories = [
    "serie",
    "emission",
    "film",
    "documentaire",
    "info",
    "evenement",
];

if (!in_array($show_type, $allowed_categories)) {
    echo json_encode(["error" => "La categorie $show_type n'existe pas"]);
    die();
}

$ch = curl_init();

if ($show_type == "info") {
    curl_setopt(
        $ch,
        CURLOPT_URL,
        "https://services.radio-canada.ca/ott/catalog/v2/toutv/section/$show_type?device=web&pageNumber=1&pageSize=999999999"
    );
} else {
    curl_setopt(
        $ch,
        CURLOPT_URL,
        "https://services.radio-canada.ca/ott/catalog/v2/toutv/category/$show_type?device=web&pageNumber=1&pageSize=999999999"
    );
}

curl_setopt_array(
    $ch,
    [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => false,
    ]
);

$str_response = curl_exec($ch);

$resp = json_decode($str_response, true);

curl_close($ch);

$shows = [];

if ($show_type == "info") {
    foreach ($resp["lineups"]["results"] as $_ => $s) {
        $show = [];

        $show["id"] = $s["callToActions"]["primary"]["url"];
        $show["title"] = $s["title"];
        $show["description"] = "";
        $show["image"] = $s["images"]["logo"]["url"];
        $show["type"] = $s["lineupType"];

        $show["availability"] = get_availability_type($s["tier"]);

        // Il va falloir fixer ca, mais pour l'instant les media c'est trop chiant
        if ($show["type"] == "Regular") {
            continue;
        }

        // Ceci n'est pas tres beau. Il faut trouver une meilleure maniere de supporter info
        if ($show["type"] == "Episodic") {
            $show["type"] = "Series";
        }

        array_push($shows, $show);
    }
} else {
    foreach ($resp["header"]["items"] as $_ => $s) {
        $show = [];

        $show["id"] = $s["url"];
        $show["title"] = $s["title"];
        $show["description"] = $s["description"];
        $show["image"] = $s["images"]["card"]["url"];
        $show["type"] = $s["type"];

        $show["availability"] = get_availability_type($s["tier"]);

        array_push($shows, $show);
    }

    foreach ($resp["content"][0]["items"]["results"] as $_ => $s) {
        $show = [];

        $show["id"] = $s["url"];
        $show["title"] = $s["title"];
        $show["description"] = $s["description"];
        $show["image"] = $s["images"]["card"]["url"];
        $show["type"] = $s["type"];

        $show["availability"] = get_availability_type($s["tier"]);

        array_push($shows, $show);
    }
}

echo json_encode($shows);

function get_availability_type($raw_type)
{
    switch ($raw_type) {
        case "Standard":
            return "free";

        case "Member":
            return "account";

        case "Premium":
            return "paid";
    }
}
