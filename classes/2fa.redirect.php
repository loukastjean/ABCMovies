<?php
require_once __DIR__."/session.include.php";
ResumeSession("auth");

Verify2FASession();

if (!empty($_POST["code"])) 
{
    $code = filter_input(INPUT_POST,"code",FILTER_DEFAULT);

    if ($code == $_SESSION["code"]) {
        $username = $_SESSION["username"];

        DeleteSession(session_name());
        CreateSession("logged");

        $_SESSION["username"] = $username;
        header("Location: index.php?YES");
        die();
    }
    else {
        header("Location: 2fa.php");
        die();
    }
}


function Verify2FASession() {
    if (session_status() == PHP_SESSION_ACTIVE) {
        if (!VerifySession() || !isset($_SESSION["code"])) {
            header("Location: 2fa.php");
            die();
        }
    }
}