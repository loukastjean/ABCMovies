<?php
require_once __DIR__."/classes/session.include.php";

if (!ResumeSession("logged")) {
    echo json_encode(["error" => "L'utilisateur n'est pas connecte"]);
    die();
}

if (!empty($_GET["service"]) && !empty($_GET["id"]))
{
    require_once(__DIR__."/classes/db.include.php");

    $service = filter_input(INPUT_GET,"service",FILTER_DEFAULT); // Futur: FILTER_DEFAULT
    $id = filter_input(INPUT_GET,"id",FILTER_DEFAULT);

    //Faire une sélect sur la bd pour trouver le username
    //Le username existe pour l"exemple
    //Il faut récupérer le mot de passe associé à ce compte.

    $liked = SelectLiked($_SESSION["username"]);

    $alreadyLiked = false;

    foreach ($liked as $likedVideo) {
        if ($likedVideo["id"] == $id) {
            $alreadyLiked = true;
        }
    }

    // Si il n'est pas deja liked et que l'utilisateur ne voulait pas l'enlever, l'ajouter
    if (isset($_GET["remove"])) {
        // Si il est liked
        if ($alreadyLiked) {
            RemoveLiked($_SESSION["username"], $service, $id);
            echo json_encode(["error" => 0, "debug" => "Deleted $id from liked", "success"=> true]);
            die();
        }
        // Sinon, donner une erreur car tente de remove un like sur une video pas liked
        else {
            echo json_encode(["error" => "La video n'etait pas liked", "success"=> false]);
            die();
        }
    }

    // Si le parametre verify est present dans l'url pour la verification du like
    if (isset($_GET["verify"])) {
        echo json_encode(["success" => true, "liked" => $alreadyLiked, "debug" => [$_GET]]);
        die();
    }


    // Si elle etait deja like
    if ($alreadyLiked) {
        echo json_encode(["error" => "Il est deja liked", "success"=> false]);
        die();
    }

    // Si elle etait pas deja liked
    try {
        $videos = SelectVideos();

        $videoExists = false;
    
        foreach ($videos as $video) {
            if ($video["id"] == $id) {
                $videoExists = true;
            }
        }
    
        if (!$videoExists && !isset($_GET["remove"])) {
            $services = SelectServices();
            foreach ($services as $s) {
                if ($s["name"] == $service) {
                    AddVideo((int)$s["idservice"], $id);
                }
            } 
        }

        AddLiked($_SESSION["username"], $service, $id);
        echo json_encode(["error" => 0, "debug" => "Added $id to liked", "success"=> true]);
        die();
    }
    catch (Exception $e) {
        echo json_encode(["error" => "Il n'a pas delete ou add, probablement parce qu'il n'etait pas liked/unliked", "success"=> false]);
        die();
    }
}

echo json_encode(["error" => "Il manque le service ou le id du media liked", "success"=> false]);