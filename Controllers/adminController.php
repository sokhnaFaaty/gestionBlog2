<?php
require_once ROOT . "/models/adminModel.php";
auth();
if (!hasRole("admin")) {
    redirectTo("auth", "login");
}
$dashboard = function () {
    $stats = [
        "total_articles"      => countTable("article"),
        //  Comptage précis par rôle
        "total_auteurs"       => countUtilisateursByRole("auteur"),
        "total_lecteurs"      => countUtilisateursByRole("lecteur"),
        "total_admins"        => countUtilisateursByRole("admin"),
        "articles_en_attente" => countArticlesByStatut("En attente"),
        "articles_publies"    => countArticlesByStatut("Publie"),
        "articles_rejetes"    => countArticlesByStatut("Rejete"),
        "total_commentaires"  => countTable("commentaire"),
        "derniers_articles"   => findDerniersArticles(),
    ];
    loadView("admins/dashboard", $stats, "side");
};

$listeArticles = function () {
    $statut   = $_GET["statut"] ?? null;
    $articles = findAllArticlesAdmin($statut);
    loadView("admins/listeArticles", [
        "articles"      => $articles,
        "statut_filtre" => $statut,
    ], "side");
};

$changerStatut = function () {
    $id     = (int)$_POST["id_article"];
    $statut = $_POST["statut"];
    $statutsValides = ["Publie", "Rejete", "En attente"];
    if (in_array($statut, $statutsValides)) {
        updateStatutArticle($id, $statut);
    }
    redirectTo("admin", "listeArticles");
};


$listeAuteurs = function () {
    $auteurs = findAllAuteurs();
    loadView("admins/listeAuteurs", ["auteurs" => $auteurs], "side");
};

$banirAuteur = function () {
    $id = (int)$_POST["id_utilisateur"];
    toggleBanAuteur($id);
    redirectTo("admin", "listeAuteurs");
};

//admin
$addAdmin = function () {
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
            redirectTo("admin", "listeAdmins");
        }
    }

    loadView("admins/addAdmin", ["errors" => $errors], "side");
};

$listeAdmins = function () {
    $admins = findAllAdmins();
    loadView("admins/listeAdmins", ["admins" => $admins], "side");
};

$supprimerAdmin = function () {
    $id         = (int)($_POST["id_utilisateur"] ?? 0);
    //  Utilise id_utilisateur (plus id_auteur)
    $idConnecte = (int)($_SESSION["user"]["id_utilisateur"] ?? 0);

    if ($id && $id !== $idConnecte) {
        deleteAdmin($id);
    }
    redirectTo("admin", "listeAdmins");
};