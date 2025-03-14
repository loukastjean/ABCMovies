<?php
declare(strict_types=1);
include $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/episode.php';

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/ABCMovies/common/nav.html");

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


?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <link href="//vjs.zencdn.net/8.23.0/video-js.min.css" rel="stylesheet">
    <script src="//vjs.zencdn.net/8.23.0/video.min.js"></script>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo $episode->title ?></title>
    <link rel="stylesheet" href="./css/style.css"/>
    <script src="./js/script.js"></script>

  </head>
  <body>
    <?php echo $header ?>
    <main>
      
      <?php
        echo '
        <video id="video-player" class="video-js" controls data-setup="{}" preload="auto" poster="'.$episode->image.'">
          <source src="'.$stream_url.'" type="application/x-mpegURL">
        '
      ?>
      </video>
    </main>
  </body>
</html>