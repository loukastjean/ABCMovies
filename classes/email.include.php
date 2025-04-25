<?php

function SendEmail($email, $subject, $message) {

    $headers = ["From" => "stjeanh25techinf@st-jean.h25.techinfo420.ca", "X-Mailer" => "PHP/".phpversion()];

    mail($email, $subject, $message, $headers);
}

function SendVerificationCode($email) {
    $code = rand(100000,999999);

    session_start();

    $_SESSION["code"] = $code;

    $subject = "Code de verification";
    $message = "Votre code de securite ABCMovies est: ".$code.". Attention, ce code est tres important! Ne le divulgez a personne.";

    SendEmail($email, $subject, $message);
}