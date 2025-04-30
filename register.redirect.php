<?php

require_once __DIR__."/classes/db.include.php";
include_once __DIR__."/classes/session.include.php";

// Vérifie que les champs "username" et "password" sont remplis dans le formulaire
if (!empty($_POST["username"]) && !empty($_POST["password"])) {
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

        // Redirige vers la page d’accueil
        header("Location: index.php?YES");
        die();
    }
}

// Si le formulaire est invalide ou si l'utilisateur existe déjà, on redirige vers la page d'inscription
header("Location: register.php");
