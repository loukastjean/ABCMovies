<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/episode.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/toutv/info.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/toutv/download.php';

$episode = new Episode();
$episode->id = "961493";

$info = new Info();
$info->Episode($episode);

