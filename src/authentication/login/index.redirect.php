<?php

require_once $_SERVER['DOCUMENT_ROOT']."/classes/db.include.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/session.include.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/email.include.php";

try {
    // Vérifie que les champs "username" et "password" ont été envoyés
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        // Filtre les données POST
        $username = filter_input(INPUT_POST, "username", FILTER_VALIDATE_EMAIL); // Peut être modifié en FILTER_DEFAULT si ce n’est plus un email
        $password = filter_input(INPUT_POST, "password", FILTER_DEFAULT);

        // Récupère l'utilisateur en base de données via son nom d'utilisateur
        $user = SelectUserByUsername($username);

        // Vérifie que l’utilisateur existe et que le mot de passe correspond
        if ($user && $username === $user->username && password_verify($password, $user->password)) {

            // Crée une session temporaire nommée "auth" pour la validation 2FA
            CreateSession("auth");

            $_SESSION["username"] = $username;

            // Envoie un code de vérification à l'utilisateur (email dans ce cas ci)
            SendVerificationCode($username);

            error_log("[".date("d/m/o H:i:s e", time())."] ".$_SESSION["username"]." tente de se connecter (avant le 2FA): Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.successful.login.log");

            // Redirige vers la page de validation du code 2FA
            header("Location: ../2fa/");
            die();
        }
    }
} catch (Exception $e) {
    error_log("[".date("d/m/o H:i:s e", time())."] ".$_SESSION["username"]." tente de se connecter (avant le 2FA): Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.failed.login.log");
    die();
}

error_log("[".date("d/m/o H:i:s e", time())."] Tentative de connexion échouée: Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.failed.login.log");

// Si la validation échoue ou les champs sont vides, redirige vers la page de connexion
header("Location: index.php?error=wrong");
