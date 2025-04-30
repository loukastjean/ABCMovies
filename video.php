<?php
declare(strict_types=1);

require_once __DIR__."/classes/session.include.php";

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/ABCMovies/common/nav.html");

ResumeSession("logged");

$service = $_GET["service"];
$id = $_GET["id"];


?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <link href="./css/video/video-js.min.css" rel="stylesheet">
    <script src="./js/video/video.min.js"></script>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title> | ABCMovies</title>
    <link rel="stylesheet" href="./css/style.css"/>
    <script src="./js/script.js"></script>

  </head>
  <body>
    <?php echo $header ?>
    <main>
      <div class="flex-center-parent">
        <video id="my-player" class="video-js"></video>
      </div>
    </main>
  </body>
</html>


<script>


// Initialise le videojs
let player = videojs("my-player", {
    controls: true,
    autoplay: true,
    preload: "auto",
});

let myPlayerEl = document.getElementById("my-player");
let hasSource = false;

// Récupère les paramètres PHP côté client
let service = "<?php echo $service; ?>";
let id = "<?php echo $id; ?>";

// Vérifie périodiquement si la vidéo est prête à être lue
async function isVideoDownloaded() {
    if (await videoSrc && !hasSource) {
        myPlayerEl.setAttribute("style", `background: #000`);
        hasSource = true;

        // Assigne la source vidéo au lecteur et démarre la lecture
        player.src({
          src: await videoSrc,
          type: "video/mp4",
        })
        player.load();
    }
}

// Une fois que la page est prête, démarre la récupération de la vidéo
document.addEventListener("DOMContentLoaded", function() {
    videoSrc = fetchVideo(service, id); // Charge une vidéo puis retourne le URL
})

// Appelle `isVideoDownloaded` toutes les 4 secondes jusqu’à ce que la vidéo soit chargée
setInterval(isVideoDownloaded, 4000, service, id);
</script>