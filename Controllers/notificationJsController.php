<?php
require_once ROOT . "/models/notificationModel.php";

$action = $_REQUEST["action"] ?? "liste";
authJson();

// Liste des notifications de l'utilisateur connecté
$liste = function () {
    $id = (int)$_SESSION["user"]["id_utilisateur"];
    loadJson([
        "success"  => true,
        "nonLues"  => countNotificationsNonLues($id),
        "notifs"   => findNotifications($id),
    ]);
};

// Marquer comme lues (tout, ou un espace précis via ?espace=)
$marquerLues = function () {
    requirePost();
    $id     = (int)$_SESSION["user"]["id_utilisateur"];
    $espace = $_REQUEST["espace"] ?? null;
    marquerNotificationsLues($id, $espace ?: null);
    loadJson([
        "success" => true,
        "nonLues" => countNotificationsNonLues($id),
    ]);
};

$actions = [
    "liste"      => $liste,
    "marquerLues" => $marquerLues,
];

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    loadJson(["success" => false, "message" => "Action introuvable"], 404);
}
