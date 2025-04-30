<?php
require_once __DIR__."/classes/session.include.php";
if (!ResumeSession("auth")) {
    header("Location: login.php");
    die();
}

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/ABCMovies/common/nav.html");

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
    <main class="center-div">
        <form action="2fa.redirect.php" method="post" class="register-login-container">
            <span class="register-login-title">Validation d'identité</span>
            <span class="register-login-input-title">Code d'authentification :</span>
            <div class="register-login-input-container">
              <input type="text" class="register-login-input" name="code"/>
            </div>
            <button type="submit" class="register-login-button">Valider</button>
        </form>
    </main>
  </body>
</html>