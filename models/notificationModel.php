<?php
require_once(ROOT . "/db/database.php");

/**
 * Modèle des notifications (cloche).
 * Espaces possibles : article, commentaire, signalement, newsletter, contact, utilisateur.
 */

// Crée une notification pour un utilisateur précis.
function ajouterNotification(int $idUtilisateur, string $espace, string $message, ?string $lien = null): void {
    try {
        $sql = "INSERT INTO notification (id_utilisateur, espace, message, lien)
                VALUES (:id_utilisateur, :espace, :message, :lien)";
        executeUpdate($sql, [
            "id_utilisateur" => $idUtilisateur,
            "espace"         => $espace,
            "message"        => $message,
            "lien"           => $lien,
        ]);
    } catch (\Throwable $e) {
        // Ne jamais bloquer l'action principale si la table de notifs manque.
    }
}

// Crée une notification pour TOUS les admins.
function notifierAdmins(string $espace, string $message, ?string $lien = null): void {
    $admins = executeSelect(
        "SELECT id_utilisateur FROM utilisateur WHERE role = 'admin'"
    );
    foreach ($admins as $admin) {
        ajouterNotification((int)$admin["id_utilisateur"], $espace, $message, $lien);
    }
}

// Nombre de notifications non lues pour un utilisateur (badge cloche).
function countNotificationsNonLues(int $idUtilisateur): int {
    try {
        $result = executeSelect(
            "SELECT COUNT(*) AS total FROM notification WHERE id_utilisateur = :id AND lu = FALSE",
            ["id" => $idUtilisateur],
            true
        );
        return (int)$result["total"];
    } catch (\Throwable $e) {
        return 0;
    }
}

// Liste des notifications d'un utilisateur, les plus récentes d'abord.
function findNotifications(int $idUtilisateur, int $limite = 30): array {
    try {
        $sql = "SELECT id_notification, espace, message, lien, lu, date_creation
                FROM notification
                WHERE id_utilisateur = :id
                ORDER BY date_creation DESC, id_notification DESC
                LIMIT :limite";
        return executeSelect($sql, ["id" => $idUtilisateur, "limite" => $limite]);
    } catch (\Throwable $e) {
        return [];
    }
}

// Marque comme lues les notifications d'un utilisateur.
// Si $espace est fourni, ne marque que cet espace ; sinon tout.
function marquerNotificationsLues(int $idUtilisateur, ?string $espace = null): void {
    try {
        if ($espace !== null) {
            executeUpdate(
                "UPDATE notification SET lu = TRUE WHERE id_utilisateur = :id AND espace = :espace",
                ["id" => $idUtilisateur, "espace" => $espace]
            );
        } else {
            executeUpdate(
                "UPDATE notification SET lu = TRUE WHERE id_utilisateur = :id",
                ["id" => $idUtilisateur]
            );
        }
    } catch (\Throwable $e) {
        // Silencieux.
    }
}
