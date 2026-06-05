<?php
define("WEBROOT","http://localhost:8002/");
define("ROOT", str_replace("public","",$_SERVER['DOCUMENT_ROOT']));
if(session_status() == PHP_SESSION_NONE){session_start();}

require_once ROOT."config/helpers.php";
require_once ROOT."config/validators.php";
// require_once ROOT."view/partials/header.php";

require_once("../routes/web/router.php");

