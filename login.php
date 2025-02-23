<!DOCTYPE html>
<html lang="fr">
<head>
    <script type="text/javascript" src="./js/script.js"></script>
    <link rel="stylesheet" href="./css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connection</title>
</head>
<body>
    <form action="./verifyUserInformations.php" method="post">

        <fieldset class="loginFieldSet">
            <legend>Connectez vous</legend>

            <label for="inputUsernameLogin" class="labelInputUsernameLogin">
                Nom d'utilisateur: 
                <input type="text" name="username" class="inputUsernameLogin" onchange="VerifyUsername(this, 'login')">
            </label>

            <label for="inputPasswordLogin" class="labelInputPasswordLogin">
                Mot de passe: 
                <input type="password" name="password" class="inputPasswordLogin" oninput="VerifyPassword(this)">
            </label>
        </fieldset>

        <input type="submit" value="Connexion" class="submitButtonLogin">

    </form>
</body>
</html>