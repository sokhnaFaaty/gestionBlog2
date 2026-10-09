-- Migration : système de "j'aime" (articles et commentaires)
-- À exécuter une fois sur la base LOCALE et sur la base de PROD (AlwaysData).
--   psql -h <host> -U <user> -d <dbname> -f db/migration_jaime.sql

CREATE TABLE IF NOT EXISTS "jaime" (
    "id_jaime"       SERIAL PRIMARY KEY,
    "id_utilisateur" INTEGER NOT NULL REFERENCES "utilisateur"("id_utilisateur") ON DELETE CASCADE,
    "type"           VARCHAR(20) NOT NULL,   -- article | commentaire
    "id_cible"       INTEGER NOT NULL,       -- id de l'article ou du commentaire aimé
    "date_creation"  TIMESTAMP NOT NULL DEFAULT NOW(),
    CONSTRAINT "jaime_unique" UNIQUE ("id_utilisateur", "type", "id_cible")
);

CREATE INDEX IF NOT EXISTS idx_jaime_cible ON "jaime"("type", "id_cible");
