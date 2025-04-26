<?php
require_once(__DIR__."/config.db.include.php");

function SelectUserByUsername(string $username)
{
    try {
    
        $conn = GetConnexionBd("read");
        $pdoRequete = $conn->prepare("SELECT * FROM user WHERE username=:username");

        $pdoRequete->bindParam(":username",$username,PDO::PARAM_STR);
    
        $pdoRequete->execute();

        return $pdoRequete->fetch(PDO::FETCH_OBJ);

    } catch (Exception $e) {
        error_log("Exception pdo en Select user by username: ".$e->getMessage());
    }
}

function CreateUser(string $username, string $password)
{
    try {
        $conn = GetConnexionBd("insert");
        $pdoRequete = $conn->prepare("INSERT INTO user (username, password) VALUES (:username, :password)");

        $pdoRequete->bindParam(":username",$username,PDO::PARAM_STR);
        $pdoRequete->bindParam(":password",$password,PDO::PARAM_STR);
    
        $pdoRequete->execute();
    } catch (Exception $e) {
        error_log("Exception pdo en Create User: ".$e->getMessage());
    }
}

function RemoveLiked(string $username, string $service, string $id)
{
    try {
        $conn = GetConnexionBd("delete");
        $pdoRequete = $conn->prepare("CALL procedure_removeLiked(:username, :service, :id)");

        $pdoRequete->bindParam(":username",$username,PDO::PARAM_STR);
        $pdoRequete->bindParam(":service",$service,PDO::PARAM_STR);
        $pdoRequete->bindParam(":id",$id,PDO::PARAM_STR);
    
        $pdoRequete->execute();
    } catch (Exception $e) {
        error_log("Exception pdo en Remove Liked: ".$e->getMessage());
    }
}

function AddLiked(string $username, string $service, string $id)
{
    try {
        $conn = GetConnexionBd("insert");
        $pdoRequete = $conn->prepare("CALL procedure_like(:username, :service, :id)");

        $pdoRequete->bindParam(":username",$username,PDO::PARAM_STR);
        $pdoRequete->bindParam(":service",$service,PDO::PARAM_STR);
        $pdoRequete->bindParam(":id",$id,PDO::PARAM_STR);
    
        $pdoRequete->execute();
    } catch (Exception $e) {
        error_log("Exception pdo en Add Liked: ".$e->getMessage());
    }
}


function SelectLiked(string $username)
{
    try {
        $conn = GetConnexionBd("read");
        $pdoRequete = $conn->prepare("CALL procedure_selectLiked(:username)");

        $pdoRequete->bindParam(":username",$username,PDO::PARAM_STR);
    
        $pdoRequete->execute();

        return $pdoRequete->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Exception pdo en Select Liked: ".$e->getMessage());
    }
}

function SelectServices()
{
    try {
        $conn = GetConnexionBd("read");
        $pdoRequete = $conn->prepare("SELECT * FROM service");
    
        $pdoRequete->execute();

        return $pdoRequete->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Exception pdo en Select Liked: ".$e->getMessage());
    }
}


function AddVideo(int $service, string $id)
{
    try {
        $conn = GetConnexionBd("insert");
        $pdoRequete = $conn->prepare("INSERT INTO video (service, id) VALUES (:service, :id)");

        $pdoRequete->bindParam(":service",$service,PDO::PARAM_INT);
        $pdoRequete->bindParam(":id",$id,PDO::PARAM_STR);
    
        $pdoRequete->execute();
    } catch (Exception $e) {
        error_log("Exception pdo en AddVideo: ".$e->getMessage());
    }
}

function SelectVideos()
{
    try {
        $conn = GetConnexionBd("read");
        $pdoRequete = $conn->prepare("SELECT * FROM video");
    
        $pdoRequete->execute();

        return $pdoRequete->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        error_log("Exception pdo en SelectVideos: ".$e->getMessage());
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