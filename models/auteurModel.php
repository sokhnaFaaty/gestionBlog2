<?php
require_once(ROOT . "/db/database.php");

function findAllArticles(): array {
    $id_utilisateur = $_SESSION['user']['id_utilisateur'];

    $sql = "SELECT a.*, u.nom as utilisateur_nom, c.libelle as categorie_nom
            FROM article a
            INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
            INNER JOIN article_categorie ac ON a.id_article = ac.id_article
            INNER JOIN categorie c ON ac.id_categorie = c.id_categorie
            WHERE a.id_utilisateur = :id_utilisateur
            ORDER BY a.date_publication DESC";

    return executeSelect($sql, ["id_utilisateur" => $id_utilisateur]);
}

function findAllCategories(): array {
    $sql = "SELECT id_categorie, libelle FROM categorie ORDER BY libelle ASC";
    return executeSelect($sql, []);
}

function addArticle(array $article, int $id_categorie): void {
    $sql = "INSERT INTO article (titre, contenu, image, statut, id_utilisateur)
            VALUES (:titre, :contenu, :image, :statut, :id_utilisateur)
            RETURNING id_article";

    $res        = executeSelect($sql, $article, true);
    $id_article = $res['id_article'];

    $sql_intermediaire = "INSERT INTO article_categorie (id_article, id_categorie)
                          VALUES (:id_article, :id_categorie)";
    executeUpdate($sql_intermediaire, [
        'id_article'   => $id_article,
        'id_categorie' => $id_categorie,
    ]);
}

// Renommée pour correspondre à l'appel dans auteurController
function findArticleById_utilisateur(int $id): array|false {
    $sql = "SELECT a.*, ac.id_categorie
            FROM article a
            INNER JOIN article_categorie ac ON a.id_article = ac.id_article
            WHERE a.id_article = :id";
    return executeSelect($sql, ["id" => $id], true);
}

function updateArticle(array $data, int $id_categorie): void {
    $sql = "UPDATE article
            SET titre   = :titre,
                contenu = :contenu,
                image   = :image,
                statut  = 'En attente'
            WHERE id_article = :id_article";
    executeUpdate($sql, [
        "titre"      => $data["titre"],
        "contenu"    => $data["contenu"],
        "image"      => $data["image"],
        "id_article" => $data["id_article"],
    ]);

    $sql_cat = "UPDATE article_categorie
                SET id_categorie = :id_categorie
                WHERE id_article = :id_article";
    executeUpdate($sql_cat, [
        "id_categorie" => $id_categorie,
        "id_article"   => $data["id_article"],
    ]);
}

function deleteArticle(int $id): void {
    $sql = "DELETE FROM article WHERE id_article = :id";
    executeUpdate($sql, ["id" => $id]);
}