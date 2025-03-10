<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ABCMovies</title>
    <link rel="stylesheet" href="./css/style.css" />
  </head>
  <body>
    <header>
      <nav>
        <a href="./index.php" class="logo">ABCMovies</a>
        <div class="search-bar">
          <button class="search-icon"></button>
          <input type="text" class="search-bar-input" placeholder="Recherche..."/>
        </div>
        <div class="regroup-register-login">
          <a href="./register.php">
            <button class="create-account">Créer un compte</button>
          </a>
          <a href="./login.php">
            <button class="login">Connexion</button>
          </a>
        </div>
      </nav>
    </header>
    <main>
      <div class="flex-row-a">
        <div class="register-background">
          <div class="flex-column">
            <span class="create-account-heading">Création d’un compte</span>
            <span class="username-input">Nom d’utilisateur</span>
            <div class="username-bar">
              <input type="email" class="username-input" />
            </div>
            <span class="email-input">Courriel</span>
            <div class="email-bar">
              <input type="email" class="email-input" />
            </div>
            <span class="password-input">Mot de passe</span>
            <div class="password-bar">
              <input class="password-input" />
            </div>
            <button class="enregistrer">
              <span class="enregistrer-text">Enregistrer</span>
            </button>
          </div>
        </div>
      </div>
      </div>
    </main>
  </body>
</html>