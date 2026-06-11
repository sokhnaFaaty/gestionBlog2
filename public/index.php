<?
if(session_status() == PHP_SESSION_NONE){session_start();}

define("WEBROOT","https://nexuuusdev.alwaysdata.net/");
define("ROOT", dirname(__DIR__) . "/");
// define("ROOT", str_replace("public","",$_SERVER['DOCUMENT_ROOT']));

require_once ROOT."config/helpers.php";
require_once ROOT."config/validators.php";
//si le fichier env.php existe(sur alwaysData)
if(file_exists(ROOT."env.php")){
    require_once ROOT . "env.php";

}
//si je suis en local
require_once ROOT."env.dev.php";
// require_once ROOT."view/partials/header.php";

require_once("../routes/web/router.php");

