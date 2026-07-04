<?php
require_once ROOT . "/models/utilisateurModel.php";

$action = $_REQUEST["action"] ?? "index";

// Toutes les actions utilisateur nécessitent d'être connecté
auth();

// ── ACTIONS ADMIN 

$listeAuteurs = function () {
    if (!hasRole("admin")) redirectTo("article", "home");
    $auteurs = findAllAuteurs();
    loadView("utilisateurs/listeAuteurs", ["auteurs" => $auteurs], "side");
};

$banirAuteur = function () {
    if (!hasRole("admin")) redirectTo("article", "home");
    $id = (int)$_POST["id_utilisateur"];
    toggleBanAuteur($id);
    redirectTo("utilisateur", "listeAuteurs");
};

$listeAdmins = function () {
    if (!hasRole("admin")) redirectTo("article", "home");
    $admins = findAllAdmins();
    loadView("utilisateurs/listeAdmins", ["admins" => $admins], "side");
};

$addAdmin = function () {
    if (!hasRole("admin")) redirectTo("article", "home");
    $errors = [];

    if (isset($_POST["btn_ajouter_admin"])) {
        $rules = [
            "nom"      => "required|string",
            "prenom"   => "required|string",
            "email"    => "required|email|unique",
            "password" => "required",
        ];
        $errors = validations($_POST, $rules, "emailAdminExiste");

        if (validate($errors)) {
            addAdmin($_POST);
            redirectTo("utilisateur", "listeAdmins");
        }
    }

    loadView("utilisateurs/addAdmin", ["errors" => $errors], "side");
};

$supprimerAdmin = function () {
    if (!hasRole("admin")) redirectTo("article", "home");

    $id         = (int)($_POST["id_utilisateur"] ?? 0);
    $idConnecte = (int)($_SESSION["user"]["id_utilisateur"] ?? 0);

    if ($id && $id !== $idConnecte) {
        deleteAdmin($id);
    }
    redirectTo("utilisateur", "listeAdmins");
};

$listeNewsletters = function () {
    if (!hasRole("admin")) redirectTo("article", "home");
    require_once ROOT . "/models/newsletterModel.php";
    $newsletters = findAllNewslettersEmails();
    loadView("utilisateurs/listeNewsletter", ["newsletters" => $newsletters], "side");
};

$dashboard = function () {
    if (!hasRole("admin")) redirectTo("article", "home");
    require_once ROOT . "/models/ArticleModel.php";

    $stats = [
        "total_articles"      => countTable("article"),
        "total_auteurs"       => countUtilisateursByRole("auteur"),
        "total_lecteurs"      => countUtilisateursByRole("lecteur"),
        "total_admins"        => countUtilisateursByRole("admin"),
        "articles_en_attente" => countArticlesByStatut("En attente"),
        "articles_publies"    => countArticlesByStatut("Publie"),
        "articles_rejetes"    => countArticlesByStatut("Rejete"),
        "total_commentaires"  => countTable("commentaire"),
        "derniers_articles"   => findDerniersArticles(),
    ];
    loadView("utilisateurs/dashboard", $stats, "side");
};
//paginatiom

$listeAuteurs = function () {
    if (!hasRole("admin")) redirectTo("article", "home");
    $page     = (int)($_GET["page"] ?? 1);
    $parPage  = 10;
    $total    = countAllAuteurs();
    $auteurs  = findAllAuteursPagines($page, $parPage);
    loadView("utilisateurs/listeAuteurs", [
        "auteurs"    => $auteurs,
        "page"       => $page,
        "totalPages" => ceil($total / $parPage),
    ], "side");
};
$listeAdmins = function () {
    if (!hasRole("admin")) redirectTo("article", "home");
    $page     = (int)($_GET["page"] ?? 1);
    $parPage  = 10;
    $total    = countAllAdmins();
    $admins   = findAllAdminsPagines($page, $parPage);
    loadView("utilisateurs/listeAdmins", [
        "admins"     => $admins,
        "page"       => $page,
        "totalPages" => ceil($total / $parPage),
    ], "side");
};
// ── ROUTING 

$actions = [
    "index"          => $dashboard,
    "dashboard"      => $dashboard,
    "listeAuteurs"   => $listeAuteurs,
    "banirAuteur"    => $banirAuteur,
    "listeAdmins"    => $listeAdmins,
    "addAdmin"       => $addAdmin,
    "supprimerAdmin" => $supprimerAdmin,
    "listeNewsletters" => $listeNewsletters,
];

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    echo "Action introuvable";
}