<?php

require_once __DIR__."/classes/db.include.php";


if (!empty($_POST["username"]) && !empty($_POST["password"])) {
    $username = filter_input(INPUT_POST, "username", FILTER_VALIDATE_EMAIL); // Futur: FILTER_DEFAULT
    $password = filter_input(INPUT_POST, "password", FILTER_DEFAULT);

    //Faire une sélect sur la bd pour trouver le username
    //Le username existe pour l"exemple
    //Il faut récupérer le mot de passe associé à ce compte.

    $user = SelectUserByUsername($username);

    if (!$user) {
        CreateUser($username, password_hash($password, PASSWORD_DEFAULT));

        include_once __DIR__."/classes/session.include.php";

        CreateSession("logged");

        $_SESSION["username"] = $username;
        header("Location: index.php?YES");
        die();
    }
}

header("Location: register.php");
