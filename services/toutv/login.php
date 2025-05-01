<?php

header('Content-type: application/json');

$availabilities = ["free", "paid"];
$availability;

// Vérifie si ?availability=paid, donc vidéo a besoin d'un compte payant
if (isset($_GET["availability"])) {
    if (in_array($_GET["availability"], $availabilities)) {
        $availability = $_GET["availability"];
    } else {
        $availability = $availabilities[0];
    }
}

// Construit la commande pour appeler le script Python avec le paramètre choisi
$python_venv_path = "/home/stjeanh25techinf/ABCMovies/.venv/bin/python";
$script_path = "/home/stjeanh25techinf/ABCMovies/login.py";

// Exécute le script Python et capture sa sortie
$tokens = shell_exec("$python_venv_path $script_path $availability");

// Retourne la sortie du script, nettoyée
echo trim($tokens);
