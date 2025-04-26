<?php
require_once __DIR__."/classes/session.include.php";
if (!ResumeSession("logged")) {
    die();
}

if (!empty($_POST["service"]) && !empty($_POST["id"]))
{
    require_once(__DIR__."/classes/db.include.php");

    $service = filter_input(INPUT_POST,"service",FILTER_DEFAULT); // Futur: FILTER_DEFAULT
    $id = filter_input(INPUT_POST,"id",FILTER_DEFAULT);

    //Faire une sélect sur la bd pour trouver le username
    //Le username existe pour l"exemple
    //Il faut récupérer le mot de passe associé à ce compte.

    $user = SelectUserByUsername($_SESSION["username"]);

    if ($username === $user->username)
    {

        die();
    }
}
