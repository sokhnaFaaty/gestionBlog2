<?php
$controllers = [
    "article"      => "article",
    "commentaire"  => "commentaire",
    "categorie"    => "categorie",
    "utilisateur"  => "utilisateur",
    "auth"         => "auth",
    "newsletter"   => "newsletter",
    "page"         => "page"
];

$controller = $_REQUEST["controller"] ?? "article";

if (array_key_exists($controller, $controllers)) {
    $path = ROOT . "Controllers/" . $controllers[$controller] . "Controller.php";
} else {
    echo "Controller introuvable";
    exit();
}

require_once($path);