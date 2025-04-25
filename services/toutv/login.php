<?php
header('Content-type: application/json');

$tokens = shell_exec("/home/stjeanh25techinf/ABCMovies/.venv/bin/python /home/stjeanh25techinf/ABCMovies/login.py .");

echo trim($tokens);

