<?php
require_once(ROOT . "/db/database.php");

// Types de cibles autorisés (évite toute valeur arbitraire)
const JAIME_TYPES = ["article", "commentaire"];

// Nombre de "j'aime" pour une cible (article ou commentaire)
function compterJaimes(string $type, int $id_cible): int {
    $sql = "SELECT COUNT(*) as total FROM jaime
            WHERE type = :type AND id_cible = :id_cible";
    $result = executeSelect($sql, ["type" => $type, "id_cible" => $id_cible], true);
    return (int)$result["total"];
}

// L'utilisateur a-t-il déjà aimé cette cible ?
function aAime(string $type, int $id_cible, int $id_utilisateur): bool {
    $sql = "SELECT COUNT(*) as total FROM jaime
            WHERE type = :type AND id_cible = :id_cible AND id_utilisateur = :id_utilisateur";
    $result = executeSelect($sql, [
        "type"           => $type,
        "id_cible"       => $id_cible,
        "id_utilisateur" => $id_utilisateur,
    ], true);
    return (int)$result["total"] > 0;
}

// Ajoute un "j'aime" (idempotent grâce à la contrainte unique)
function ajouterJaime(string $type, int $id_cible, int $id_utilisateur): void {
    $sql = "INSERT INTO jaime (type, id_cible, id_utilisateur)
            VALUES (:type, :id_cible, :id_utilisateur)
            ON CONFLICT (id_utilisateur, type, id_cible) DO NOTHING";
    executeUpdate($sql, [
        "type"           => $type,
        "id_cible"       => $id_cible,
        "id_utilisateur" => $id_utilisateur,
    ]);
}

// Retire un "j'aime"
function retirerJaime(string $type, int $id_cible, int $id_utilisateur): void {
    $sql = "DELETE FROM jaime
            WHERE type = :type AND id_cible = :id_cible AND id_utilisateur = :id_utilisateur";
    executeUpdate($sql, [
        "type"           => $type,
        "id_cible"       => $id_cible,
        "id_utilisateur" => $id_utilisateur,
    ]);
}

// Bascule l'état "j'aime" et renvoie le nouvel état + le total
function basculerJaime(string $type, int $id_cible, int $id_utilisateur): array {
    if (aAime($type, $id_cible, $id_utilisateur)) {
        retirerJaime($type, $id_cible, $id_utilisateur);
        $aime = false;
    } else {
        ajouterJaime($type, $id_cible, $id_utilisateur);
        $aime = true;
    }
    return ["aime" => $aime, "total" => compterJaimes($type, $id_cible)];
}

// Ids des cibles aimées par un utilisateur parmi une liste donnée (une seule requête)
function jaimesUtilisateur(string $type, int $id_utilisateur, array $ids_cibles): array {
    $ids_cibles = array_values(array_filter(array_map("intval", $ids_cibles)));
    if (empty($ids_cibles)) return [];

    $placeholders = [];
    $params = ["type" => $type, "id_utilisateur" => $id_utilisateur];
    foreach ($ids_cibles as $i => $id) {
        $key = "id_$i";
        $placeholders[] = ":$key";
        $params[$key] = $id;
    }

    $sql = "SELECT id_cible FROM jaime
            WHERE type = :type AND id_utilisateur = :id_utilisateur
            AND id_cible IN (" . implode(", ", $placeholders) . ")";
    $rows = executeSelect($sql, $params);

    return array_map("intval", array_column($rows, "id_cible"));
}

// Contexte complet pour afficher un article et ses commentaires
function contexteJaimesArticle(int $id_article, array $commentaires): array {
    $id_utilisateur = isConnected() ? (int)$_SESSION["user"]["id_utilisateur"] : 0;
    $ids_com = array_column($commentaires, "id_commentaire");

    return [
        "aimeArticle"       => $id_utilisateur ? aAime("article", $id_article, $id_utilisateur) : false,
        "commentairesAimes" => $id_utilisateur ? jaimesUtilisateur("commentaire", $id_utilisateur, $ids_com) : [],
    ];
}
