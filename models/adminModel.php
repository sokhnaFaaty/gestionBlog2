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