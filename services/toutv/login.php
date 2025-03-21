<?php



function get_tokens(): array {
    $output = shell_exec("/home/stjeanh25techinf/ABCMovies/.venv/bin/python /home/stjeanh25techinf/ABCMovies/login.py .");

    return json_decode($output, true);
}
