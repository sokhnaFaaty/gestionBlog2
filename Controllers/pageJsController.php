<?php
require_once ROOT . "/models/contactModel.php";

/**
 * API JSON des pages statiques (à propos, contact). Publique.
 */

$action = $_REQUEST["action"] ?? "apropos";

// ── À PROPOS ──

$apropos = function () {
    loadJson([
        "success" => true,
        "stats"   => [
            "articles"     => statArticlesPublies(),
            "auteurs"      => statAuteurs(),
            "categories"   => countTable("categorie"),
            "commentaires" => countTable("commentaire"),
        ],
    ]);
};

// ── CONTACT ──

$contact = function () {
    requirePost();

    $datas  = jsonInput();
    $rules  = [
        "nom"     => "required",
        "email"   => "required|email",
        "sujet"   => "required",
        "message" => "required",
    ];
    $errors = validations($datas, $rules);

    if (!validate($errors)) {
        loadJson(["success" => false, "errors" => $errors], 422);
    }

    $ok = addContact([
        "nom"     => trim($datas["nom"]),
        "email"   => trim($datas["email"]),
        "sujet"   => trim($datas["sujet"]),
        "message" => trim($datas["message"]),
    ]);

    if (!$ok) {
        loadJson([
            "success" => false,
            "message" => "L'envoi a échoué, réessayez plus tard.",
        ], 500);
    }

    // Pas de PRG ici : en fetch la page n'est pas rechargée,
    // le JS affiche directement le message de succès.
    loadJson([
        "success" => true,
        "message" => "Votre message a bien été envoyé. Merci !",
    ]);
};

// ── ROUTING ──

$actions = [
    "apropos" => $apropos,
    "contact" => $contact,
];

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    loadJson(["success" => false, "message" => "Action introuvable"], 404);
}
