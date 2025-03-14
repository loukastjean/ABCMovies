<?php
declare(strict_types=1);
include $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/episode.php';

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/ABCMovies/common/nav.html");

$request_id = explode("-", $_GET["id"]);

$service = $request_id[0];
$id = $request_id[1];

$episode = new Episode();
$episode->id = $id;

include $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/'.$service.'/info.php';
include $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/'.$service.'/download.php';

$info = new Info();
$download = new Downloads();

$info->Episode($episode);
$stream_url = $download->Episode($episode->id);


?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <link href="https://unpkg.com/video.js/dist/video-js.min.css" rel="stylesheet">
    <script src="https://unpkg.com/video.js/dist/video.min.js"></script>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo $episode->title ?></title>
    <link rel="stylesheet" href="./css/style.css"/>
    <script src="./js/script.js"></script>

  </head>
  <body>
    <?php echo $header ?>
    <main>
      <video id="video-player" class="video-js vjs-lime" controls data-setup="{}">
      <?php
        echo '<source src="'.$stream_url.'" type="application/x-mpegURL">'
      ?>
      </video>
    </main>
  </body>
</html>