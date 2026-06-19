<?php
require_once(ROOT . "/db/database.php");

function findArticlesPublies(): array {
    $sql = "SELECT a.*, u.nom as utilisateur_nom, c.libelle as categorie_nom,
            (SELECT COUNT(*) FROM commentaire WHERE id_article = a.id_article) as nb_commentaires
            FROM article a
            INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
            INNER JOIN article_categorie ac ON a.id_article = ac.id_article
            INNER JOIN categorie c ON ac.id_categorie = c.id_categorie
            WHERE a.statut = 'Publie' AND (u.banni IS NULL OR u.banni = false)
            ORDER BY a.date_publication DESC";
    return executeSelect($sql, []);
}

function findArticleById(int $id): array|false {
    $sql = "SELECT a.*, u.nom as utilisateur_nom, c.libelle as categorie_nom
            FROM article a
            INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
            INNER JOIN article_categorie ac ON a.id_article = ac.id_article
            INNER JOIN categorie c ON ac.id_categorie = c.id_categorie
            WHERE a.id_article = :id AND a.statut = 'Publie'";
    return executeSelect($sql, ["id" => $id], true);
}

function findCommentairesByArticle(int $id): array {
    $sql = "SELECT c.*, u.nom as utilisateur_nom
            FROM commentaire c
            INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
            WHERE c.id_article = :id
            ORDER BY c.date_commentaire ASC";
    return executeSelect($sql, ["id" => $id]);
}

function addCommentaire(int $id_article, int $id_utilisateur, string $contenu): void {
    $sql = "INSERT INTO commentaire (id_article, id_utilisateur, contenu)
            VALUES (:id_article, :id_utilisateur, :contenu)";
    executeUpdate($sql, [
        "id_article"     => $id_article,
        "id_utilisateur" => $id_utilisateur,
        "contenu"        => $contenu,
    ]);
}

function modifierCommentaire(int $id_commentaire, int $id_utilisateur, string $contenu): void {
    $sql = "UPDATE commentaire SET contenu = :contenu
            WHERE id_commentaire = :id AND id_utilisateur = :id_utilisateur";
    executeUpdate($sql, [
        "contenu"        => $contenu,
        "id"             => $id_commentaire,
        "id_utilisateur" => $id_utilisateur,
    ]);
}

function supprimerCommentaire(int $id_commentaire, int $id_utilisateur): void {
    $sql = "DELETE FROM commentaire
            WHERE id_commentaire = :id AND id_utilisateur = :id_utilisateur";
    executeUpdate($sql, [
        "id"             => $id_commentaire,
        "id_utilisateur" => $id_utilisateur,
    ]);
}

function signalerCommentaire(int $id_commentaire, int $id_utilisateur): void {
    $check = "SELECT COUNT(*) as total FROM signalement_commentaire
              WHERE id_commentaire = :id_commentaire AND id_utilisateur = :id_utilisateur";
    $res = executeSelect($check, [
        "id_commentaire" => $id_commentaire,
        "id_utilisateur" => $id_utilisateur,
    ], true);

    if ((int)$res["total"] === 0) {
        $sql = "INSERT INTO signalement_commentaire (id_commentaire, id_utilisateur)
                VALUES (:id_commentaire, :id_utilisateur)";
        executeUpdate($sql, [
            "id_commentaire" => $id_commentaire,
            "id_utilisateur" => $id_utilisateur,
        ]);
    }
}

function signalerArticle(int $id_article, int $id_utilisateur): void {
    $check = "SELECT COUNT(*) as total FROM signalement
              WHERE id_article = :id_article AND id_utilisateur = :id_utilisateur";
    $res = executeSelect($check, [
        "id_article"     => $id_article,
        "id_utilisateur" => $id_utilisateur,
    ], true);

    if ((int)$res["total"] === 0) {
        $sql = "INSERT INTO signalement (id_article, id_utilisateur)
                VALUES (:id_article, :id_utilisateur)";
        executeUpdate($sql, [
            "id_article"     => $id_article,
            "id_utilisateur" => $id_utilisateur,
        ]);
    }
}
