<?php
require_once(ROOT . "/db/database.php");

// ── LECTURE 

// Tous les auteurs
function findAllAuteurs(): array {
    $sql = "SELECT u.*,
            (SELECT COUNT(*) FROM article WHERE id_utilisateur = u.id_utilisateur) as nb_articles
            FROM utilisateur u
            WHERE u.role = 'auteur'
            ORDER BY u.nom ASC";
    return executeSelect($sql, []);
}

// Tous les admins
function findAllAdmins(): array {
    $sql = "SELECT * FROM utilisateur WHERE role = 'admin' ORDER BY nom ASC";
    return executeSelect($sql, []);
}

// ── COMPTAGE 

function countUtilisateursByRole(string $role): int {
    $sql    = "SELECT COUNT(*) as total FROM utilisateur WHERE role = :role";
    $result = executeSelect($sql, ["role" => $role], true);
    return (int)$result["total"];
}

// ── VÉRIFICATION 

function emailAdminExiste(string $email): bool {
    $sql    = "SELECT COUNT(*) as total FROM utilisateur WHERE email ILIKE :email";
    $result = executeSelect($sql, ["email" => $email], true);
    return (int)$result["total"] > 0;
}

// ── CRÉATION / MODIFICATION / SUPPRESSION ──

function addAdmin(array $data): void {
    $sql = "INSERT INTO utilisateur (nom, prenom, email, mdp, role)
            VALUES (:nom, :prenom, :email, :mdp, 'admin')";
    executeUpdate($sql, [
        "nom"    => $data["nom"],
        "prenom" => $data["prenom"],
        "email"  => $data["email"],
        "mdp"    => $data["password"],
    ]);
}

function deleteAdmin(int $id): void {
    $sql = "DELETE FROM utilisateur WHERE id_utilisateur = :id AND role = 'admin'";
    executeUpdate($sql, ["id" => $id]);
}

function toggleBanAuteur(int $id): void {
    $sql = "UPDATE utilisateur SET banni = NOT banni WHERE id_utilisateur = :id";
    executeUpdate($sql, ["id" => $id]);
}