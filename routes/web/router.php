<?php
$controllers = [
    "article"      => "Article",
    "commentaire"  => "Commentaire",
    "categorie"    => "Categorie",
    "utilisateur"  => "Utilisateur",
    "auth"         => "auth",
    "newsletter"   => "newsletter"
];

$controller = $_REQUEST["controller"] ?? "article";

if (array_key_exists($controller, $controllers)) {
    $path = ROOT . "Controllers/" . $controllers[$controller] . "Controller.php";
} else {
    echo "Controller introuvable";
    exit();
}

require_once($path);