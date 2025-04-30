<?php

/**
 * Supprime la session en cours et le cookie associé.
 *
 * @param string $session_name Nom de la session à supprimer
 * @return void
 */
function DeleteSession($session_name)
{
    if (session_status() == PHP_SESSION_ACTIVE) {

        // Récupère les paramètres du cookie pour pouvoir le supprimer proprement
        $p = session_get_cookie_params();

        // Supprime le cookie de session en le rétro-datant
        setcookie(
            $session_name,
            '',
            time() - 60 * 60 * 24 * 30,
            $p["path"],
            $p["domain"],
            $p["secure"],
            $p["httponly"]
        );

        session_destroy();
        $_SESSION = array();
    }
}

/**
 * Crée une nouvelle session sécurisée avec des paramètres
 *
 * @param string $session_name Nom de la session
 * @param int $duree Durée de la session en secondes (par défaut : 1 heure)
 * @return void
 */
function CreateSession($session_name, $duree = 60 * 60)
{
    DeleteSession($session_name); // Supprime toute session existante avec le même nom

    // Configuration des paramètres de sécurité de la session
    ini_set("session.cookie_lifetime", time() + $duree);
    ini_set("session.use_cookies", 1);
    ini_set("session.use_only_cookies", 1);
    ini_set("session.use_strict_mode", 1);
    ini_set("session.cookie_httponly", 1);
    ini_set("session.cookie_secure", 1);
    ini_set("session.cookie_samesite", "Strict");
    ini_set("session.cache_limiter", "nocache"); 
    ini_set("session.hash_function", "sha512");

    session_name($session_name);
    session_start();

    $_SESSION["time"] = time();
    $_SESSION["ip"] = $_SERVER["REMOTE_ADDR"];
}

/**
 * Vérifie que la session en cours est valide et sécurisée.
 *
 * @return bool true si la session est valide, false sinon
 */
function VerifySession(): bool
{
    if (session_status() == PHP_SESSION_ACTIVE) {

        // Vérifie que les paramètres de sécurité existent
        if (!isset($_SESSION["username"]) || !isset($_SESSION["ip"]) || !isset($_SESSION["time"])) {
            return false;
        }

        // Vérifie que l'adresse IP n'a pas changé
        if ($_SESSION["ip"] != $_SERVER["REMOTE_ADDR"]) {
            return false;
        }

        // Vérifie que la session n’a pas expiré (1 heure)
        if (time() > $_SESSION["time"] + 60 * 60) {
            return false;
        }

        return true;

    } else {
        return false;
    }
}

/**
 * Tente de reprendre une session existante
 *
 * @param string $session_name Nom de la session à reprendre
 * @return bool true si la session est valide, false sinon
 */
function ResumeSession($session_name)
{
    session_name($session_name);
    session_start();

    if (!VerifySession()) {
        DeleteSession($session_name); // Supprime la session si elle est invalide
        return false;
    }

    $_SESSION["time"] = time(); // Met à jour le timestamp pour prolonger la session
    return true;
}
