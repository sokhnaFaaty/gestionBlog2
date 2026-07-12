<?php
require_once ROOT . "/models/articleModel.php";
require_once ROOT . "/models/categorieModel.php";

$action = $_REQUEST["action"] ?? "home";
$actionsPubliques = ["home", "index", "voir"];
if (!in_array($action, $actionsPubliques)) {
    auth();
}


// ── LISTE 

// Liste des articles de l'auteur connecté
$liste = function () {
    if (!hasRole("auteur") && !hasRole("admin")) {
        redirectTo("article", "home");
    }
    $articles       = findAllArticles();
    $total_articles = countTable("article");
    loadView("articles/liste", [
        "articles"       => $articles,
        "total_articles" => $total_articles,
    ], "side");
};

// Page d'accueil publique
$home = function () {
    $articles = findArticlesPublies();
    loadView("articles/home", ["articles" => $articles], "base");
};

// Voir un article
$voirArticle = function () {
    require_once ROOT . "/models/CommentaireModel.php";
    $id = (int)($_GET["id"] ?? 0);
    if (!$id) redirectTo("article", "home");

    $article      = findArticleById($id);
    $commentaires = findCommentairesByArticle($id);

    if (!$article) redirectTo("article", "home");

    $layout = hasRole("auteur") || hasRole("admin") ? "side" : "base";
    loadView("articles/article", [
        "article"      => $article,
        "commentaires" => $commentaires,
        "errors"       => [],
    ], $layout);
};

// ── CRUD AUTEUR 

$add = function () {
    if (!hasRole("auteur") && !hasRole("admin")) {
        redirectTo("article", "home");
    }
    $errors     = [];
    $categories = findAllCategories();

    if (isset($_POST["btn_publier"])) {
        $rules = [
            "titre"        => "required",
            "contenu"      => "required",
            "categorie_id" => "required",
        ];
        $errors = validations($_POST, $rules);

        $imageName = null;
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $fileExtension     = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $allowedExtensions = ["jpg", "jpeg", "png", "webp", "gif"];

            if (in_array($fileExtension, $allowedExtensions)) {
                $imageName     = time() . "_" . uniqid() . "." . $fileExtension;
                $uploadFileDir = ROOT . "/public/uploads/";
                if (!is_dir($uploadFileDir)) mkdir($uploadFileDir, 0755, true);
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFileDir . $imageName)) {
                    $errors["image"] = "Erreur lors du déplacement de l'image.";
                }
            } else {
                $errors["image"] = "Format invalide (JPG, JPEG, PNG, WEBP, GIF uniquement).";
            }
        } else {
            $errors["image"] = "Une photo de couverture est obligatoire.";
        }

        if (validate($errors)) {
            $articleData = [
                'titre'          => $_POST['titre'],
                'image'          => $imageName,
                'contenu'        => $_POST['contenu'],
                'statut'         => 'En attente',
                'id_utilisateur' => $_SESSION['user']['id_utilisateur'],
            ];
            addArticle($articleData, (int)$_POST['categorie_id']);
            redirectTo("article", "liste");
        }
    }

    loadView("articles/add_article", [
        "errors"     => $errors,
        "categories" => $categories,
    ], "side");
};

$edit = function () {
    if (!hasRole("auteur") && !hasRole("admin")) {
        redirectTo("article", "home");
    }
    $id = (int)($_GET["id"] ?? $_POST["id_article"] ?? 0);
    if (!$id) redirectTo("article", "liste");

    $article    = findArticleById_utilisateur($id);
    $categories = findAllCategories();

    if (!$article || $article["id_utilisateur"] !== $_SESSION["user"]["id_utilisateur"]) {
        redirectTo("article", "liste");
    }

    $errors = [];

    if (isset($_POST["btn_modifier"])) {
        $rules = [
            "titre"        => "required",
            "contenu"      => "required",
            "categorie_id" => "required",
        ];
        $errors = validations($_POST, $rules);

        $imageName = $article["image"];
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $fileExtension     = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $allowedExtensions = ["jpg", "jpeg", "png", "webp", "gif"];
            if (in_array($fileExtension, $allowedExtensions)) {
                $newName       = time() . "_" . uniqid() . "." . $fileExtension;
                $uploadFileDir = ROOT . "/public/uploads/";
                if (!is_dir($uploadFileDir)) mkdir($uploadFileDir, 0755, true);
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFileDir . $newName)) {
                    $imageName = $newName;
                } else {
                    $errors["image"] = "Erreur lors du déplacement de l'image.";
                }
            } else {
                $errors["image"] = "Format invalide (JPG, JPEG, PNG, WEBP, GIF uniquement).";
            }
        }

        if (validate($errors)) {
            $data = [
                "titre"      => $_POST["titre"],
                "contenu"    => $_POST["contenu"],
                "image"      => $imageName,
                "id_article" => $id,
            ];
            updateArticle($data, (int)$_POST["categorie_id"]);
            redirectTo("article", "liste");
        }
    }

    loadView("articles/edit_article", [
        "article"    => $article,
        "categories" => $categories,
        "errors"     => $errors,
    ], "side");
};

$delete = function () {
    if (!hasRole("auteur") && !hasRole("admin")) {
        redirectTo("article", "home");
    }
    $id = (int)($_POST["id_article"] ?? 0);
    if (!$id) redirectTo("article", "liste");

    $article = findArticleById_utilisateur($id);
    if ($article && $article["id_utilisateur"] === $_SESSION["user"]["id_utilisateur"]) {
        deleteArticle($id);
    }
    redirectTo("article", "liste");
};

// ── ACTIONS ADMIN 

$changerStatut = function () {
    if (!hasRole("admin")) redirectTo("article", "home");

    $id     = (int)$_POST["id_article"];
    $statut = $_POST["statut"];
    $statutsValides = ["Publie", "Rejete", "En attente"];
    if (in_array($statut, $statutsValides)) {
        updateStatutArticle($id, $statut);
    }
    redirectTo("article", "listeAdmin");
};

$listeAdmin = function () {
    if (!hasRole("admin")) redirectTo("article", "home");

    $statut   = $_GET["statut"] ?? null;
    $articles = findAllArticlesAdmin($statut);
    loadView("articles/listeArticles", [
        "articles"      => $articles,
        "statut_filtre" => $statut,
    ], "side");
};

$banir = function () {
    if (!hasRole("admin")) redirectTo("article", "home");

    $id = (int)$_POST["id_article"];
    updateStatutArticle($id, "Rejete");
    redirectTo("article", "listeAdmin");
};

$search = function () {
    if (!hasRole("admin")) redirectTo("article", "home");

    $q = trim($_GET['q'] ?? '');
    header('Content-Type: application/json');
    if (strlen($q) < 2) {
        echo json_encode(['articles' => [], 'auteurs' => [], 'categories' => []]);
        exit();
    }
    echo json_encode(searchGlobal($q));
    exit();
};
$signalerArticle = function () {
    $id = (int)($_POST["id_article"] ?? 0);
    signalerArticle($id, $_SESSION["user"]["id_utilisateur"]);
    redirectTo("article", "voir", ["id" => $id]);
};

//pagination
$home = function () {
    $page     = (int)($_GET["page"] ?? 1);
    $total    = countArticlesPublies();
    $parPage  = 4;
    $articles = findArticlesPubliesPagines($page, $parPage);
    loadView("articles/home", [
        "articles"   => $articles,
        "page"       => $page,
        "totalPages" => ceil($total / $parPage),
    ], "base");
};
$liste = function () {
    if (!hasRole("auteur") && !hasRole("admin")) {
        redirectTo("article", "home");
    }
    $page     = (int)($_GET["page"] ?? 1);
    $total    = countAllArticles();
    $parPage  = 5;
    $articles = findAllArticlesPagines($page, $parPage);
    loadView("articles/liste", [
        "articles"       => $articles,
        "total_articles" => $total,
        "page"           => $page,
        "totalPages"     => ceil($total / $parPage),
    ], "side");
};
$listeAdmin = function () {
    if (!hasRole("admin")) redirectTo("article", "home");
    $statut   = $_GET["statut"] ?? null;
    $page     = (int)($_GET["page"] ?? 1);
    $parPage  = 10;
    $total    = countAllArticlesAdmin($statut);
    $articles = findAllArticlesAdminPagines($statut, $page, $parPage);
    loadView("articles/listeArticles", [
        "articles"      => $articles,
        "statut_filtre" => $statut,
        "page"          => $page,
        "totalPages"    => ceil($total / $parPage),
    ], "side");
};

// ── ROUTING 

$actions = [
    "index"        => $home,
    "home"         => $home,
    "liste"        => $liste,
    "voir"      => $voirArticle,
    "add"          => $add,
    "edit"         => $edit,
    "delete"       => $delete,
    "changerStatut" => $changerStatut,
    "listeAdmin"   => $listeAdmin,
    "banir"        => $banir,
    "search"       => $search,
    "signalerArticle" => $signalerArticle,
];

$action = $_REQUEST["action"] ?? "home";
if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    echo "Action introuvable";
}