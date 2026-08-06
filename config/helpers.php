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

/**
 * Équivalent de loadView() mais pour les contrôleurs JS :
 * on renvoie du JSON au lieu d'une vue, puis on arrête le script.
 */
function loadJson(array $datas, int $code = 200): void {
    http_response_code($code);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($datas, JSON_UNESCAPED_UNICODE);
    exit();
}


/**
 * Équivalent JSON de auth() : un fetch ne peut pas suivre une redirection
 * vers la page de login, on répond donc 401 et le JS décide quoi faire.
 */
function authJson(): void {
    if (!isConnected()) {
        loadJson(["success" => false, "message" => "Non authentifié"], 401);
    }
}

// Équivalent JSON de hasRole() : accepte plusieurs rôles, sinon 403.
function hasRoleJson(string ...$roles): void {
    foreach ($roles as $role) {
        if (hasRole($role)) return;
    }
    loadJson(["success" => false, "message" => "Accès refusé"], 403);
}

// Refuse une action de modification appelée en GET.
function requirePost(): void {
    if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
        loadJson(["success" => false, "message" => "Méthode non autorisée"], 405);
    }
}

/**
 * Données envoyées par le JS, que ce soit du FormData
 * (fetch avec un objet FormData) ou du JSON (Content-Type: application/json).
 */
function jsonInput(): array {
    if (!empty($_POST)) {
        return $_POST;
    }
    $body = json_decode(file_get_contents("php://input") ?: "", true);
    return is_array($body) ? $body : [];
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