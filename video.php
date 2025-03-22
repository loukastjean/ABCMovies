<?php
declare(strict_types=1);
include_once $_SERVER['DOCUMENT_ROOT'].'/ABCMovies/services/common/episode.php';

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/ABCMovies/common/nav.html");

$request_id = $_GET["id"];

?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <link href="./css/video/video-js.min.css" rel="stylesheet">
    <script src="./js/video/video.min.js"></script>
    <script src="./js/hls/hls.min.js"></script>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>TEST</title>
    <link rel="stylesheet" href="./css/style.css"/>
    <script src="./js/script.js"></script>
    <script src="./js/video.js"></script>

  </head>
  <body>
    <?php echo $header ?>
    <main>
      <?php echo "<video id=\"my-player\" class=\"video-js\"></video>" ?>
    </main>
  </body>
</html>


<script>

let player = videojs("my-player", {
    controls: true,
    autoplay: true,
    preload: "auto",
    liveui: true,
    liveTracker: {
        trackingThreshold: 0,
        liveTolerance: 0,
    }
});

let hls;

let videoSrc;

document.addEventListener("DOMContentLoaded", function() {
    loadVideo("<?php echo $request_id ?>");
})

async function loadVideo(request_id) {

    console.log("Fetching: " + "https://st-jean.h25.techinfo420.ca/ABCMovies/loadVideo.php?id=" + request_id)

    let r = await fetch("https://st-jean.h25.techinfo420.ca/ABCMovies/loadVideo.php?id=" + request_id);

    videoSrc = await r.text();

    console.log("The video source is " + videoSrc);

    loadPlaylist();
}

function createPlayer() {

    hls = new Hls({
        captionsTextTrack1Label: "French",
        captionsTextTrack1LanguageCode: "fr",
        captionsTextTrack2Label: "English",
        captionsTextTrack2LanguageCode: "en",
    })

    player.src({
        src: videoSrc
    })
}


async function removeVideo() {
    await fetch("https://st-jean.h25.techinfo420.ca/ABCMovies/deleteVideo.php?id=" + request_id);
}


let lastPlaylistContent = '';

async function loadPlaylist() {

    if (typeof videoSrc !== "string") {
        console.log(videoSrc);
        console.log("The type of videosrc is not a string");
        return;
    }

    console.log("Videosrc is a string");

    //let r = await fetch(videoSrc + "?t=" + Date.now());

    let r = await fetch(videoSrc);

    let newContent = await r.text();

    if (!newContent.startsWith("#")) {
        return;
    }

    console.log("The video starts with #, so a playlist");

    if (!hls) {
        createPlayer();
    }



    console.log(newContent);

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
</script>