<?php

function DeleteSession($session_name) {
    if (session_status() == PHP_SESSION_ACTIVE) {

        $p = session_get_cookie_params(); //Pour antidater (détruire) le cookie

        setcookie(
            $session_name, '', time()-60*60*24*30,
            $p["path"], $p["domain"],
            $p["secure"], $p["httponly"]
        );

        session_destroy(); //La session est effacée
        $_SESSION = array(); //La variable superglobale est supprimée
    }
}

function CreateSession($session_name, $duree = 60*60) {

    DeleteSession($session_name);
    
    ini_set("session.cookie_lifetime", time()+$duree); // Durée de la session en secondes
    ini_set("session.use_cookies", 1);
    ini_set("session.use_only_cookies" , 1);
    ini_set("session.use_strict_mode", 1);
    ini_set("session.cookie_httponly", 1);
    ini_set("session.cookie_secure", 1);//Pour docker local. Mettre à 1 en production sur techninfo420.
    ini_set("session.cookie_samesite" , "Strict");
    ini_set("session.cache_limiter" , "nocache");
    ini_set("session.hash_function" , "sha512");
    
    session_name($session_name); //C'est la session pour l'authentification

    session_start();

    $_SESSION["time"] = time();
    $_SESSION["ip"] = $_SERVER["REMOTE_ADDR"];
}


function VerifySession(): bool {
    if (session_status() == PHP_SESSION_ACTIVE) {
        // Si il manque un des parametres de session
        if (!isset($_SESSION["username"]) || !isset($_SESSION["ip"]) || !isset($_SESSION["time"])) {
            return false;
        }
        // Si ce n'est pas la meme adresse IP
        elseif ($_SESSION["ip"] != $_SERVER["REMOTE_ADDR"]) {
            return false;
        }
        // Si on a depasse la duree de la session (60 mins)
        elseif (time() > $_SESSION["time"] + 60*60) {
            return false;
        }

        else {
            return true;
        }
    }
    else {
        return false;
    }
}

function ResumeSession($session_name) {
    session_name($session_name);
    session_start();
    if (!VerifySession()) {
        DeleteSession($session_name);
        header("Location: index.php");
    }
}

