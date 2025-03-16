<?php



$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/ABCMovies/common/nav.html");





?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <link href="./css/video/video-js.min.css" rel="stylesheet">
    <script src="./js/video/video.min.js"></script>
    <script src="./js/hls/hls.min.js"></script>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ABCMovies</title>
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



let player = videojs("my-player", {
    controls: true,
    autoplay: true,
    preload: "auto",
});
let hls;

var videoSrc = "https://d2zihajmogu5jn.cloudfront.net/bipbop-advanced/bipbop_16x9_variant.m3u8" // 'http://127.0.0.1/ABCMovies/videos/master.m3u8'; //lien bidon vers la video

function createPlayer() {

    hls = new Hls({
        captionsTextTrack1Label: "French",
        captionsTextTrack1LanguageCode: "fr",
        captionsTextTrack2Label: "English",
        captionsTextTrack2LanguageCode: "en",
    })

    player.src({src: videoSrc})

    loadPlaylist();
}

let lastPlaylistContent = '';

async function loadPlaylist() {

    let r = await fetch(videoSrc + "?t=" + Date.now());

        let newContent = await r.text();

        if (newContent !== lastPlaylistContent) {
            lastPlaylistContent = newContent;

            let currentTime = player.currentTime();
            hls.loadSource(videoSrc);

            hls.on(Hls.Events.MANIFEST_PARSED, function(event, data) {
                console.log("Trouve " + data.levels.length + " qualites");
            });

            player.ready(() => {
                player.currentTime(currentTime);
            });
        }
}

setInterval(loadPlaylist, 2000);

createPlayer();


</script>