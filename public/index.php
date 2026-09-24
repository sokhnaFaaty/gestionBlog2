<?php
error_reporting(E_ALL);

$host    = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
           || ($_SERVER['SERVER_PORT'] ?? 80) == 443;

// Derrière un proxy/CDN (ex. Cloudflare), le vrai protocole est annoncé par cette en-tête.
if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $proto = strtolower(trim(explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO'])[0]));
    if ($proto === 'https') {
        $isHttps = true;
    } elseif ($proto === 'http') {
        $isHttps = false;
    }
}

$scheme = $isHttps ? 'https' : 'http';

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure'   => $isHttps,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Base de l'application : on dérive le sous-dossier depuis SCRIPT_NAME
// pour que l'app fonctionne aussi bien en racine de domaine qu'en sous-dossier.
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
if ($base === '' || $base === '/') { $base = ''; }
define("BASE_URL", $base);

define("WEBROOT", rtrim($scheme . "://" . $host . $base, "/") . "/");
define("ROOT", dirname(__DIR__) . "/");

require_once ROOT."config/helpers.php";
require_once ROOT."config/validators.php";

if(file_exists(ROOT."env.php")){
    require_once ROOT . "env.php";
    // Prod : on journalise les erreurs, on ne les affiche jamais au visiteur.
    ini_set('display_errors', '0');
}else{
    require_once ROOT."env.dev.php";
    ini_set('display_errors', '1');
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

// ── Protection CSRF : toute requête POST doit porter un jeton valide ──
if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST") {
    $token = $_POST["csrf_token"] ?? ($_SERVER["HTTP_X_CSRF_TOKEN"] ?? "");
    if (!is_string($token) || !verifierCsrf($token)) {
        http_response_code(419);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["success" => false, "message" => "Session expirée. Rechargez la page et réessayez."]);
        exit();
    }
}

require_once(ROOT . "routes/web/router.php");