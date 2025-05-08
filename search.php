<?php
declare(strict_types=1);
require_once __DIR__."/classes/session.include.php";

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/ABCMovies/common/nav.html");

ResumeSession("logged");

$query = "";

if (isset($_GET["q"])) {
    $query .= $_GET["q"];
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
    <?php
    echo $header;
?>
    <main>
      <div class="category-background search-background">

      </div>
    </main>
  </body>
</html>

<script>

if ("<?php echo $query ?>" != "") {
    document.getElementById("search-bar").value = "<?php echo $query ?>"
}


<?php


if ($query == "") {
    $query = "*";
}

echo "placeSearchShows(\"$query\", \"toutv\")";
?>


</script>