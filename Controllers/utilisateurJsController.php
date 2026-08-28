<?php
require_once ROOT . "/models/utilisateurModel.php";

/**
 * API JSON de la gestion des utilisateurs. Réservée à l'admin.
 */

$action = $_REQUEST["action"] ?? "dashboard";
authJson();
hasRoleJson("admin");

// ── AUTEURS ──

$listeAuteurs = function () {
    $page    = max(1, (int)($_GET["page"] ?? 1));
    $parPage = 10;
    $total   = countAllAuteurs();

    loadJson([
        "success"    => true,
        "auteurs"    => findAllAuteursPagines($page, $parPage),
        "total"      => $total,
        "page"       => $page,
        "totalPages" => (int)ceil($total / $parPage),
    ]);
};

// Bannit ou débannit (le modèle inverse l'état)
$banirAuteur = function () {
    requirePost();

    $id = (int)(jsonInput()["id_utilisateur"] ?? 0);
    if (!$id) {
        loadJson(["success" => false, "message" => "Utilisateur non précisé"], 400);
    }

    toggleBanAuteur($id);
    loadJson(["success" => true, "message" => "Statut de l'auteur modifié."]);
};

// ── ADMINS ──

$listeAdmins = function () {
    $page    = max(1, (int)($_GET["page"] ?? 1));
    $parPage = 10;
    $total   = countAllAdmins();

    loadJson([
        "success"    => true,
        "admins"     => findAllAdminsPagines($page, $parPage),
        "total"      => $total,
        "page"       => $page,
        "totalPages" => (int)ceil($total / $parPage),
    ]);
};

$addAdmin = function () {
    requirePost();

    $datas  = jsonInput();
    $rules  = [
        "nom"      => "required|string",
        "prenom"   => "required|string",
        "email"    => "required|email|unique",
        "password" => "required",
    ];
    $errors = validations($datas, $rules, "emailAdminExiste");

    if (!validate($errors)) {
        loadJson(["success" => false, "errors" => $errors], 422);
    }

    addAdmin($datas);
    loadJson(["success" => true, "message" => "Administrateur ajouté."]);
};

$supprimerAdmin = function () {
    requirePost();

    $id         = (int)(jsonInput()["id_utilisateur"] ?? 0);
    $idConnecte = (int)($_SESSION["user"]["id_utilisateur"] ?? 0);

    if (!$id) {
        loadJson(["success" => false, "message" => "Utilisateur non précisé"], 400);
    }
    // Un admin ne peut pas supprimer son propre compte
    if ($id === $idConnecte) {
        loadJson([
            "success" => false,
            "message" => "Vous ne pouvez pas supprimer votre propre compte.",
        ], 409);
    }

    deleteAdmin($id);
    loadJson(["success" => true, "message" => "Administrateur supprimé."]);
};

// ── NEWSLETTER ──

$listeNewsletters = function () {
    require_once ROOT . "/models/newsletterModel.php";
    loadJson(["success" => true, "newsletters" => findAllNewslettersEmails()]);
};

// ── DASHBOARD ──

$dashboard = function () {
    require_once ROOT . "/models/articleModel.php";

    loadJson([
        "success" => true,
        "stats"   => [
            "total_articles"      => countTable("article"),
            "total_auteurs"       => countUtilisateursByRole("auteur"),
            "total_lecteurs"      => countUtilisateursByRole("lecteur"),
            "total_admins"        => countUtilisateursByRole("admin"),
            "articles_en_attente" => countArticlesByStatut("En attente"),
            "articles_publies"    => countArticlesByStatut("Publie"),
            "articles_rejetes"    => countArticlesByStatut("Rejete"),
            "total_commentaires"  => countTable("commentaire"),
            "derniers_articles"   => findDerniersArticles(),
        ],
    ]);
};

// ── ROUTING ──

$actions = [
    "dashboard"        => $dashboard,
    "listeAuteurs"     => $listeAuteurs,
    "banirAuteur"      => $banirAuteur,
    "listeAdmins"      => $listeAdmins,
    "addAdmin"         => $addAdmin,
    "supprimerAdmin"   => $supprimerAdmin,
    "listeNewsletters" => $listeNewsletters,
];

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    loadJson(["success" => false, "message" => "Action introuvable"], 404);
}
