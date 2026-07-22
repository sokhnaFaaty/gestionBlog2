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
        "mdp"    => $data["mdp"],
        "role"   => $data["role"],
    ]);
    return true;
}