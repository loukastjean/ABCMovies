<?php
require_once __DIR__ . "/classes/session.include.php";
require_once __DIR__ . "/classes/db.include.php";

// Fonction d'envoi de réponse JSON
function sendJson(array $data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    die();
}

// Vérifie si l'utilisateur est connecté
if (!ResumeSession("logged")) {
    sendJson(["error" => "Utilisateur non connecté", "success" => false]);
}

// Récupération et validation des paramètres
$service = filter_input(INPUT_GET, "service", FILTER_DEFAULT);
$id = filter_input(INPUT_GET, "id", FILTER_DEFAULT);

if (empty($service) || empty($id)) {
    sendJson(["error" => "Paramètre 'service' ou 'id' manquant", "success" => false]);
}

// Initialisation
$username = $_SESSION["username"];
$likedVideos = SelectLiked($username);
$alreadyLiked = in_array($id, array_column($likedVideos, 'id'));

// Si on demande à vérifier si c'est liké
if (isset($_GET["verify"])) {
    sendJson(["success" => true, "liked" => $alreadyLiked]);
}

// Si on demande à retirer un like
if (isset($_GET["remove"])) {
    // Si il est liked, proceder a la deletion
    if ($alreadyLiked) {
        RemoveLiked($username, $service, $id);
        sendJson(["error" => 0, "success" => true]);
    } else {
        // Sinon, donner une erreur car tente de remove un like sur une video pas liked
        sendJson(["error" => "Vidéo non likée", "success" => false]);
    }
}

// Si la vidéo est déjà likée
if ($alreadyLiked) {
    sendJson(["error" => "Vidéo déjà likée", "success" => false]);
}

try {
    // Vérifier si la vidéo existe
    $videoExists = false;
    $allVideos = SelectVideos(); // À remplacer par une requête ciblée pour la performance

    foreach ($allVideos as $video) {
        if ($video["id"] === $id) {
            $videoExists = true;
            break;
        }
    }

    // Si la vidéo n'existe pas encore dans la BD, l'ajouter
    if (!$videoExists) {
        $services = SelectServices();
        foreach ($services as $s) {
            if ($s["name"] === $service) {
                AddVideo((int)$s["idservice"], $id);
                break;
            }
        }
    }

    // Ajouter le like
    AddLiked($username, $service, $id);
    sendJson(["error" => 0, "message" => "Vidéo likée", "success" => true]);

} catch (Exception $e) {
    // Tu pourrais ici loguer $e->getMessage() dans un fichier de log
    sendJson(["error" => "Erreur lors de l'ajout/suppression du like", "success" => false]);
}