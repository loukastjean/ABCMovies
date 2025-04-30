<?php

/**
 * Envoie un e-mail à un destinataire spécifique.
 *
 * @param string $email   Adresse e-mail du destinataire
 * @param string $subject Sujet de l'e-mail
 * @param string $message Contenu de l'e-mail
 */
function SendEmail($email, $subject, $message)
{
    // Définition des en-têtes de l'e-mail
    $headers = [
        "From" => "stjeanh25techinf@st-jean.h25.techinfo420.ca",
        "X-Mailer" => "PHP/" . phpversion()
    ];

    mail($email, $subject, $message, implode("\r\n", array_map(
        fn($key, $value) => "$key: $value",
        array_keys($headers),
        $headers
    )));
}

/**
 * Génère un code de vérification aléatoire, le stocke dans la session, et l’envoie par e-mail.
 *
 * @param string $email Adresse e-mail du destinataire
 */
function SendVerificationCode($email)
{
    $code = rand(100000, 999999); // Génère un code à 6 chiffres

    session_start();
    $_SESSION["code"] = $code; // Stocke le code dans la session

    $subject = "Code de vérification ABCMovies";
    $message = "Votre code de sécurité ABCMovies est: ".$code.". Attention, ce code est très important! Ne le divulguez à personne.";

    SendEmail($email, $subject, $message);
}
