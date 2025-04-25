<?php
require_once __DIR__."/classes/session.include.php";
ResumeSession("auth");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="2fa.redirect.php" method="post">
        <input type="text" name="code">
        <input type="submit" value="Verify code">
    </form>
</body>
</html>