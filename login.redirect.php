<?php

require_once __DIR__."/classes/db.include.php";
require_once __DIR__."/classes/session.include.php";
include_once __DIR__."/classes/email.include.php";

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

        // Redirige vers la page de validation du code 2FA
        header("Location: 2fa.php");
        die();
    }
}

// Si la validation échoue ou les champs sont vides, redirige vers la page de connexion
header("Location: login.php");
