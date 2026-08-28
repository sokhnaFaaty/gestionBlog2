<?php
require_once(ROOT . "/db/database.php");

// ── LECTURE ──

// Tous les articles d'un auteur connecté
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

// Tous les articles (admin)
function findAllArticlesAdmin(?string $statut = null): array {
    $where  = $statut ? "WHERE a.statut = :statut" : "";
    $params = $statut ? ["statut" => $statut] : [];
    $sql = "SELECT a.*, u.nom as utilisateur_nom, u.banni,
            c.libelle as categorie_nom,
            (SELECT COUNT(*) FROM signalement WHERE id_article = a.id_article) as nb_signalements,
            (SELECT COUNT(*) FROM commentaire WHERE id_article = a.id_article) as nb_commentaires
            FROM article a
            INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
            INNER JOIN article_categorie ac ON a.id_article = ac.id_article
            INNER JOIN categorie c ON ac.id_categorie = c.id_categorie
            $where
            ORDER BY a.date_publication DESC";
    return executeSelect($sql, $params);
}

// Articles publiés (lecteur)
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

// Un article par id (public)
function findArticleById(int $id): array|false {
    $sql = "SELECT a.*, u.nom as utilisateur_nom, c.libelle as categorie_nom
            FROM article a
            INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
            INNER JOIN article_categorie ac ON a.id_article = ac.id_article
            INNER JOIN categorie c ON ac.id_categorie = c.id_categorie
            WHERE a.id_article = :id AND a.statut = 'Publie'";
    return executeSelect($sql, ["id" => $id], true);
}

// Un article par id pour l'auteur (sans filtre statut)
function findArticleById_utilisateur(int $id): array|false {
    $sql = "SELECT a.*, ac.id_categorie
            FROM article a
            INNER JOIN article_categorie ac ON a.id_article = ac.id_article
            WHERE a.id_article = :id";
    return executeSelect($sql, ["id" => $id], true);
}

// 5 derniers articles (dashboard admin)
function findDerniersArticles(): array {
    $sql = "SELECT a.titre, a.statut, a.date_publication, u.nom AS utilisateur_nom
            FROM article a
            INNER JOIN utilisateur u ON u.id_utilisateur = a.id_utilisateur
            ORDER BY a.date_publication DESC
            LIMIT 5";
    return executeSelect($sql, []);
}

// ── COMPTAGE ─

function countArticlesByStatut(string $statut): int {
    $sql    = "SELECT COUNT(*) as total FROM article WHERE statut = :statut";
    $result = executeSelect($sql, ["statut" => $statut], true);
    return (int)$result["total"];
}

// ── CRÉATION / MODIFICATION / SUPPRESSION 

function addArticle(array $article, int $id_categorie): void {
    $sql = "INSERT INTO article (titre, contenu, image, statut, id_utilisateur)
            VALUES (:titre, :contenu, :image, :statut, :id_utilisateur)
            RETURNING id_article";
    $res        = executeSelect($sql, $article, true);
    $id_article = $res['id_article'];

    $sql_cat = "INSERT INTO article_categorie (id_article, id_categorie)
                VALUES (:id_article, :id_categorie)";
    executeUpdate($sql_cat, [
        'id_article'   => $id_article,
        'id_categorie' => $id_categorie,
    ]);

    require_once(ROOT . "/models/notificationModel.php");
    notifierAdmins(
        "article",
        "Nouvel article en attente : « " . $article["titre"] . " »",
        path("article", "listeAdmin")
    );
}

function updateArticle(array $data, int $id_categorie): void {
    $sql = "UPDATE article
            SET titre = :titre, contenu = :contenu, image = :image, statut = 'En attente'
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

function updateStatutArticle(int $id, string $statut): void {
    $avant = executeSelect(
        "SELECT titre, id_utilisateur FROM article WHERE id_article = :id",
        ["id" => $id],
        true
    );

    $sql = "UPDATE article SET statut = :statut WHERE id_article = :id";
    executeUpdate($sql, ["statut" => $statut, "id" => $id]);

    // Notifie l'auteur quand son article change de statut
    if ($avant) {
        require_once(ROOT . "/models/notificationModel.php");
        $titre  = $avant["titre"];
        $auteur = (int)$avant["id_utilisateur"];
        $lien   = path("article", "voir", ["id" => $id]);

        if ($statut === "Publie") {
            ajouterNotification($auteur, "article", "Votre article « " . $titre . " » a été publié.", $lien);
        } elseif ($statut === "Rejete") {
            ajouterNotification($auteur, "article", "Votre article « " . $titre . " » a été rejeté.", $lien);
        }
    }
}

function deleteArticle(int $id): void {
    $sql = "DELETE FROM article WHERE id_article = :id";
    executeUpdate($sql, ["id" => $id]);
}

// ── SIGNALEMENT ──

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

        require_once(ROOT . "/models/notificationModel.php");
        $article = executeSelect(
            "SELECT titre FROM article WHERE id_article = :id",
            ["id" => $id_article],
            true
        );
        notifierAdmins(
            "signalement",
            "L'article « " . ($article["titre"] ?? "sans titre") . " » a été signalé.",
            path("article", "listeAdmin")
        );
    }
}

// ── RECHERCHE 

function searchGlobal(string $query): array {
    $q = '%' . $query . '%';
    $articles = executeSelect(
        "SELECT id_article, titre, statut FROM article WHERE titre ILIKE :q LIMIT 5",
        ['q' => $q]
    );
    $auteurs = executeSelect(
        "SELECT id_utilisateur, nom, prenom FROM utilisateur
         WHERE role = 'auteur' AND (nom ILIKE :q1 OR prenom ILIKE :q2 OR email ILIKE :q3) LIMIT 5",
        ['q1' => $q, 'q2' => $q, 'q3' => $q]
    );
    $categories = executeSelect(
        "SELECT id_categorie, libelle FROM categorie WHERE libelle ILIKE :q LIMIT 5",
        ['q' => $q]
    );
    return compact('articles', 'auteurs', 'categories');
}

//pagination
// Mes articles paginés (auteur)
function findAllArticlesPagines(int $page = 1, int $parPage = 6): array {
    $id_utilisateur = $_SESSION['user']['id_utilisateur'];
    $offset = ($page - 1) * $parPage;
    $sql = "SELECT a.*, u.nom as utilisateur_nom, c.libelle as categorie_nom
            FROM article a
            INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
            INNER JOIN article_categorie ac ON a.id_article = ac.id_article
            INNER JOIN categorie c ON ac.id_categorie = c.id_categorie
            WHERE a.id_utilisateur = :id_utilisateur
            ORDER BY a.date_publication DESC
            LIMIT :limit OFFSET :offset";
    return executeSelect($sql, [
        "id_utilisateur" => $id_utilisateur,
        "limit"          => $parPage,
        "offset"         => $offset,
    ]);
}
function findArticlesPubliesPagines(int $page = 1, int $parPage = 6): array {
    $offset = ($page - 1) * $parPage;
    $sql = "SELECT a.*, u.nom as utilisateur_nom, c.libelle as categorie_nom,
            (SELECT COUNT(*) FROM commentaire WHERE id_article = a.id_article) as nb_commentaires
            FROM article a
            INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
            INNER JOIN article_categorie ac ON a.id_article = ac.id_article
            INNER JOIN categorie c ON ac.id_categorie = c.id_categorie
            WHERE a.statut = 'Publie' AND (u.banni IS NULL OR u.banni = false)
            ORDER BY a.date_publication DESC
            LIMIT :limit OFFSET :offset";
    return executeSelect($sql, ["limit" => $parPage, "offset" => $offset]);
}
function countArticlesPublies(): int {
    $sql = "SELECT COUNT(*) as total
            FROM article a
            INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
            WHERE a.statut = 'Publie' AND (u.banni IS NULL OR u.banni = false)";
    $result = executeSelect($sql, [], true);
    return (int)$result["total"];
}
function countAllArticles(): int {
    $id_utilisateur = $_SESSION['user']['id_utilisateur'];
    $sql = "SELECT COUNT(*) as total FROM article WHERE id_utilisateur = :id_utilisateur";
    $result = executeSelect($sql, ["id_utilisateur" => $id_utilisateur], true);
    return (int)$result["total"];
}

// Articles admin paginés
function findAllArticlesAdminPagines(?string $statut = null, int $page = 1, int $parPage = 10): array {
    $where  = $statut ? "WHERE a.statut = :statut" : "";
    $params = $statut ? ["statut" => $statut] : [];
    $params["limit"]  = $parPage;
    $params["offset"] = ($page - 1) * $parPage;
    $sql = "SELECT a.*, u.nom as utilisateur_nom, u.banni,
            c.libelle as categorie_nom,
            (SELECT COUNT(*) FROM signalement WHERE id_article = a.id_article) as nb_signalements,
            (SELECT COUNT(*) FROM commentaire WHERE id_article = a.id_article) as nb_commentaires
            FROM article a
            INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
            INNER JOIN article_categorie ac ON a.id_article = ac.id_article
            INNER JOIN categorie c ON ac.id_categorie = c.id_categorie
            $where
            ORDER BY a.date_publication DESC
            LIMIT :limit OFFSET :offset";
    return executeSelect($sql, $params);
}

function countAllArticlesAdmin(?string $statut = null): int {
    $where  = $statut ? "WHERE statut = :statut" : "";
    $params = $statut ? ["statut" => $statut] : [];
    $sql    = "SELECT COUNT(*) as total FROM article $where";
    $result = executeSelect($sql, $params, true);
    return (int)$result["total"];
}