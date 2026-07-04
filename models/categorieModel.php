<?php
require_once(ROOT . "/db/database.php");

// ── LECTURE 

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
        $sql    = "SELECT COUNT(*) as total FROM categorie 
                   WHERE libelle ILIKE :libelle AND id_categorie != :id";
        $result = executeSelect($sql, ["libelle" => $libelle, "id" => $excludeId], true);
    } else {
        $sql    = "SELECT COUNT(*) as total FROM categorie WHERE libelle ILIKE :libelle";
        $result = executeSelect($sql, ["libelle" => $libelle], true);
    }
    return (int)$result["total"] > 0;
}

// ── CRÉATION / MODIFICATION / SUPPRESSION 

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
// ── PAGINATION ──────────────────────────────────────

function findAllCategoriesPaginees(int $page = 1, int $parPage = 10): array {
    $offset = ($page - 1) * $parPage;
    $sql = "SELECT c.id_categorie, c.libelle,
            (SELECT COUNT(*) FROM article_categorie ac WHERE ac.id_categorie = c.id_categorie) as nb_articles
            FROM categorie c
            ORDER BY c.libelle ASC
            LIMIT :limit OFFSET :offset";
    return executeSelect($sql, ["limit" => $parPage, "offset" => $offset]);
}

function countAllCategories(): int {
    $sql    = "SELECT COUNT(*) as total FROM categorie";
    $result = executeSelect($sql, [], true);
    return (int)$result["total"];
}