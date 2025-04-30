<?php

require_once __DIR__."/classes/session.include.php";

ResumeSession("auth");

// Vérifie que la session 2FA est active et valide
Verify2FASession();

/**
 * Si un code a été soumis via POST, le traiter.
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

        header("Location: index.php");
        die();
    } else {
        // Code invalide → retour à la page de vérification
        header("Location: 2fa.php");
        die();
    }
}

/**
 * Vérifie que la session "auth" contient les éléments nécessaires pour la validation 2FA.
 * Redirige vers la page 2FA si ce n’est pas le cas.
 *
 * @return void
 */
function Verify2FASession()
{
    if (session_status() == PHP_SESSION_ACTIVE) {
        // Si la session est invalide ou ne contient pas de code 2FA
        if (!VerifySession() || !isset($_SESSION["code"])) {
            header("Location: 2fa.php");
            die();
        }
    }
}
