<?php
require_once(ROOT . "/db/database.php");


// Commentaires d'un article
function findCommentairesByArticle(int $id_article): array {
    $sql = "SELECT c.*, u.nom as utilisateur_nom
            FROM commentaire c
            INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
            WHERE c.id_article = :id
            ORDER BY c.date_commentaire ASC";
    return executeSelect($sql, ["id" => $id_article]);
}

// Commentaires signalés (admin)
function findSignalementsCommentaires(): array {
    $sql = "SELECT c.id_commentaire, c.contenu, c.date_commentaire, c.id_article,
            u.nom as auteur_nom,
            a.titre as article_titre,
            COUNT(sc.id_utilisateur) as nb_signalements
            FROM commentaire c
            INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
            INNER JOIN article a ON c.id_article = a.id_article
            INNER JOIN signalement_commentaire sc ON c.id_commentaire = sc.id_commentaire
            GROUP BY c.id_commentaire, u.nom, a.titre, a.id_article
            ORDER BY nb_signalements DESC, c.date_commentaire DESC";
    return executeSelect($sql, []);
}

// ── CRÉATION / MODIFICATION / SUPPRESSION ────────────

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

// Suppression par admin
function deleteCommentaire(int $id): void {
    $sql = "DELETE FROM commentaire WHERE id_commentaire = :id";
    executeUpdate($sql, ["id" => $id]);
}

// Suppression par le propriétaire
function supprimerCommentaire(int $id_commentaire, int $id_utilisateur): void {
    $sql = "DELETE FROM commentaire
            WHERE id_commentaire = :id AND id_utilisateur = :id_utilisateur";
    executeUpdate($sql, [
        "id"             => $id_commentaire,
        "id_utilisateur" => $id_utilisateur,
    ]);
}

// ── SIGNALEMENT 

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