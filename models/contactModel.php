<?php
require_once(ROOT . "/db/database.php");

// Enregistre un message de contact
function addContact(array $data): bool {
    $sql = "INSERT INTO contact (nom, email, sujet, message)
            VALUES (:nom, :email, :sujet, :message)";
    executeUpdate($sql, [
        ':nom'     => $data['nom'],
        ':email'   => $data['email'],
        ':sujet'   => $data['sujet'],
        ':message' => $data['message'],
    ]);
    return true;
}

// Liste des messages (pour une future page admin)
function findAllContacts(): array {
    $sql = "SELECT * FROM contact ORDER BY date_envoi DESC";
    return executeSelect($sql);
}

// ── Statistiques pour la page "À propos"

function statArticlesPublies(): int {
    $r = executeSelect("SELECT COUNT(*) AS total FROM article WHERE statut = 'Publie'", [], true);
    return (int)$r["total"];
}

function statAuteurs(): int {
    $r = executeSelect("SELECT COUNT(*) AS total FROM utilisateur WHERE role = 'auteur'", [], true);
    return (int)$r["total"];
}
