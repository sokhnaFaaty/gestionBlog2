<?php
require_once ROOT . "/models/newsletterModel.php";

/**
 * API JSON de la newsletter. Publique : le formulaire est dans le footer.
 */

$action = $_REQUEST["action"] ?? "subscribe";

$subscribe = function () {
    requirePost();

    $email = trim(jsonInput()["email"] ?? "");

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        loadJson([
            "success" => false,
            "errors"  => ["email" => "L'adresse email n'est pas valide."],
        ], 422);
    }

    inscrireNewsletter($email);
    loadJson(["success" => true, "message" => "Inscription à la newsletter enregistrée."]);
};

// ── ROUTING ──

$actions = [
    "subscribe" => $subscribe,
];

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    loadJson(["success" => false, "message" => "Action introuvable"], 404);
}
