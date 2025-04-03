<?php
declare(strict_types=1);

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/ABCMovies/common/nav.html");

$service = $_GET["service"];
$id = $_GET["id"];


?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <link href="./css/video/video-js.min.css" rel="stylesheet">
    <script src="./js/video/video.min.js"></script>
    <script src="./js/hls/hls.min.js"></script>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title> | ABCMovies</title>
    <link rel="stylesheet" href="./css/style.css"/>
    <script src="./js/script.js"></script>

  </head>
  <body>
    <?php echo $header ?>
    <main>
      <video id="my-player" class="video-js"></video>
    </main>
  </body>
</html>


<script>

async function a() {
    playlistPath = await fetchVideo("<?php echo $service ?>", "<?php echo $id ?>");

    console.log(playlistPath)
}

a();


</script>
