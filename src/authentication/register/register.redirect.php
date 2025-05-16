<?php

require_once $_SERVER['DOCUMENT_ROOT']."/classes/db.include.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/session.include.php";

try {
    // Vérifie que les champs "username" et "password" sont remplis dans le formulaire
    if (filter_input(INPUT_POST, "username", FILTER_VALIDATE_EMAIL) && filter_input(INPUT_POST, "password", FILTER_DEFAULT)) {
        // Nettoie et valide les entrées utilisateur
        $username = filter_input(INPUT_POST, "username", FILTER_VALIDATE_EMAIL); // Peut être remplacé par FILTER_DEFAULT si ce n’est plus un email
        $password = filter_input(INPUT_POST, "password", FILTER_DEFAULT);

        // Recherche dans la base de données si l'utilisateur existe déjà
        $user = SelectUserByUsername($username);

        // Si l'utilisateur n'existe pas, on peut le créer
        if (!$user) {
            // Hash sécurisé du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insère l'utilisateur dans la BD
            CreateUser($username, $hashed_password);

            // Crée une session principale sécurisée nommée "logged"
            CreateSession("logged");

            $_SESSION["username"] = $username;

            error_log("[".date("d/m/o H:i:s e", time())."] Le compte ".$_SESSION["username"]." vient d'être créé: Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.successful.login.log");

            // Redirige vers la page d’accueil
            header("Location: /");
            die();
        } else {
            error_log("[".date("d/m/o H:i:s e", time())."] Tentative de création de compte échouée, car compte $username existe déjà: Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.failed.login.log");
            header("Location: ./?error=alreadyexists");
            die();
        }

    }
} catch (Exception $e) {
    error_log("[".date("d/m/o H:i:s e", time())."] Tentative de création de compte échouée, car compte $username existe déjà: Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.failed.login.log");
    header("Location: ./");
    die();
}

error_log("[".date("d/m/o H:i:s e", time())."] Tentative de création de compte échouée: Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.failed.login.log");

// Si le formulaire est invalide ou si l'utilisateur existe déjà, on redirige vers la page d'inscription
header("Location: ./");
