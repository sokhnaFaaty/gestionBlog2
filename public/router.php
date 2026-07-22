<?php
// Serveur PHP intégré : sert les fichiers statiques tels quels, redirige le reste vers index.php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Si c'est un fichier réel (CSS, image, upload…), le servir directement
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Sinon, tout passe par index.php
require_once __DIR__ . '/index.php';
