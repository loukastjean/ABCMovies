<?php

require_once $_SERVER['DOCUMENT_ROOT']."/classes/session.include.php";

ResumeSession("auth");

// Vérifie que la session 2FA est active et valide
Verify2FASession();

try {
    /**
     * Si un code a été soumis, le traiter.
     */
    if (!empty($_POST["code"])) {

        $code = filter_input(INPUT_POST, "code", FILTER_DEFAULT);

        if ($code == $_SESSION["code"]) {
            // Sauvegarde temporairement le nom d'utilisateur
            $username = $_SESSION["username"];

            // Supprime la session temporaire "auth"
            DeleteSession(session_name());

            // Crée une nouvelle session sécurisée, nommée "logged"
            CreateSession("logged");

            $_SESSION["username"] = $username;

            error_log("[".date("d/m/o H:i:s e", time())."] ".$_SESSION["username"]." s'est connecté: Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.successful.login.log");

            header("Location: /");
            die();
        } else {

            error_log("[".date("d/m/o H:i:s e", time())."] Tentative de 2FA de ".$_SESSION["username"]." échouée: Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.failed.login.log");

            // Code invalide → retour à la page de vérification
            header("Location: ./?error=wrongcode");
            die();
        }
    }
} catch (Exception $e) {
    error_log("[".date("d/m/o H:i:s e", time())."] Tentative de 2FA de ".$_SESSION["username"]." échouée: Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.failed.login.log");
    header("Location: ./?error=wrongcode");
    die();
}

error_log("[".date("d/m/o H:i:s e", time())."] Tentative de 2FA de ".$_SESSION["username"]." échouée: Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.failed.login.log");
header("Location: ./?error=nocode");

/**
 * Vérifie que la session "auth" contient les éléments nécessaires pour la validation 2FA.
 * Redirige vers la page 2FA si ce n’est pas le cas.
 *
 * @return void
 */
function Verify2FASession()
{
    try {
        if (session_status() == PHP_SESSION_ACTIVE) {
            // Si la session est invalide ou ne contient pas de code 2FA
            if (!VerifySession()) {
                header("Location: ./");
                die();
            }
            if (!isset($_SESSION["code"])) {
                header("Location: ./?error=nocode");
                die();
            }
        }
    } catch (Exception $e) {
        error_log("[".date("d/m/o H:i:s e", time())."] Verify2FASession Tentative de 2FA de ".$_SESSION["username"]." échouée: Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.failed.login.log");
    }
}
