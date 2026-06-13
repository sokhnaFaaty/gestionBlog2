<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(session_status() == PHP_SESSION_NONE){session_start();}
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isHttps  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || ($_SERVER['SERVER_PORT'] ?? 80) == 443;
$scheme   = $isHttps ? 'https' : 'http';
define("WEBROOT", $scheme . "://" . $host . "/");
//chemin absolu vers la racine du projet (dossier parent de public/)
define("ROOT", dirname(__DIR__) . "/");

require_once ROOT."config/helpers.php";
require_once ROOT."config/validators.php";
//si le fichier env.php existe(sur alwaysData)
if(file_exists(ROOT."env.php")){
    require_once ROOT . "env.php";
}else{
    //si je suis en local
require_once ROOT."env.dev.php";

}
// require_once ROOT."view/partials/header.php";

require_once("../routes/web/router.php");