<?php
require_once ROOT . "/models/adminModel.php";
require_once ROOT . "/models/newsletterModel.php";
auth();
if (!hasRole("admin")) {
    redirectTo("auth", "login");
}
$dashboard = function () {
    $stats = [
        "total_articles"      => countTable("article"),
        //Comptage précis par rôle
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
$banirArticle = function () {
    $id = (int)$_POST["id_article"];
    updateStatutArticle($id, "Rejete");
    redirectTo("admin", "listeArticles");
};

$supprimerCommentaire = function () {
    $id = (int)$_POST["id_commentaire"];
    deleteCommentaire($id);
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

    $idConnecte = (int)($_SESSION["user"]["id_utilisateur"] ?? 0);

    if ($id && $id !== $idConnecte) {
        deleteAdmin($id);
    }
    redirectTo("admin", "listeAdmins");
};
$listeCategories = function () {
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
            redirectTo("admin", "listeCategories");
        }
    }

    loadView("admins/listeCategories", [
        "categories" => $categories,
        "errors"     => $errors,
    ], "side");
};

$editCategorie = function () {
    $id = (int)($_GET["id"] ?? $_POST["id_categorie"] ?? 0);
    if (!$id) redirectTo("admin", "listeCategories");

    $categorie = findCategorieById($id);
    if (!$categorie) redirectTo("admin", "listeCategories");

    $errors = [];

    if (isset($_POST["btn_modifier"])) {
        $libelle = trim($_POST["libelle"] ?? "");
        if ($libelle === "") {
            $errors["libelle"] = "Le nom de la catégorie est obligatoire.";
        } elseif (categorieLibelleExiste($libelle, $id)) {
            $errors["libelle"] = "Cette catégorie existe déjà.";
        } else {
            updateCategorie($id, $libelle);
            redirectTo("admin", "listeCategories");
        }
    }

    loadView("admins/editCategorie", [
        "categorie" => $categorie,
        "errors"    => $errors,
    ], "side");
};

$supprimerCategorie = function () {
    $id = (int)($_POST["id_categorie"] ?? 0);
    if ($id) {
        $cat = findCategorieById($id);
        if ($cat && (int)$cat["nb_articles"] === 0) {
            deleteCategorie($id);
        }
    }
    redirectTo("admin", "listeCategorie");
};

$signalementsCommentaires = function () {
    $signalements = findSignalementsCommentaires();
    loadView("admins/signalementsCommentaires", ["signalements" => $signalements], "side");
};

$supprimerCommentaireSignale = function () {
    $id = (int)($_POST["id_commentaire"] ?? 0);
    if ($id) deleteCommentaire($id);
    redirectTo("admin", "signalementsCommentaires");
};
$listeNewsletter = function () {
    $newsletters = findAllNewslettersEmails();
    loadView("admins/listeNewsletter", ["newsletters" => $newsletters], "side");
}
;
//routing
$actions = [
    "index"                       => $dashboard,
    "dashboard"                   => $dashboard,
    "listeArticles"               => $listeArticles,
    "changerStatut"               => $changerStatut,
    "listeAuteurs"                => $listeAuteurs,
    "banirAuteur"                 => $banirAuteur,
    "banirArticle"                => $banirArticle,
    "supprimerCommentaire"        => $supprimerCommentaire,
    "listeCategories"             => $listeCategories,
    "editCategorie"               => $editCategorie,
    "supprimerCategorie"          => $supprimerCategorie,
    "addAdmin"                    => $addAdmin,
    "listeAdmins"                 => $listeAdmins,
    "supprimerAdmin"              => $supprimerAdmin,
    "signalementsCommentaires"    => $signalementsCommentaires,
    "supprimerCommentaireSignale" => $supprimerCommentaireSignale,
    "listeNewsletters" => $listeNewsletter
];

$action = $_REQUEST["action"] ?? "dashboard";
if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    echo "Action introuvable";
}

