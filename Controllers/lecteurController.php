<?php
require_once ROOT . "/models/lecteurModel.php";
//dd($_REQUEST);

$listeArticles = function () {
    $articles = findArticlesPublies();
    loadView("lecteurs/liste", ["articles" => $articles], "base");
};

$voirArticle = function () {
    $id = (int)($_GET["id"] ?? 0);
    if (!$id) redirectTo("lecteur", "liste");

    $article      = findArticleById($id);
    $commentaires = findCommentairesByArticle($id);

    if (!$article) redirectTo("lecteur", "liste");

    loadView("lecteurs/article", [
        "article"      => $article,
        "commentaires" => $commentaires,
        "errors"       => [],
    ], "base");
};

$ajouterCommentaire = function () {
    auth();
    $id = (int)($_POST["id_article"] ?? 0);

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

$actions = [
    "liste"              => $listeArticles,
    "index"              => $listeArticles,
    "article"            => $voirArticle,
    "ajouterCommentaire" => $ajouterCommentaire,
    "signalerArticle"    => $signalerArticle,
];

$action = $_REQUEST["action"] ?? "liste";
if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    echo "Action introuvable";
}