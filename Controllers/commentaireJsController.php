<?php
require_once ROOT . "/models/commentaireModel.php";
require_once ROOT . "/models/articleModel.php";

/**
 * API JSON des commentaires.
 * "parArticle" est publique (tout le monde lit les commentaires d'un article),
 * le reste demande une session.
 */

$action           = $_REQUEST["action"] ?? "parArticle";
$actionsPubliques = ["parArticle"];

if (!in_array($action, $actionsPubliques)) {
    authJson();
}

// ── LECTURE ──

$parArticle = function () {
    $id = (int)($_GET["id_article"] ?? 0);
    if (!$id) {
        loadJson(["success" => false, "message" => "Article non précisé"], 400);
    }

    loadJson([
        "success"      => true,
        "commentaires" => findCommentairesByArticle($id),
        // Permet au JS de savoir quels commentaires sont modifiables
        "utilisateur"  => $_SESSION["user"]["id_utilisateur"] ?? null,
    ]);
};

// ── ÉCRITURE (lecteur / auteur) ──

$ajouter = function () {
    requirePost();

    $datas  = jsonInput();
    $id     = (int)($datas["id_article"] ?? 0);
    $errors = validations($datas, ["contenu" => "required"]);

    if (!$id) {
        loadJson(["success" => false, "message" => "Article non précisé"], 400);
    }
    if (!validate($errors)) {
        loadJson(["success" => false, "errors" => $errors], 422);
    }

    addCommentaire($id, $_SESSION["user"]["id_utilisateur"], $datas["contenu"]);

    // On renvoie la liste à jour : le JS n'a plus qu'à réafficher
    loadJson([
        "success"      => true,
        "message"      => "Commentaire ajouté.",
        "commentaires" => findCommentairesByArticle($id),
    ]);
};

$modifier = function () {
    requirePost();

    $datas          = jsonInput();
    $id_commentaire = (int)($datas["id_commentaire"] ?? 0);
    $id_article     = (int)($datas["id_article"] ?? 0);
    $contenu        = trim($datas["contenu"] ?? "");

    if (!$id_commentaire || !$id_article) {
        loadJson(["success" => false, "message" => "Commentaire non précisé"], 400);
    }
    if ($contenu === "") {
        loadJson([
            "success" => false,
            "errors"  => ["contenu" => "Ce champ est obligatoire."],
        ], 422);
    }

    // Le modèle filtre déjà sur l'id de l'utilisateur : on ne modifie que le sien
    modifierCommentaire($id_commentaire, $_SESSION["user"]["id_utilisateur"], $contenu);

    loadJson([
        "success"      => true,
        "message"      => "Commentaire modifié.",
        "commentaires" => findCommentairesByArticle($id_article),
    ]);
};

$supprimer = function () {
    requirePost();

    $datas          = jsonInput();
    $id_commentaire = (int)($datas["id_commentaire"] ?? 0);
    $id_article     = (int)($datas["id_article"] ?? 0);

    if (!$id_commentaire || !$id_article) {
        loadJson(["success" => false, "message" => "Commentaire non précisé"], 400);
    }

    supprimerCommentaire($id_commentaire, $_SESSION["user"]["id_utilisateur"]);

    loadJson([
        "success"      => true,
        "message"      => "Commentaire supprimé.",
        "commentaires" => findCommentairesByArticle($id_article),
    ]);
};

$signaler = function () {
    requirePost();

    $id = (int)(jsonInput()["id_commentaire"] ?? 0);
    if (!$id) {
        loadJson(["success" => false, "message" => "Commentaire non précisé"], 400);
    }

    signalerCommentaire($id, $_SESSION["user"]["id_utilisateur"]);
    loadJson(["success" => true, "message" => "Commentaire signalé."]);
};

// ── ADMIN ──

$signalements = function () {
    hasRoleJson("admin");
    loadJson(["success" => true, "signalements" => findSignalementsCommentaires()]);
};

// Suppression par un admin : pas de filtre sur l'auteur du commentaire
$supprimerAdmin = function () {
    hasRoleJson("admin");
    requirePost();

    $id = (int)(jsonInput()["id_commentaire"] ?? 0);
    if (!$id) {
        loadJson(["success" => false, "message" => "Commentaire non précisé"], 400);
    }

    deleteCommentaire($id);
    loadJson(["success" => true, "message" => "Commentaire supprimé."]);
};

// ── ROUTING ──

$actions = [
    "parArticle"     => $parArticle,
    "ajouter"        => $ajouter,
    "modifier"       => $modifier,
    "supprimer"      => $supprimer,
    "signaler"       => $signaler,
    "signalements"   => $signalements,
    "supprimerAdmin" => $supprimerAdmin,
];

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    loadJson(["success" => false, "message" => "Action introuvable"], 404);
}
