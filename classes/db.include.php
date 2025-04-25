<?php
require_once(__DIR__."/config.db.include.php");

function SelectUserByUsername(string $username)
{
    try {
    
        $conn = GetConnexionBd("read");
        $pdoRequete = $conn->prepare("SELECT * FROM users WHERE username=:username");

        $pdoRequete->bindParam(":username",$username,PDO::PARAM_STR);
    
        $pdoRequete->execute();

        return $pdoRequete->fetch(PDO::FETCH_OBJ);

    } catch (Exception $e) {
        error_log("Exception pdo: ".$e->getMessage());
    }
}

function CreateUser(string $username, string $password)
{
    try {
        $conn = GetConnexionBd("insert");
        $pdoRequete = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");

        $pdoRequete->bindParam(":username",$username,PDO::PARAM_STR);
        $pdoRequete->bindParam(":password",$password,PDO::PARAM_STR);
    
        $pdoRequete->execute();
    } catch (Exception $e) {
        error_log("Exception pdo: ".$e->getMessage());
    }

}





/** Retourne une connexion avec le driver Mariabd sur la bd. */
function GetConnexionBd(string $user_type)
{
    try {
        $conn = "mysql:dbname=".BDSCHEMA.";host=".BDSERVEUR;

        return new PDO($conn, BDUTILISATEUR."_".$user_type, BDPASSWORD);

    } catch (Exception $e) {
        error_log("Exception pdo: ".$e->getMessage());
    }
}