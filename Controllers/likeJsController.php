<?php
require_once ROOT . "/models/jaimeModel.php";

/**
 * Contrôleur "API" des "j'aime" : il ne renvoie QUE du JSON.
 */

$action = $_REQUEST["action"] ?? "basculer";
authJson();

// Bascule le "j'aime" d'un article ou d'un commentaire
$basculer = function () {
    requirePost();

    $datas = jsonInput();
    $type  = $datas["type"] ?? "";
    $id    = (int)($datas["id"] ?? 0);

    if (!in_array($type, JAIME_TYPES, true) || !$id) {
        loadJson(["success" => false, "message" => "Requête invalide"], 400);
    }

    $resultat = basculerJaime($type, $id, (int)$_SESSION["user"]["id_utilisateur"]);

    loadJson([
        "success" => true,
        "aime"    => $resultat["aime"],
        "total"   => $resultat["total"],
    ]);
};

$actions = [
    "basculer" => $basculer,
];

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    loadJson(["success" => false, "message" => "Action introuvable"], 404);
}
