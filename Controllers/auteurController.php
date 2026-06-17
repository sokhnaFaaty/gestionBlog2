<?php
require_once ROOT . "/models/auteurModel.php";
require_once ROOT . "/models/lecteurModel.php";
auth();

//  Le rôle dans la base est 'auteur', pas 'utilisateur'
if (!hasRole("auteur") && !hasRole("admin")) {
    redirectTo("auth", "login");
}

$liste = function () {
    $articles       = findAllArticles();
    $total_articles = countTable("article");
    loadView("auteurs/liste", [
        "articles"       => $articles,
        "total_articles" => $total_articles,
    ], "side");
};

$addArticleAction = function () {
    $errors     = [];
    $categories = findAllCategories();

    if (isset($_POST["btn_publier"])) {
        $rules = [
            "titre"        => "required",
            "contenu"      => "required",
            "categorie_id" => "required",
        ];
        $errors = validations($_POST, $rules);

        // Gestion de l'image
        $imageName = null;
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $fileTmpPath       = $_FILES["image"]["tmp_name"];
            $fileName          = $_FILES["image"]["name"];
            $fileExtension     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ["jpg", "jpeg", "png", "webp", "gif"];

            if (in_array($fileExtension, $allowedExtensions)) {
                $imageName     = time() . "_" . uniqid() . "." . $fileExtension;
                $uploadFileDir = ROOT . "/public/uploads/";
                if (!is_dir($uploadFileDir)) mkdir($uploadFileDir, 0755, true);
                if (!move_uploaded_file($fileTmpPath, $uploadFileDir . $imageName)) {
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
            redirectTo("auteur", "liste");
        }
    }

    loadView("auteurs/add_article", [
        "errors"     => $errors,
        "categories" => $categories,
    ], "side");
};

$editArticleAction = function () {
    $id = (int)($_GET["id"] ?? $_POST["id_article"] ?? 0);
    if (!$id) redirectTo("auteur", "liste");

    $article    = findArticleById_utilisateur($id);
    $categories = findAllCategories();

    if (!$article || $article["id_utilisateur"] !== $_SESSION["user"]["id_utilisateur"]) {
        redirectTo("auteur", "liste");
    }

    $errors = [];

    if (isset($_POST["btn_modifier"])) {
        $rules = [
            "titre"        => "required",
            "contenu"      => "required",
            "categorie_id" => "required",
        ];
        $errors = validations($_POST, $rules);

        // Image optionnelle à l'édition : on conserve l'existante par défaut
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
            redirectTo("auteur", "liste");
        }
    }

    loadView("auteurs/edit_article", [
        "article"    => $article,
        "categories" => $categories,
        "errors"     => $errors,
    ], "side");
};

$deleteArticleAction = function () {
    $id = (int)($_POST["id_article"] ?? 0);
    if (!$id) redirectTo("auteur", "liste");

    $article = findArticleById_utilisateur($id);
    if ($article && $article["id_utilisateur"] === $_SESSION["user"]["id_utilisateur"]) {
        deleteArticle($id);
    }
    redirectTo("auteur", "liste");
};

$actions = [
    "index"  => $liste,
    "liste"  => $liste,
    "add"    => $addArticleAction,
    "edit"   => $editArticleAction,
    "delete" => $deleteArticleAction,
];

$action = $_REQUEST["action"] ?? "liste";
if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    echo "Action introuvable";
}