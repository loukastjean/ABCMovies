<?php

header('Content-type: application/json');

$availability = "free";

if (isset($_GET["availability"])) {
    $availability = $_GET["availability"];
}

$tokens = shell_exec("/home/stjeanh25techinf/ABCMovies/.venv/bin/python /home/stjeanh25techinf/ABCMovies/login.py ".$availability);

echo trim($tokens);
