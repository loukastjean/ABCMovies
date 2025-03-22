<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/show.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/episode.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/toutv/info.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/toutv/recommended.php';

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/ABCMovies/common/nav.html");



$shows = get_movies();

$info = new Info();

?>



<!DOCTYPE html>
<html lang="fr">
  <head>
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
      for ($i=0; $i < sizeof($shows); $i++) { 
        echo '
        <span class="category-name">Films ICI TOU.TV</span>
        <div class="category-background">';
        foreach ($shows as $_ => $show) {
          $info->Show($show);
        foreach ($show->seasons as $_ => $season) {
          foreach ($season->episodes as $_ => $episode) {
            $info->Episode($episode);
          $id = $episode->service."-".$episode->id;
          echo '
          <div class="media-group">
            <a href="https://st-jean.h25.techinfo420.ca/ABCMovies/video.php?id='.$id.'" class="picture-related" id="'.$id.'" style="background: url(\''.str_replace("_Size_","300", $episode->image).'\')">
              <div class="liked">
                <div class="heart">
                </div>
              </div>
              <div class="media-text-background"></div>
              <span class="lorem-ipsum-dolor">
                '.$episode->description.'
              </span>
            </a>
            <span class="lorem-ipsum">'.$episode->title.'
            </span>
          </div>';
          }
        }
      }

        echo '</div>';
      }
      ?>
    </main>
  </body>
</html>