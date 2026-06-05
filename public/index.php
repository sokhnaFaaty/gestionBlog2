<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
define("WEBROOT", $protocol . "://" . $_SERVER['HTTP_HOST'] . "/");
define("ROOT", str_replace("public","",$_SERVER['DOCUMENT_ROOT']));
if(session_status() == PHP_SESSION_NONE){session_start();}

require_once ROOT."config/helpers.php";
require_once ROOT."config/validators.php";
// require_once ROOT."view/partials/header.php";

require_once("../routes/web/router.php");

