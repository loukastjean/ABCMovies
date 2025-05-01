<?php

declare(strict_types=1);

header('Content-type: application/json');

// Récupère la catégorie demandée (ex: serie, film, info, etc.)
$show_type = $_GET["category"] ?? '';

// Liste des catégories valides
$allowed_categories = [
    "serie",
    "emission",
    "film",
    "documentaire",
    "info",
    "evenement",
];

// Valide la catégorie
if (!in_array($show_type, $allowed_categories, true)) {
    echo json_encode(["error" => "La catégorie '$show_type' n'existe pas."]);
    exit;
}

$ch = curl_init();

$url_base = "https://services.radio-canada.ca/ott/catalog/v2/toutv/";

// TOU.TV utilise deux types d'URL différents selon la catégorie "info" ou non
$url_base = ($show_type === "info")
    ? $url_base .= "section"
    : $url_base .= "category";

$url = $url_base . "/$show_type?device=web&pageNumber=1&pageSize=999999999";

curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => false,
]);

$str_response = curl_exec($ch);

curl_close($ch);

$resp = json_decode($str_response, true);

// Gère les erreurs de décodage JSON
if (json_last_error() !== JSON_ERROR_NONE || !$resp) {
    error_log("recommendations.php: Erreur lors du traitement de la réponse TOU.TV.");
    die(502);
}

// Initialise la liste des émissions
$shows = [];

if ($show_type === "info") {
    // Cas spécial pour les émissions de nouvelles (structure différente)
    foreach ($resp["lineups"]["results"] as $s) {
        $type = $s["lineupType"];

        // Il va falloir fixer ca, mais pour l'instant les media c'est trop chiant
        if ($type === "Regular") {
            continue;
        }

        // Ceci n'est pas tres beau. Il faut trouver une meilleure maniere de supporter info
        if ($type === "Episodic") {
            $type = "Series";
        }

        $shows[] = [
            "id" => $s["callToActions"]["primary"]["url"],
            "title" => $s["title"],
            "description" => "", // Pas de description dispo dans ce format
            "image" => $s["images"]["logo"]["url"],
            "type" => $type,
            "availability" => get_availability_type($s["tier"] ?? "Standard"),
        ];
    }
} else {
    // Pour les autres catégories normales (film, documentaire, etc.)

    // Recommendations qui changent en haut de la page
    foreach ($resp["header"]["items"] as $s) {
        $shows[] = [
            "id" => $s["url"],
            "title" => $s["title"],
            "description" => $s["description"],
            "image" => $s["images"]["card"]["url"],
            "type" => $s["type"],
            "availability" => get_availability_type($s["tier"]),
        ];
    }

    // Recommendations principales
    foreach ($resp["content"][0]["items"]["results"] ?? [] as $s) {
        $shows[] = [
            "id" => $s["url"],
            "title" => $s["title"],
            "description" => $s["description"],
            "image" => $s["images"]["card"]["url"],
            "type" => $s["type"],
            "availability" => get_availability_type($s["tier"]),
        ];
    }
}

echo json_encode($shows);

/**
 * Convertit le type d'abonnement en une valeur simplifiée
 *
 * @param string $raw_type Type retourné par TOU.TV (ex: Standard, Premium)
 * @return string Type lisible par le client (ex: free, paid)
 */
function get_availability_type(string $raw_type): string
{
    switch ($raw_type) {
        case "Standard":
            return "free";
        case "Member":
            return "account";
        case "Premium":
            return "paid";
        default:
            return "unknown";
    }
}
