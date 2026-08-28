<?php
require_once ROOT . "/models/articleModel.php";
require_once ROOT . "/models/categorieModel.php";

/**
 * Contrôleur "API" des articles : il ne renvoie QUE du JSON.
 * Les vues sont chargées (vides) par articleController.php,
 * puis remplies par le JS qui appelle ce contrôleur.
 */

$action           = $_REQUEST["action"] ?? "home";
$actionsPubliques = ["home", "voir"];

if (!in_array($action, $actionsPubliques)) {
    authJson();
}

/**
 * Traite l'image envoyée en FormData (un fichier ne peut pas transiter en JSON).
 * Renvoie le nom du fichier enregistré, ou null + un message dans $erreur.
 */
$uploadImage = function (?string &$erreur): ?string {
    $erreur = null;

    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extension  = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $autorisees = ["jpg", "jpeg", "png", "webp", "gif"];

    if (!in_array($extension, $autorisees)) {
        $erreur = "Format invalide (JPG, JPEG, PNG, WEBP, GIF uniquement).";
        return null;
    }

    $nom     = time() . "_" . uniqid() . "." . $extension;
    $dossier = ROOT . "/public/uploads/";
    if (!is_dir($dossier)) mkdir($dossier, 0755, true);

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $dossier . $nom)) {
        $erreur = "Erreur lors du déplacement de l'image.";
        return null;
    }
    return $nom;
};

// Supprime une image déjà déplacée quand la validation échoue après coup
$supprimerUpload = function (?string $image): void {
    if ($image && is_file(ROOT . "/public/uploads/" . $image)) {
        unlink(ROOT . "/public/uploads/" . $image);
    }
};

// ── LECTURE ──

// Accueil public : articles publiés, paginés
$home = function () {
    $page    = max(1, (int)($_GET["page"] ?? 1));
    $parPage = 3;
    $total   = countArticlesPublies();

    loadJson([
        "success"    => true,
        "articles"   => findArticlesPubliesPagines($page, $parPage),
        "total"      => $total,
        "page"       => $page,
        "totalPages" => (int)ceil($total / $parPage),
    ]);
};

// Liste des articles de l'auteur connecté
$liste = function () {
    hasRoleJson("auteur", "admin");

    $page    = max(1, (int)($_GET["page"] ?? 1));
    $parPage = 3;
    $total   = countAllArticles();

    loadJson([
        "success"    => true,
        "articles"   => findAllArticlesPagines($page, $parPage),
        "total"      => $total,
        "page"       => $page,
        "totalPages" => (int)ceil($total / $parPage),
    ]);
};

// Un article publié + ses commentaires
$voir = function () {
    require_once ROOT . "/models/commentaireModel.php";

    $id      = (int)($_GET["id"] ?? 0);
    $article = $id ? findArticleById($id) : false;

    if (!$article) {
        loadJson(["success" => false, "message" => "Article introuvable"], 404);
    }

    loadJson([
        "success"      => true,
        "article"      => $article,
        "commentaires" => findCommentairesByArticle($id),
    ]);
};

// Un article de l'auteur connecté (pour pré-remplir le formulaire de modification)
$edition = function () {
    hasRoleJson("auteur", "admin");

    $id      = (int)($_GET["id"] ?? 0);
    $article = $id ? findArticleById_utilisateur($id) : false;

    if (!$article) {
        loadJson(["success" => false, "message" => "Article introuvable"], 404);
    }
    if ((int)$article["id_utilisateur"] !== (int)$_SESSION["user"]["id_utilisateur"]) {
        loadJson(["success" => false, "message" => "Accès refusé"], 403);
    }

    loadJson([
        "success"    => true,
        "article"    => $article,
        "categories" => findAllCategories(),
    ]);
};

// ── ÉCRITURE (auteur) ──

$add = function () use ($uploadImage, $supprimerUpload) {
    hasRoleJson("auteur", "admin");
    requirePost();

    $datas  = jsonInput();
    $errors = validations($datas, [
        "titre"        => "required",
        "contenu"      => "required",
        "categorie_id" => "required",
    ]);

    $erreurImage = null;
    $image       = $uploadImage($erreurImage);

    if ($erreurImage) {
        $errors["image"] = $erreurImage;
    } elseif (!$image) {
        $errors["image"] = "Une photo de couverture est obligatoire.";
    }

    if (!validate($errors)) {
        $supprimerUpload($image); // pas d'image orpheline sur le disque
        loadJson(["success" => false, "errors" => $errors], 422);
    }

    addArticle([
        "titre"          => $datas["titre"],
        "image"          => $image,
        "contenu"        => $datas["contenu"],
        "statut"         => "En attente",
        "id_utilisateur" => $_SESSION["user"]["id_utilisateur"],
    ], (int)$datas["categorie_id"]);

    loadJson([
        "success"  => true,
        "message"  => "Article créé, en attente de validation.",
        "redirect" => path("article", "liste"),
    ]);
};

$edit = function () use ($uploadImage, $supprimerUpload) {
    hasRoleJson("auteur", "admin");
    requirePost();

    $datas   = jsonInput();
    $id      = (int)($datas["id_article"] ?? 0);
    $article = $id ? findArticleById_utilisateur($id) : false;

    if (!$article) {
        loadJson(["success" => false, "message" => "Article introuvable"], 404);
    }
    if ((int)$article["id_utilisateur"] !== (int)$_SESSION["user"]["id_utilisateur"]) {
        loadJson(["success" => false, "message" => "Accès refusé"], 403);
    }

    $errors = validations($datas, [
        "titre"        => "required",
        "contenu"      => "required",
        "categorie_id" => "required",
    ]);

    // L'image est facultative en modification : on garde l'ancienne si rien n'est envoyé
    $erreurImage = null;
    $nouvelle    = $uploadImage($erreurImage);
    if ($erreurImage) {
        $errors["image"] = $erreurImage;
    }

    if (!validate($errors)) {
        $supprimerUpload($nouvelle);
        loadJson(["success" => false, "errors" => $errors], 422);
    }

    updateArticle([
        "titre"      => $datas["titre"],
        "contenu"    => $datas["contenu"],
        "image"      => $nouvelle ?: $article["image"],
        "id_article" => $id,
    ], (int)$datas["categorie_id"]);

    loadJson([
        "success"  => true,
        "message"  => "Article modifié, en attente de validation.",
        "redirect" => path("article", "liste"),
    ]);
};

$delete = function () {
    hasRoleJson("auteur", "admin");
    requirePost();

    $id      = (int)(jsonInput()["id_article"] ?? 0);
    $article = $id ? findArticleById_utilisateur($id) : false;

    if (!$article) {
        loadJson(["success" => false, "message" => "Article introuvable"], 404);
    }
    if ((int)$article["id_utilisateur"] !== (int)$_SESSION["user"]["id_utilisateur"]) {
        loadJson(["success" => false, "message" => "Accès refusé"], 403);
    }

    deleteArticle($id);
    loadJson(["success" => true, "message" => "Article supprimé."]);
};

$signaler = function () {
    requirePost();

    $id = (int)(jsonInput()["id_article"] ?? 0);
    if (!$id) {
        loadJson(["success" => false, "message" => "Article non précisé"], 400);
    }

    signalerArticle($id, $_SESSION["user"]["id_utilisateur"]);
    loadJson(["success" => true, "message" => "Article signalé."]);
};

// ── ADMIN ──

$listeAdmin = function () {
    hasRoleJson("admin");

    $statut  = $_GET["statut"] ?? null;
    $page    = max(1, (int)($_GET["page"] ?? 1));
    $parPage = 10;
    $total   = countAllArticlesAdmin($statut);

    loadJson([
        "success"       => true,
        "articles"      => findAllArticlesAdminPagines($statut, $page, $parPage),
        "statut_filtre" => $statut,
        "total"         => $total,
        "page"          => $page,
        "totalPages"    => (int)ceil($total / $parPage),
    ]);
};

$changerStatut = function () {
    hasRoleJson("admin");
    requirePost();

    $datas  = jsonInput();
    $id     = (int)($datas["id_article"] ?? 0);
    $statut = $datas["statut"] ?? "";

    if (!$id || !in_array($statut, ["Publie", "Rejete", "En attente"])) {
        loadJson(["success" => false, "message" => "Statut invalide"], 400);
    }

    updateStatutArticle($id, $statut);
    loadJson(["success" => true, "message" => "Statut mis à jour.", "statut" => $statut]);
};

$search = function () {
    hasRoleJson("admin");

    $q = trim($_GET["q"] ?? "");
    if (strlen($q) < 2) {
        loadJson([
            "success"    => true,
            "articles"   => [],
            "auteurs"    => [],
            "categories" => [],
        ]);
    }

    loadJson(["success" => true] + searchGlobal($q));
};

// ── ROUTING ──

$actions = [
    "home"          => $home,
    "liste"         => $liste,
    "voir"          => $voir,
    "edition"       => $edition,
    "add"           => $add,
    "edit"          => $edit,
    "delete"        => $delete,
    "signaler"      => $signaler,
    "listeAdmin"    => $listeAdmin,
    "changerStatut" => $changerStatut,
    "search"        => $search,
];

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    loadJson(["success" => false, "message" => "Action introuvable"], 404);
}
