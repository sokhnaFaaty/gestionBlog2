<?php
require_once(ROOT . "/db/database.php");

// ── ARTICLES ─────────────────────────────────────────────────────────────────

function findAllArticlesAdmin(?string $statut = null): array {
    $where = $statut ? "WHERE a.statut = :statut" : "";
    $params = $statut ? ["statut" => $statut] : [];

    $sql = "SELECT a.*, u.nom as utilisateur_nom, u.banni, c.libelle as categorie_nom,
            (SELECT COUNT(*) FROM signalement  WHERE id_article = a.id_article) as nb_signalements,
            (SELECT COUNT(*) FROM commentaire  WHERE id_article = a.id_article) as nb_commentaires
            FROM article a
            INNER JOIN utilisateur u  ON a.id_utilisateur = u.id_utilisateur
            INNER JOIN article_categorie ac ON a.id_article = ac.id_article
            INNER JOIN categorie c    ON ac.id_categorie = c.id_categorie
            $where
            ORDER BY a.date_publication DESC";

    return executeSelect($sql, $params);
}

function countUtilisateursByRole(string $role): int {
    $sql    = "SELECT COUNT(*) as total FROM utilisateur WHERE role = :role";
    $result = executeSelect($sql, ["role" => $role], true);
    return (int)$result["total"];
}

function updateStatutArticle(int $id, string $statut): void {
    $sql = "UPDATE article SET statut = :statut WHERE id_article = :id";
    executeUpdate($sql, ["statut" => $statut, "id" => $id]);
}

function findDerniersArticles(): array {
    $sql = "SELECT a.titre, a.statut, a.date_publication, u.nom as utilisateur_nom
            FROM article a
            INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
            ORDER BY a.date_publication DESC
            LIMIT 5";
    return executeSelect($sql, []);
}

function findAllAuteurs(): array {
    $sql = "SELECT u.*,
            (SELECT COUNT(*) FROM article WHERE id_utilisateur = u.id_utilisateur) as nb_articles
            FROM utilisateur u
            WHERE u.role = 'auteur'
            ORDER BY u.nom ASC";
    return executeSelect($sql, []);
}

function toggleBanAuteur(int $id): void {
    $sql = "UPDATE utilisateur SET banni = NOT banni WHERE id_utilisateur = :id";
    executeUpdate($sql, ["id" => $id]);
}
function emailAdminExiste(string $email): bool {
    $sql    = "SELECT COUNT(*) as total FROM utilisateur WHERE email ILIKE :email";
    $result = executeSelect($sql, ["email" => $email], true);
    return (int)$result["total"] > 0;
}

//admin

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

function findAllAdmins(): array {
    $sql = "SELECT * FROM utilisateur WHERE role = 'admin' ORDER BY nom ASC";
    return executeSelect($sql, []);
}

function deleteAdmin(int $id): void {
    $sql = "DELETE FROM utilisateur WHERE id_utilisateur = :id AND role = 'admin'";
    executeUpdate($sql, ["id" => $id]);
}

//categorie

function findAllCategories(): array {
    $sql = "SELECT c.id_categorie, c.libelle,
            (SELECT COUNT(*) FROM article_categorie ac WHERE ac.id_categorie = c.id_categorie) as nb_articles
            FROM categorie c
            ORDER BY c.libelle ASC";
    return executeSelect($sql, []);
}

function findCategorieById(int $id): array|false {
    $sql = "SELECT * FROM categorie WHERE id_categorie = :id";
    return executeSelect($sql, ["id" => $id], true);
}

function categorieLibelleExiste(string $libelle, ?int $excludeId = null): bool {
    if ($excludeId) {
        $sql    = "SELECT COUNT(*) as total FROM categorie WHERE libelle ILIKE :libelle AND id_categorie != :id";
        $result = executeSelect($sql, ["libelle" => $libelle, "id" => $excludeId], true);
    } else {
        $sql    = "SELECT COUNT(*) as total FROM categorie WHERE libelle ILIKE :libelle";
        $result = executeSelect($sql, ["libelle" => $libelle], true);
    }
    return (int)$result["total"] > 0;
}

function addCategorie(string $libelle): void {
    $sql = "INSERT INTO categorie (libelle) VALUES (:libelle)";
    executeUpdate($sql, ["libelle" => $libelle]);
}

function updateCategorie(int $id, string $libelle): void {
    $sql = "UPDATE categorie SET libelle = :libelle WHERE id_categorie = :id";
    executeUpdate($sql, ["libelle" => $libelle, "id" => $id]);
}

function deleteCategorie(int $id): void {
    $sql = "DELETE FROM categorie WHERE id_categorie = :id";
    executeUpdate($sql, ["id" => $id]);
}