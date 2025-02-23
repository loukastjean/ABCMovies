<?php

$users = array(1=>array("username"=>"louka","password"=>"12345"),2=>array("username"=>"miguel","password"=>"wow"));

$homePage = "https://st-jean.h25.techinfo420.ca/";
$loginPage = "https://st-jean.h25.techinfo420.ca/login.php";

$username = $_POST["username"];
$password = $_POST["password"];

foreach ($users as $key => $value) {
    if ($username == $value["username"] && $password == $value["password"]) {
        header("Location: ".$homePage);
        die();
    }
}

header("Location: ".$loginPage."?userFailedLogin=true");