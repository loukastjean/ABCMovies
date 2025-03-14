<?php
include $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/episode.php';
include $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/radiocan/info.php';

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/ABCMovies/common/nav.html");

$episode = new Episode();
$episode->id = "10325510";

$info = new Info();
$info->Episode($episode);

?>



<!DOCTYPE html>
<html lang="fr">
  <head>
    <link href="https://unpkg.com/video.js/dist/video-js.min.css" rel="stylesheet">
    <script src="https://unpkg.com/video.js/dist/video.min.js"></script>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ABCMovies</title>
    <link rel="stylesheet" href="./css/style.css"/>
    <script src="./js/script.js"></script>

  </head>
  <body>
    <?php echo $header ?>
    <main>
      <span class="welcome-message">Bienvenue sur ABCMovies! ($username)!</span>
      <?php
      for ($i=0; $i < 2; $i++) { 
        echo '
        <span class="category-name">Films ICI TOU.TV</span>
        <div class="category-background">';

        for ($j=0; $j < 4; $j++) {
          echo '
          <div class="media-group">
            <div class="picture-related" id="'.$episode->service."-".$episode->id.'" onclick="playVideo(this)" style="background: url(\''.$episode->image.'\')">
              <div class="liked">
                <div class="heart">
                </div>
              </div>
              <div class="media-text-background"></div>
              <span class="lorem-ipsum-dolor">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                Curabitur tempor, turpis vitae ultrices euismod, augue mi
                pretium est, eget tristique nibh diam ut elit.
              </span>
            </div>
            <span class="lorem-ipsum">Lorem Ipsum Dolor Sit Amet
            </span>
          </div>';
        }

        echo '</div>';
      }
      ?>
    </main>
  </body>
</html>