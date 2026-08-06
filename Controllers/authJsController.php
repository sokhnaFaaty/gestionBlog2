<?php
require_once ROOT . "/models/authModel.php";

/**
 * API JSON d'authentification.
 * La session PHP reste la source de vérité : le JS ne manipule pas de token,
 * il envoie ses requêtes avec le cookie de session comme n'importe quelle page.
 */

$action = $_REQUEST["action"] ?? "me";

// login() fait un SELECT * : la ligne contient le mot de passe.
// On ne renvoie jamais ce champ au navigateur.
$userPublic = function (array $user): array {
    unset($user["mdp"]);
    return $user;
};

// Page vers laquelle le JS doit rediriger après connexion, selon le rôle
$accueilSelonRole = function (string $role): string {
    if ($role === "admin")  return path("utilisateur", "dashboard");
    if ($role === "auteur") return path("article", "liste");
    return path("article", "home");
};

// ── UTILISATEUR COURANT ──

// Permet au JS de savoir s'il y a une session ouverte et qui est connecté
$me = function () use ($userPublic) {
    if (!isConnected()) {
        loadJson(["success" => true, "connecte" => false, "user" => null]);
    }
    loadJson([
        "success"  => true,
        "connecte" => true,
        "user"     => $userPublic($_SESSION["user"]),
    ]);
};

// ── CONNEXION ──

$login = function () use ($userPublic, $accueilSelonRole) {
    requirePost();

    if (isConnected()) {
        loadJson([
            "success"  => true,
            "message"  => "Déjà connecté.",
            "user"     => $userPublic($_SESSION["user"]),
            "redirect" => $accueilSelonRole($_SESSION["user"]["role"]),
        ]);
    }

    $datas  = jsonInput();
    $errors = validations($datas, [
        "email"    => "required",
        "password" => "required",
    ]);

    if (!validate($errors)) {
        loadJson(["success" => false, "errors" => $errors], 422);
    }

    $user = login($datas["email"]);

    if (!$user || $datas["password"] != $user["mdp"]) {
        loadJson([
            "success" => false,
            "errors"  => ["connect" => "email ou mot de passe incorrect"],
        ], 401);
    }

    if (!empty($user["banni"])) {
        loadJson([
            "success" => false,
            "errors"  => ["banned" => "Votre compte a été suspendu par un administrateur."],
        ], 403);
    }

    $_SESSION["user"] = $user;

    loadJson([
        "success"  => true,
        "message"  => "Connexion réussie.",
        "user"     => $userPublic($user),
        "redirect" => $accueilSelonRole($user["role"]),
    ]);
};

// ── INSCRIPTION ──

$register = function () {
    requirePost();

    if (isConnected()) {
        loadJson(["success" => false, "message" => "Vous êtes déjà connecté."], 409);
    }

    $datas  = jsonInput();
    $rules  = [
        "nom"      => "required|string",
        "prenom"   => "required|string",
        "email"    => "required|email|unique",
        "password" => "required",
        "role"     => "required",
    ];
    $errors = validations($datas, $rules, "emailExists");

    // Confirmation du mot de passe (non gérée par validations())
    if (empty($errors["password"])) {
        if (trim($datas["password_confirmation"] ?? "") === "") {
            $errors["password_confirmation"] = "Veuillez confirmer le mot de passe.";
        } elseif ($datas["password"] !== $datas["password_confirmation"]) {
            $errors["password_confirmation"] = "Les mots de passe ne correspondent pas.";
        }
    }

    if (!validate($errors)) {
        loadJson(["success" => false, "errors" => $errors], 422);
    }

    // registerUser() refuse tout rôle autre que auteur / lecteur
    $ok = registerUser([
        "nom"    => $datas["nom"],
        "prenom" => $datas["prenom"],
        "email"  => $datas["email"],
        "mdp"    => $datas["password"],
        "role"   => $datas["role"],
    ]);

    if (!$ok) {
        loadJson([
            "success" => false,
            "errors"  => ["global" => "Une erreur est survenue lors de l'inscription"],
        ], 422);
    }

    loadJson([
        "success"  => true,
        "message"  => "Inscription réussie.",
        "redirect" => path("auth", "login"),
    ]);
};

// ── DÉCONNEXION ──

$logout = function () {
    requirePost();
    session_unset();
    session_destroy();

    loadJson([
        "success"  => true,
        "message"  => "Déconnexion réussie.",
        "redirect" => path("auth", "login"),
    ]);
};

// ── ROUTING ──

$actions = [
    "me"       => $me,
    "login"    => $login,
    "register" => $register,
    "logout"   => $logout,
];

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    loadJson(["success" => false, "message" => "Action introuvable"], 404);
}
