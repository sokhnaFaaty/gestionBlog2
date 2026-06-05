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