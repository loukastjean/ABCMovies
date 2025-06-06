<?php
require_once $_SERVER['DOCUMENT_ROOT']."/classes/session.include.php";

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/common/nav.html");

ResumeSession("logged");
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ABCMovies</title>
    <link rel="stylesheet" href="/css/style.css" />
  </head>
  <body>
    <?php echo $header ?>
    <main class="center-div">
      <form action="login.redirect.php" method="post" class="register-login-container">
        <span class="register-login-title">Connectez vous à votre compte</span>
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] === "wrong") {
                echo "<span class=\"register-login-error\">Il y a des erreurs dans les informations entrées</span>";
            }
        } 
        ?>
        <span class="register-login-input-title">Nom d’utilisateur :</span>
        <div class="register-login-input-container">
          <input type="text" class="register-login-input" id="username-input" name="username"/>
        </div>
        <span class="register-login-input-title">Mot de passe :</span>
        <div class="register-login-input-container">
          <input type="password" class="register-login-input" id="password-input" name="password"/>
        </div>
        <button type="submit" class="register-login-button">Se connecter</button>
      </form>
    </main>
  </body>
</html>