<?php
require_once ROOT . "/models/newsletterModel.php";

$subscribe = function () {
    $email = trim($_POST['email'] ?? '');

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        inscrireNewsletter($email);
    }

    redirectTo("article", "home");
};

$actions = [
    'subscribe' => $subscribe,
];

$action = $_REQUEST['action'] ?? '';
if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    redirectTo("article", "home");
}
