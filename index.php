<?php
require_once __DIR__."/classes/session.include.php";

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/ABCMovies/common/nav.html");
$username = "";

if (ResumeSession("logged")) {
    $username = " ".$_SESSION["username"];
}
?>



<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ABCMovies</title>
    <link rel="stylesheet" href="./css/style.css"/>
    <script type="text/javascript" src="./js/script.js"></script>

  </head>
  <body>
    <?php echo $header ?>
    <main>
      <?php echo "<span class=\"welcome-message\">Bienvenue sur ABCMovies$username!</span>"?>
    </main>
  </body>
</html>

<script>

placeRecommendedVideos("toutv", "film");
placeRecommendedVideos("toutv", "documentaire");
placeRecommendedVideos("toutv", "serie");

</script>