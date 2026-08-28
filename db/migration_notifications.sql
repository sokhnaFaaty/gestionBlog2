-- Migration : système de notifications (cloche)
-- Exécuter une seule fois sur la base PostgreSQL.

CREATE TABLE IF NOT EXISTS "notification" (
    "id_notification" SERIAL PRIMARY KEY,
    "id_utilisateur" INTEGER NOT NULL REFERENCES "utilisateur"("id_utilisateur") ON DELETE CASCADE,
    "espace" VARCHAR(50) NOT NULL,          -- article, commentaire, signalement, newsletter, contact, utilisateur
    "message" TEXT NOT NULL,
    "lien" VARCHAR(255) DEFAULT NULL,       -- URL optionnelle vers l'élément concerné
    "lu" BOOLEAN NOT NULL DEFAULT FALSE,
    "date_creation" TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_notification_utilisateur ON "notification"("id_utilisateur");
