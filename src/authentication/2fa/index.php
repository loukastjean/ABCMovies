<?php
require_once $_SERVER['DOCUMENT_ROOT']."/classes/session.include.php";

if (!ResumeSession("auth")) {
    header("Location: ../login/index.php");
    die();
}

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/common/nav.html");

?>


<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ABCMovies</title>
    <link rel="stylesheet" href="/css/style.css"/>
    <script type="text/javascript" src="/js/script.js"></script>

  </head>
  <body>
    <?php echo $header ?>
    <main class="center-div">
        <form action="index.redirect.php" method="post" class="register-login-container">
            <span class="register-login-title">Validation d'identité</span>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] === "wrongcode") {
                    echo "<span class=\"register-login-error\">Le code entré est erroné</span>";
                }
                if ($_GET["error"] === "nocode") {
                    echo "<span class=\"register-login-error\">Le code entré est vide</span>";
                }
            }
            ?>
            <span class="register-login-input-title">Code d'authentification :</span>
            <div class="register-login-input-container">
              <input type="text" class="register-login-input" name="code"/>
            </div>
            <button type="submit" class="register-login-button">Valider</button>
        </form>
    </main>
  </body>
</html>