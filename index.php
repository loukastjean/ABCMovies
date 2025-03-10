<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ABCMovies</title>
    <link rel="stylesheet" href="./css/style.css"/>
  </head>
  <body>
    <header>
      <nav>
        <a href="./index.php" class="logo">ABCMovies</a>
        <div class="search-bar">
          <input type="text" class="search-bar-input" placeholder="Recherche..."/>
          <button class="search-icon"></button>
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
      <span class="welcome-message">Bienvenue sur ABCMovies! ($username)!</span>
      <?php
      for ($i=0; $i < 2; $i++) { 
        echo '
      <div class="category-background">
        <span class="category-name">Films ICI TOU.TV</span>
        <div class="category-row">
          ';

          for ($j=0; $j < 4; $j++) { 
            echo '
            <div class="media-group">
              <div class="picture-related">
                <div class="liked">
                  <div class="heart">
                  </div>
                </div>
                <div class="media-text-background">
                </div>
                <span class="lorem-ipsum-dolor"
                      >Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                  Curabitur tempor, turpis vitae ultrices euismod, augue mi
                  pretium est, eget tristique nibh diam ut elit.
                </span>
              </div>
              <span class="lorem-ipsum">Lorem Ipsum Dolor Sit Amet
              </span>
            </div>';
          }

          echo '
          
        </div>
      </div>';
      }
      ?>
    </main>
  </body>
</html>