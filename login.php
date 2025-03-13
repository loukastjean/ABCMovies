<?php

$header = file_get_contents($_SERVER['DOCUMENT_ROOT']."/ABCMovies/common/nav.html");

?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ABCMovies</title>
    <link rel="stylesheet" href="./css/style.css" />
  </head>
  <body>
    <?php echo $header ?>
    <main>
      <div class="flex-row-a">
        <div class="register-background">
          <div class="flex-column">
            <span class="login-account-heading">Connectez vous à votre compte</span>
            <span class="username-email-input">Nom d’utilisateur / Courriel</span>
            <div class="username-email-bar">
              <input type="email" class="username-email-input" />
            </div>
            <span class="password-input">Mot de passe</span>
            <div class="password-bar">
              <input class="password-input" />
            </div>
            <button class="connexion">
              <span class="connexion-text">Se connecter</span>
            </button>
          </div>
        </div>
      </div>
      </div>
    </main>
  </body>
</html>