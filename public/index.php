<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(session_status() == PHP_SESSION_NONE){session_start();}
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isHttps  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || ($_SERVER['SERVER_PORT'] ?? 80) == 443;
$scheme   = $isHttps ? 'https' : 'http';
define("WEBROOT", $scheme . "://" . $host . "/");
define("ROOT", dirname(__DIR__) . "/");

require_once ROOT."config/helpers.php";
require_once ROOT."config/validators.php";

if(file_exists(ROOT."env.php")){
    require_once ROOT . "env.php";
}else{
    require_once ROOT."env.dev.php";
}

// Parse clean URLs : /controller/action → $_GET['controller'] + $_GET['action']
$_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($_uri !== '' && empty($_GET['controller'])) {
    $_parts = explode('/', $_uri, 2);
    if (!empty($_parts[0])) {
        $_GET['controller']     = $_parts[0];
        $_REQUEST['controller'] = $_parts[0];
    }
    if (!empty($_parts[1])) {
        $_GET['action']     = $_parts[1];
        $_REQUEST['action'] = $_parts[1];
    }
}
unset($_uri, $_parts);

require_once(ROOT . "routes/web/router.php");
