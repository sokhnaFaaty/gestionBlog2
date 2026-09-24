<?php
$controllers = [
    "article"      => "article",
    "commentaire"  => "commentaire",
    "categorie"    => "categorie",
    "utilisateur"  => "utilisateur",
    "auth"         => "auth",
    "newsletter"   => "newsletter",
    "page"         => "page",
   "articlejs"    => "articleJs",
      "authjs"    => "authJs",
      "utilisateurjs"    => "utilisateurJs",
      "pagejs"    => "pageJs",
     "categoriejs"    => "categorieJs",
      "commentairejs"    => "commentaireJs",
      "newsletterjs"    => "newsletterJs",
       "notificationjs"    => "notificationJs",
];

$controller = $_REQUEST["controller"] ?? "article";

if (array_key_exists($controller, $controllers)) {
    $path = ROOT . "Controllers/" . $controllers[$controller] . "Controller.php";
} else {
    notFound("Contrôleur introuvable");
}

require_once($path);