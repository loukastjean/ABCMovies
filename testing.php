<?php
declare(strict_types=1);
include $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/episode.php';

$request_id = $_GET["id"];
$request_id_array = explode("-", $request_id);

$service = $request_id_array[0];
$id = $request_id_array[1];

$episode = new Episode();
$episode->id = $id;
$episode->name = $request_id;

include $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/'.$service.'/info.php';
include $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/'.$service.'/download.php';

$info = new Info();
$download = new Download();

$info->Episode($episode);

$stream_url = $download->Episode($episode);

$stream_url = "https://st-jean.h25.techinfo420.ca/ABCMovies/video/$request_id/master.m3u8";

echo $stream_url;
?>