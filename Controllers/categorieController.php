<?php
require_once ROOT . "/models/categorieModel.php";

auth(); // toutes les actions catégorie nécessitent d'être connecté

$action = $_REQUEST["action"] ?? "liste";

// ── ACTIONS ADMIN 

$liste = function () {
    if (!hasRole("admin")) redirectTo("article", "home");

    $categories = findAllCategories();
    $errors     = [];

    if (isset($_POST["btn_ajouter"])) {
        $libelle = trim($_POST["libelle"] ?? "");
        if ($libelle === "") {
            $errors["libelle"] = "Le nom de la catégorie est obligatoire.";
        } elseif (categorieLibelleExiste($libelle)) {
            $errors["libelle"] = "Cette catégorie existe déjà.";
        } else {
            addCategorie($libelle);
            redirectTo("categorie", "liste");
        }
    }

    loadView("categories/listeCategories", [
        "categories" => $categories,
        "errors"     => $errors,
    ], "side");
};

$edit = function () {
    if (!hasRole("admin")) redirectTo("article", "home");

    $id = (int)($_GET["id"] ?? $_POST["id_categorie"] ?? 0);
    if (!$id) redirectTo("categorie", "liste");

    $categorie = findCategorieById($id);
    if (!$categorie) redirectTo("categorie", "liste");

    $errors = [];

    if (isset($_POST["btn_modifier"])) {
        $libelle = trim($_POST["libelle"] ?? "");
        if ($libelle === "") {
            $errors["libelle"] = "Le nom de la catégorie est obligatoire.";
        } elseif (categorieLibelleExiste($libelle, $id)) {
            $errors["libelle"] = "Cette catégorie existe déjà.";
        } else {
            updateCategorie($id, $libelle);
            redirectTo("categorie", "liste");
        }
    }

    loadView("categories/editCategorie", [
        "categorie" => $categorie,
        "errors"    => $errors,
    ], "side");
};

$supprimer = function () {
    if (!hasRole("admin")) redirectTo("article", "home");

    $id = (int)($_POST["id_categorie"] ?? 0);
    if ($id) {
        $cat = findCategorieById($id);
        if ($cat && (int)$cat["nb_articles"] === 0) {
            deleteCategorie($id);
        }
    }
    redirectTo("categorie", "liste");
};
//pagination
$liste = function () {
    if (!hasRole("admin")) redirectTo("article", "home");

    $page       = (int)($_GET["page"] ?? 1);
    $parPage    = 10;
    $total      = countAllCategories();
    $categories = findAllCategoriesPaginees($page, $parPage);
    $errors     = [];

    if (isset($_POST["btn_ajouter"])) {
        $libelle = trim($_POST["libelle"] ?? "");
        if ($libelle === "") {
            $errors["libelle"] = "Le nom de la catégorie est obligatoire.";
        } elseif (categorieLibelleExiste($libelle)) {
            $errors["libelle"] = "Cette catégorie existe déjà.";
        } else {
            addCategorie($libelle);
            redirectTo("categorie", "liste");
        }
    }

    loadView("categories/listeCategories", [
        "categories" => $categories,
        "errors"     => $errors,
        "page"       => $page,
        "totalPages" => ceil($total / $parPage),
    ], "side");
};

// ── ROUTING 

$actions = [
    "index"     => $liste,
    "liste"     => $liste,
    "edit"      => $edit,
    "supprimer" => $supprimer,
];

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    echo "Action introuvable";
}