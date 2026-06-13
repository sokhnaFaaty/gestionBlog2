<?php
function dd($test)
{
    echo "<pre>";
    var_dump($test);
    echo "</pre>";
    die("Yallah pitié");
}

function loadView(string $view, array $datas = [], string $layout = "base") {
    ob_start();
    extract($datas);
    require_once(ROOT ."/views/". $view.".php");
    $content = ob_get_clean();
    require_once ROOT . "/views/layouts/$layout.layout.php";
}

function path(string $controller, string $action, array $params = []): string {
    $url = WEBROOT . "$controller/$action";
    if ($params) {
        $url .= '?' . http_build_query($params);
    }
    return $url;
}

function redirectTo(string $controller, string $action, array $params = []): void {
    $url = WEBROOT . "$controller/$action";
    if ($params) {
        $url .= '?' . http_build_query($params);
    }
    header('Location:' . $url);
    exit();
}

function countTable(string $table): int {
    
    //On nettoie le nom de la table pour empêcher l'injection SQL
    // PostgreSQL est sensible à la casse, on entoure le nom de la table de guillemets doubles si nécessaire
    $cleanTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    
    $sql = "SELECT COUNT(*) as total FROM \"$cleanTable\"";
    
    $pdo = openConnexion();
    $stmt = $pdo->query($sql);
    $result = $stmt->fetch();
    
    return (int)$result["total"];
}

function isConnected() {
    return isset($_SESSION["user"]);
}

function auth() {
    if (!isConnected()) {
        redirectTo("auth", "login");
    }
}

function hasRole(string $role) {
    if (!isConnected() || !isset($_SESSION["user"]["role"])) {
        return false;
    }
    return $_SESSION["user"]["role"] == $role;
}