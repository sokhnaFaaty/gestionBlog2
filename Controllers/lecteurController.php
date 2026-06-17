<?php
require_once ROOT . "/models/lecteurModel.php";

$home = function () {
    $articles = findArticlesPublies();
    loadView("lecteurs/home", ["articles" => $articles], "base");
};

$listeArticles = function () {
    $articles = findArticlesPublies();
    loadView("lecteurs/liste", ["articles" => $articles], "base");
};

$voirArticle = function () {
    $id = (int)($_GET["id"] ?? 0);
    if (!$id) redirectTo("lecteur", "home");

    $article      = findArticleById($id);
    $commentaires = findCommentairesByArticle($id);

    if (!$article) redirectTo("lecteur", "home");

    loadView("lecteurs/article", [
        "article"      => $article,
        "commentaires" => $commentaires,
        "errors"       => [],
    ], "base");
};

$ajouterCommentaire = function () {
    auth();
    $id     = (int)($_POST["id_article"] ?? 0);
    $rules  = ["contenu" => "required"];
    $errors = validations($_POST, $rules);

    if (validate($errors)) {
        addCommentaire($id, $_SESSION["user"]["id_utilisateur"], $_POST["contenu"]);
        redirectTo("lecteur", "article", ["id" => $id]);
    }

    $article      = findArticleById($id);
    $commentaires = findCommentairesByArticle($id);

    loadView("lecteurs/article", [
        "article"      => $article,
        "commentaires" => $commentaires,
        "errors"       => $errors,
    ], "base");
};

$signalerArticle = function () {
    auth();
    $id = (int)($_POST["id_article"] ?? 0);
    signalerArticle($id, $_SESSION["user"]["id_utilisateur"]);
    redirectTo("lecteur", "article", ["id" => $id]);
};

$signalerCommentaire = function () {
    auth();
    $id_commentaire = (int)($_POST["id_commentaire"] ?? 0);
    $id_article     = (int)($_POST["id_article"] ?? 0);
    signalerCommentaire($id_commentaire, $_SESSION["user"]["id_utilisateur"]);
    redirectTo("lecteur", "article", ["id" => $id_article]);
};

$actions = [
    "home"                => $home,
    "liste"               => $listeArticles,
    "index"               => $listeArticles,
    "article"             => $voirArticle,
    "ajouterCommentaire"  => $ajouterCommentaire,
    "signalerArticle"     => $signalerArticle,
    "signalerCommentaire" => $signalerCommentaire,
];

$action = $_REQUEST["action"] ?? "home";
if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    echo "Action introuvable";
}
