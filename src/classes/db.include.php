<?php

require_once $_SERVER['DOCUMENT_ROOT']."/classes/config.db.include.php";

/**
 * Récupère un utilisateur à partir de son nom d'utilisateur.
 *
 * @param string $username Nom d'utilisateur à rechercher
 * @return object|null L'utilisateur trouvé ou null en cas d'échec
 */
function SelectUserByUsername(string $username)
{
    try {
        $conn = GetConnexionBd("read");
        $pdoRequete = $conn->prepare("SELECT * FROM user WHERE username=:username");
        $pdoRequete->bindParam(":username", $username, PDO::PARAM_STR);
        $pdoRequete->execute();

        return $pdoRequete->fetch(PDO::FETCH_OBJ);

    } catch (Exception $e) {
        error_log("Exception pdo: selectuserbyusername on a tenté de sélectionner l'utilisateur ".$username.": Client ".$_SERVER['REMOTE_ADDR'].": ".$e->getMessage()."\n\r");
    }
}

/**
 * Crée un utilisateur avec nom d'utilisateur et mot de passe.
 *
 * @param string $username Nom d'utilisateur
 * @param string $password Mot de passe
 * @return void
 */
function CreateUser(string $username, string $password)
{
    try {
        $conn = GetConnexionBd("insert");
        $pdoRequete = $conn->prepare("INSERT INTO user (username, password) VALUES (:username, :password)");
        $pdoRequete->bindParam(":username", $username, PDO::PARAM_STR);
        $pdoRequete->bindParam(":password", $password, PDO::PARAM_STR);
        $pdoRequete->execute();

        error_log("[".date("d/m/o H:i:s e", time())."] ".$username." a été créé\n", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.successful.login.log");

    } catch (Exception $e) {
        error_log("[".date("d/m/o H:i:s e", time())."] Exception pdo: createuser on a tenté de créer l'utilisateur ".$username.": Client ".$_SERVER['REMOTE_ADDR'].": ".$e->getMessage()."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.failed.login.log");
    }
}

/**
 * Supprime une vidéo likée par l'utilisateur.
 *
 * @param string $username Nom d'utilisateur
 * @param string $service Nom du service
 * @param string $id Identifiant de la vidéo
 * @return void
 */
function RemoveLiked(string $username, string $service, string $id)
{
    try {
        $conn = GetConnexionBd("delete");
        $pdoRequete = $conn->prepare("CALL procedure_removeLiked(:username, :service, :id)");
        $pdoRequete->bindParam(":username", $username, PDO::PARAM_STR);
        $pdoRequete->bindParam(":service", $service, PDO::PARAM_STR);
        $pdoRequete->bindParam(":id", $id, PDO::PARAM_STR);
        $pdoRequete->execute();

        error_log("[".date("d/m/o H:i:s e", time())."] ".$username." a unliké ".$service."-".$id.": Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.like.log");

    } catch (Exception $e) {
        error_log("[".date("d/m/o H:i:s e", time())."] Exception pdo: removeliked ".$username." a tenté de unlike ".$service."-".$id.": : Client ".$_SERVER['REMOTE_ADDR'].": ".$e->getMessage()."\n\r");
    }
}

/**
 * Ajoute une vidéo aux likes de l'utilisateur.
 *
 * @param string $username Nom d'utilisateur
 * @param string $service Nom du service
 * @param string $id Identifiant de la vidéo
 * @return void
 */
function AddLiked(string $username, string $service, string $id): void
{
    try {
        $conn = GetConnexionBd("insert");
        $pdoRequete = $conn->prepare("CALL procedure_like(:username, :service, :id)");
        $pdoRequete->bindParam(":username", $username, PDO::PARAM_STR);
        $pdoRequete->bindParam(":service", $service, PDO::PARAM_STR);
        $pdoRequete->bindParam(":id", $id, PDO::PARAM_STR);
        $pdoRequete->execute();

        error_log("[".date("d/m/o H:i:s e", time())."] ".$username." a liké ".$service."-".$id.": Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.like.log");

    } catch (Exception $e) {
        error_log("[".date("d/m/o H:i:s e", time())."] Exception pdo: addliked ".$username." a tenté de like ".$service."-".$id.": Client ".$_SERVER['REMOTE_ADDR'].": ".$e->getMessage()."\n\r");
    }
}

/**
 * Récupère la liste des vidéos likées par un utilisateur.
 *
 * @param string $username Nom d'utilisateur
 * @return array|null Liste des vidéos likées ou null en cas d'échec
 */
function SelectLiked(string $username)
{
    try {
        $conn = GetConnexionBd("read");
        $pdoRequete = $conn->prepare("CALL procedure_selectLiked(:username)");
        $pdoRequete->bindParam(":username", $username, PDO::PARAM_STR);
        $pdoRequete->execute();

        return $pdoRequete->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        error_log("[".date("d/m/o H:i:s e", time())."] Exception pdo: selectliked ".$username." a tenté de selectionner les vidéos likées: Client ".$_SERVER['REMOTE_ADDR'].": ".$e->getMessage()."\n\r");
    }
}

/**
 * Récupère la liste des services disponibles.
 *
 * @return array|null Liste des services ou null en cas d'échec
 */
function SelectServices()
{
    try {
        $conn = GetConnexionBd("read");
        $pdoRequete = $conn->prepare("SELECT * FROM service");
        $pdoRequete->execute();

        return $pdoRequete->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        error_log("[".date("d/m/o H:i:s e", time())."] Exception pdo: selectservices tentative de selectionner les services: Client ".$_SERVER['REMOTE_ADDR'].": ".$e->getMessage()."\n\r");
    }
}

/**
 * Ajoute une vidéo à la base de données.
 *
 * @param int $service Identifiant du service
 * @param string $id Identifiant de la vidéo
 * @return void
 */
function AddVideo(int $service, string $id): void
{
    try {
        $conn = GetConnexionBd("insert");
        $pdoRequete = $conn->prepare("INSERT INTO video (service, id) VALUES (:service, :id)");
        $pdoRequete->bindParam(":service", $service, PDO::PARAM_INT);
        $pdoRequete->bindParam(":id", $id, PDO::PARAM_STR);
        $pdoRequete->execute();

        error_log("[".date("d/m/o H:i:s e", time())."] Ajouté vidéo avec ID ".$id." sur ".$service.": Client ".$_SERVER['REMOTE_ADDR']."\n\r", 3, $_SERVER['DOCUMENT_ROOT']."/../logs/ABCMovies.db.like.log");

    } catch (Exception $e) {
        error_log("[".date("d/m/o H:i:s e", time())."] Exception pdo: addvideo tentative d'ajouter vidéo avec ID ".$id." sur ".$service.": Client ".$_SERVER['REMOTE_ADDR'].": ".$e->getMessage()."\n\r");
    }
}

/**
 * Récupère toutes les vidéos enregistrées.
 *
 * @return array|null Liste des vidéos ou null en cas d'échec
 */
function SelectVideos()
{
    try {
        $conn = GetConnexionBd("read");
        $pdoRequete = $conn->prepare("SELECT * FROM video");
        $pdoRequete->execute();

        return $pdoRequete->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        error_log("[".date("d/m/o H:i:s e", time())."] Exception pdo: selectvideos tentative de sélectionner toutes les vidéos: : Client ".$_SERVER['REMOTE_ADDR'].": ".$e->getMessage()."\n\r");
    }
}

/**
 * Retourne une connexion PDO vers la base de données en fonction du type d'utilisateur.
 *
 * @param string $user_type Type d'accès demandé ('read', 'insert', 'delete')
 * @return PDO|null Connexion PDO ou null si erreur
 */
function GetConnexionBd(string $user_type)
{
    try {
        $conn = "mysql:dbname=".BDSCHEMA.";host=".BDSERVEUR;
        return new PDO($conn, BDUTILISATEUR."_".$user_type, BDPASSWORD);

    } catch (Exception $e) {
        error_log("[".date("d/m/o H:i:s e", time())."] Exception pdo: getconnexionbd tentative de connexion sur ".BDSCHEMA.": Client ".$_SERVER['REMOTE_ADDR'].": ".$e->getMessage()."\n\r");
    }
}
