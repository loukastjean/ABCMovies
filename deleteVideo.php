<?php

$video_name = $GET["id"];

$command = "/home/stjeanh25techinf/ABCMovies/delete.py $video_name &";

shell_exec($command);