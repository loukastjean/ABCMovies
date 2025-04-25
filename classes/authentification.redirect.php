<?php
require_once __DIR__."/db.include.php";


if (!empty($_POST["username"]) && !empty($_POST["password"])) 
{
    $username = filter_input(INPUT_POST,"username",FILTER_VALIDATE_EMAIL); // Futur: FILTER_DEFAULT
    $password = filter_input(INPUT_POST,"password",FILTER_DEFAULT);

    //Faire une sélect sur la bd pour trouver le username
    //Le username existe pour l"exemple
    //Il faut récupérer le mot de passe associé à ce compte.

    $user = SelectUserByUsername($username);

    if (!$user) {
        CreateUser($username, password_hash($password, PASSWORD_DEFAULT));
    }

    $user = SelectUserByUsername($username);

    if ($username === $user->username && password_verify($password, $user->password))
    {
        //L"authentification est correcte
        require_once __DIR__."/session.include.php";

        CreateSession("auth");

        $_SESSION["username"] = $username;

        // Juste quand c'est un email, donc TEMP
        require_once __DIR__."/email.include.php";
        SendVerificationCode($username);

        header("Location: 2fa.php");
        die();
    }
}

header("Location: index.php");