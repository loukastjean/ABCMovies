<?php

require_once __DIR__."/classes/db.include.php";


if (!empty($_POST["username"]) && !empty($_POST["password"])) {
    $username = filter_input(INPUT_POST, "username", FILTER_VALIDATE_EMAIL); // Futur: FILTER_DEFAULT
    $password = filter_input(INPUT_POST, "password", FILTER_DEFAULT);

    //Faire une sélect sur la bd pour trouver le username
    //Le username existe pour l"exemple
    //Il faut récupérer le mot de passe associé à ce compte.

    $user = SelectUserByUsername($username);

    if ($username === $user->username && password_verify($password, $user->password)) {
        //L"authentification est correcte
        include_once __DIR__."/classes/session.include.php";

        CreateSession("auth");

        $_SESSION["username"] = $username;

        // Juste quand c'est un email, donc TEMP
        include_once __DIR__."/classes/email.include.php";
        SendVerificationCode($username);

        header("Location: 2fa.php");
        die();
    }
}

header("Location: login.php");
