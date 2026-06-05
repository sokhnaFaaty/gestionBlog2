<?php
require_once ROOT . "/models/authModel.php";
$logout = function () {
    session_unset();
    session_destroy();
    redirectTo("auth", "login");
};

$login = function () {
    // Redirection intelligente si déjà connecté (Seul l'admin accède au dashboard)
    if (isConnected()) {
        if ($_SESSION["user"]["role"] === "admin") {
            redirectTo("admin", "index");
        } elseif ($_SESSION["user"]["role"] === "auteur") {
            redirectTo("auteur", "index");
        } else {
            redirectTo("lecteur", "index");
        }
    }
    
    $errors = [];
    if (isset($_POST["connect"])) {
        $rules = [
            "email"    => "required",
            "password" => "required",
        ];
        $errors = validations($_POST, $rules);

        if (validate($errors)) {
            $user = login($_POST["email"]);
            
            if ($user && $_POST["password"] == $user["mdp"]) {
                $_SESSION["user"] = $user;
                
                // Redirection selon le rôle (Seul l'admin va sur l'espace admin)
                switch ($user["role"]) {
                    case "admin":
                        redirectTo("admin", "index");
                        break;
                    case "auteur":
                        redirectTo("auteur", "liste");
                        break;
                    case "lecteur":
                    default:
                        redirectTo("lecteur", "liste");
                        break;
                }
            } else {
                $errors["connect"] = "email ou mot de passe incorrect";
            }
        }
    }
    loadView("auth/login", ["errors" => $errors], "auth");
};

$register = function () {
    if (isConnected()) {
        if ($_SESSION["user"]["role"] === "admin") {
            redirectTo("admin", "index");
        } elseif ($_SESSION["user"]["role"] === "auteur") {
            redirectTo("auteur", "liste");
        } else {
            redirectTo("lecteur", "liste");
        }
    }
    
    $errors = [];
    if (isset($_POST["register_btn"])) {
        $rules = [
            "nom"      => "required|string",
            "prenom"   => "required|string",
            "email"    => "required|email|unique",
            "password" => "required",
            "role"     => "required",
        ];
        
        $errors = validations($_POST, $rules, 'emailExists');

        if (validate($errors)) {
            $data = [
                "nom"    => $_POST["nom"],
                "prenom" => $_POST["prenom"],
                "email"  => $_POST["email"],
                "mdp"    => $_POST["password"],
                "role"   => $_POST["role"]
            ];
            
            if (registerUser($data)) {
                redirectTo("auth", "login");
            } else {
                $errors["global"] = "Une erreur est survenue lors de l'inscription";
            }
        }
    }
    loadView("auth/register", ["errors" => $errors], "auth");
};

$actions = [
    "login"    => $login,
    "logout"   => $logout,
    "register" => $register,
];

$action = $_REQUEST["action"] ?? "login";

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    echo "page introuvable c client";
    exit();
}
