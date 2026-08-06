<?php
require_once ROOT . "/models/categorieModel.php";

/**
 * API JSON des catégories. Toutes les actions sont réservées à l'admin.
 */

$action = $_REQUEST["action"] ?? "liste";
authJson();

// ── LECTURE ──

$liste = function () {
    hasRoleJson("admin");

    $page    = max(1, (int)($_GET["page"] ?? 1));
    $parPage = 10;
    $total   = countAllCategories();

    loadJson([
        "success"    => true,
        "categories" => findAllCategoriesPaginees($page, $parPage),
        "total"      => $total,
        "page"       => $page,
        "totalPages" => (int)ceil($total / $parPage),
    ]);
};

// Liste complète sans pagination (pour remplir un <select>)
$toutes = function () {
    hasRoleJson("admin", "auteur");
    loadJson(["success" => true, "categories" => findAllCategories()]);
};

$voir = function () {
    hasRoleJson("admin");

    $id        = (int)($_GET["id"] ?? 0);
    $categorie = $id ? findCategorieById($id) : false;

    if (!$categorie) {
        loadJson(["success" => false, "message" => "Catégorie introuvable"], 404);
    }
    loadJson(["success" => true, "categorie" => $categorie]);
};

// ── ÉCRITURE ──

$add = function () {
    hasRoleJson("admin");
    requirePost();

    $datas   = jsonInput();
    $libelle = trim($datas["libelle"] ?? "");
    $errors  = [];

    if ($libelle === "") {
        $errors["libelle"] = "Le nom de la catégorie est obligatoire.";
    } elseif (categorieLibelleExiste($libelle)) {
        $errors["libelle"] = "Cette catégorie existe déjà.";
    }

    if (!validate($errors)) {
        loadJson(["success" => false, "errors" => $errors], 422);
    }

    addCategorie($libelle);
    loadJson(["success" => true, "message" => "Catégorie ajoutée."]);
};

$edit = function () {
    hasRoleJson("admin");
    requirePost();

    $datas   = jsonInput();
    $id      = (int)($datas["id_categorie"] ?? 0);
    $libelle = trim($datas["libelle"] ?? "");
    $errors  = [];

    if (!$id || !findCategorieById($id)) {
        loadJson(["success" => false, "message" => "Catégorie introuvable"], 404);
    }
    if ($libelle === "") {
        $errors["libelle"] = "Le nom de la catégorie est obligatoire.";
    } elseif (categorieLibelleExiste($libelle, $id)) {
        $errors["libelle"] = "Cette catégorie existe déjà.";
    }

    if (!validate($errors)) {
        loadJson(["success" => false, "errors" => $errors], 422);
    }

    updateCategorie($id, $libelle);
    loadJson(["success" => true, "message" => "Catégorie modifiée."]);
};

$supprimer = function () {
    hasRoleJson("admin");
    requirePost();

    $id  = (int)(jsonInput()["id_categorie"] ?? 0);
    $cat = $id ? findCategorieById($id) : false;

    if (!$cat) {
        loadJson(["success" => false, "message" => "Catégorie introuvable"], 404);
    }
    // On ne supprime pas une catégorie encore rattachée à des articles
    if (countArticlesByCategorie($id) !== 0) {
        loadJson([
            "success" => false,
            "message" => "Cette catégorie contient des articles.",
        ], 409);
    }

    deleteCategorie($id);
    loadJson(["success" => true, "message" => "Catégorie supprimée."]);
};

// ── ROUTING ──

$actions = [
    "liste"     => $liste,
    "toutes"    => $toutes,
    "voir"      => $voir,
    "add"       => $add,
    "edit"      => $edit,
    "supprimer" => $supprimer,
];

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    loadJson(["success" => false, "message" => "Action introuvable"], 404);
}
