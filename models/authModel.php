<?php
require_once(ROOT . "/db/database.php");

function login(string $email) {
    $sql = "SELECT * FROM utilisateur WHERE email ILIKE :email";
    return executeSelect($sql, ["email" => $email], true);
}

function emailExists(string $email): bool {
    $sql = "SELECT COUNT(*) as count FROM utilisateur WHERE email ILIKE :email";
    $result = executeSelect($sql, ["email" => $email], true);
    return $result["count"] > 0;
}

function registerUser(array $data): bool {
    //seuls auteur et lecteur peuvent s'inscrire
    $rolesAutorises = ["auteur", "lecteur"];
    if (!in_array($data["role"], $rolesAutorises)) {
        return false;
    }

    $sql = "INSERT INTO utilisateur (nom, prenom, email, mdp, role)
            VALUES (:nom, :prenom, :email, :mdp, :role)";
    executeUpdate($sql, [
        "nom"    => $data["nom"],
        "prenom" => $data["prenom"],
        "email"  => $data["email"],
        "mdp"    => password_hash($data["mdp"], PASSWORD_DEFAULT),
        "role"   => $data["role"],
    ]);
    return true;
}

// ── MOTS DE PASSE ──

// Compare en acceptant les anciens mots de passe stockés en clair
// pour permettre une migration sans casser les comptes existants.
function motDePasseValide(string $saisi, string $stocke): bool {
    if (str_starts_with($stocke, '$2')) {
        return password_verify($saisi, $stocke);
    }
    return hash_equals($stocke, $saisi);
}

// Retourne un nouveau hash à enregistrer si la migration est nécessaire, sinon null.
function rehashMotDePasseSiNecessaire(string $saisi, string $stocke): ?string {
    if (str_starts_with($stocke, '$2')) {
        return password_needs_rehash($stocke, PASSWORD_DEFAULT)
            ? password_hash($saisi, PASSWORD_DEFAULT)
            : null;
    }
    // Ancien mot de passe en clair : on le hache.
    return $stocke === $saisi ? password_hash($saisi, PASSWORD_DEFAULT) : null;
}