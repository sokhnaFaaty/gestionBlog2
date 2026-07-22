-- Migration : table des messages de contact
-- À exécuter une fois sur la base LOCALE et sur la base de PROD (AlwaysData).
--   psql -h <host> -U <user> -d <dbname> -f db/migration_contact.sql

CREATE TABLE IF NOT EXISTS contact (
    id_contact SERIAL PRIMARY KEY,
    nom        VARCHAR(150) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    sujet      VARCHAR(200) NOT NULL,
    message    TEXT         NOT NULL,
    date_envoi TIMESTAMP    NOT NULL DEFAULT NOW()
);
