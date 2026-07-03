<?php
require_once ROOT . "/models/commentaireModel.php";
require_once ROOT . "/models/articleModel.php";

$action = $_REQUEST["action"] ?? "index";

// Actions publiques — pas de vérification
$actionsPubliques = ["index"];

if (!in_array($action, $actionsPubliques)) {
    auth();
}

// ── ACTIONS LECTEUR / AUTEUR 

$ajouter = function () {
    $id     = (int)($_POST["id_article"] ?? 0);
    $rules  = ["contenu" => "required"];
    $errors = validations($_POST, $rules);

    if (validate($errors)) {
        addCommentaire($id, $_SESSION["user"]["id_utilisateur"], $_POST["contenu"]);
        redirectTo("article", "article", ["id" => $id]);
    }

    $article      = findArticleById($id);
    $commentaires = findCommentairesByArticle($id);

    $layout = hasRole("auteur") || hasRole("admin") ? "side" : "base";
    loadView("articles/article", [
        "article"      => $article,
        "commentaires" => $commentaires,
        "errors"       => $errors,
    ], $layout);
};

$modifier = function () {
    $id_commentaire = (int)($_POST["id_commentaire"] ?? 0);
    $id_article     = (int)($_POST["id_article"] ?? 0);
    $contenu        = trim($_POST["contenu"] ?? "");

    if ($contenu) {
        modifierCommentaire($id_commentaire, $_SESSION["user"]["id_utilisateur"], $contenu);
    }
    redirectTo("article", "article", ["id" => $id_article]);
};

$supprimer = function () {
    $id_commentaire = (int)($_POST["id_commentaire"] ?? 0);
    $id_article     = (int)($_POST["id_article"] ?? 0);
    supprimerCommentaire($id_commentaire, $_SESSION["user"]["id_utilisateur"]);
    redirectTo("article", "article", ["id" => $id_article]);
};

$signalerCommentaire = function () {
    $id_commentaire = (int)($_POST["id_commentaire"] ?? 0);
    $id_article     = (int)($_POST["id_article"] ?? 0);
    signalerCommentaire($id_commentaire, $_SESSION["user"]["id_utilisateur"]);
    redirectTo("article", "article", ["id" => $id_article]);
};

// ── ACTIONS ADMIN 

$supprimerAdmin = function () {
    if (!hasRole("admin")) redirectTo("article", "home");
    $id = (int)($_POST["id_commentaire"] ?? 0);
    if ($id) deleteCommentaire($id);
    redirectTo("article", "listeAdmin");
};

$signalements = function () {
    if (!hasRole("admin")) redirectTo("article", "home");
    $signalements = findSignalementsCommentaires();
    loadView("commentaires/signalements", ["signalements" => $signalements], "side");
};

$supprimerSignale = function () {
    if (!hasRole("admin")) redirectTo("article", "home");
    $id = (int)($_POST["id_commentaire"] ?? 0);
    if ($id) deleteCommentaire($id);
    redirectTo("commentaire", "signalements");
};

// ── ROUTING 

$actions = [
    "ajouter"          => $ajouter,
    "modifier"         => $modifier,
    "supprimer"        => $supprimer,
    "signalerCommentaire" => $signalerCommentaire,
    "supprimerAdmin"   => $supprimerAdmin,
    "signalements"     => $signalements,
    "supprimerSignale" => $supprimerSignale,
];

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    echo "Action introuvable";
}