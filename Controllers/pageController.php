<?php
require_once ROOT . "/models/contactModel.php";

// ── PAGE À PROPOS (publique)

$apropos = function () {
    $stats = [
        "articles"     => statArticlesPublies(),
        "auteurs"      => statAuteurs(),
        "categories"   => countTable("categorie"),
        "commentaires" => countTable("commentaire"),
    ];
    loadView("pages/apropos", ["stats" => $stats], "base");
};

// ── PAGE CONTACT (publique)

$contact = function () {
    $errors  = [];
    $success = false;

    if (isset($_POST["btn_contact"])) {
        $rules = [
            "nom"     => "required",
            "email"   => "required|email",
            "sujet"   => "required",
            "message" => "required",
        ];
        $errors = validations($_POST, $rules);

        if (validate($errors)) {
            addContact([
                "nom"     => trim($_POST["nom"]),
                "email"   => trim($_POST["email"]),
                "sujet"   => trim($_POST["sujet"]),
                "message" => trim($_POST["message"]),
            ]);
            $success = true;
            $_POST   = [];
        }
    }

    loadView("pages/contact", [
        "errors"  => $errors,
        "success" => $success,
    ], "base");
};

// ── ROUTING

$actions = [
    "index"   => $apropos,
    "apropos" => $apropos,
    "contact" => $contact,
];

$action = $_REQUEST["action"] ?? "apropos";
if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    echo "Action introuvable";
}
