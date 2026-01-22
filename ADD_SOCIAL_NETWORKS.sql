-- Script pour ajouter les réseaux sociaux aux members et services
-- À exécuter si vous avez déjà une base de données existante

-- Ajouter les colonnes réseaux sociaux à teams (membres)
ALTER TABLE teams ADD COLUMN linkedin VARCHAR(255) DEFAULT NULL;
ALTER TABLE teams ADD COLUMN twitter VARCHAR(255) DEFAULT NULL;
ALTER TABLE teams ADD COLUMN facebook VARCHAR(255) DEFAULT NULL;
ALTER TABLE teams ADD COLUMN instagram VARCHAR(255) DEFAULT NULL;
ALTER TABLE teams ADD COLUMN website VARCHAR(255) DEFAULT NULL;

-- Ajouter les colonnes pour services
ALTER TABLE services ADD COLUMN website VARCHAR(255) DEFAULT NULL;
ALTER TABLE services ADD COLUMN contact_email VARCHAR(100) DEFAULT NULL;
ALTER TABLE services ADD COLUMN contact_phone VARCHAR(20) DEFAULT NULL;

-- Mettre à jour setup.sql pour les nouvelles installations
-- (les modifications sont déjà dans setup.sql)
