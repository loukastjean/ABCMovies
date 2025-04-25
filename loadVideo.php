<?php
declare(strict_types=1);
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/episode.php';

$service = $_GET["service"];
$id = $_GET["id"];

include $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/'.$service.'/info.php';
include $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/'.$service.'/download.php';

$episode = [
    "id" => $id,
    "service" => $service,
];

$info->Episode($episode);

$stream_url = $download->Episode($episode);

$stream_url = "https://st-jean.h25.techinfo420.ca/ABCMovies/video/$service-$id.mp4";

echo $stream_url;
