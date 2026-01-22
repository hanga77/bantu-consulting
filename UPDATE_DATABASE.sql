-- Script de mise à jour de la base de données
-- À exécuter si vous avez déjà une base de données existante

-- Ajouter la colonne department_id à la table teams si elle n'existe pas
ALTER TABLE teams ADD COLUMN department_id INT DEFAULT NULL;
ALTER TABLE teams ADD CONSTRAINT fk_teams_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL;

-- Créer la table service_files si elle n'existe pas
CREATE TABLE IF NOT EXISTS service_files (
    id INT PRIMARY KEY AUTO_INCREMENT,
    service_id INT,
    file_name VARCHAR(255),
    file_path VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Ajouter les colonnes manquantes à la table services si nécessaire
ALTER TABLE services ADD COLUMN IF NOT EXISTS icon VARCHAR(50) DEFAULT NULL;

-- Optionnel : Créer quelques départements par défaut si la table est vide
-- Décommentez si vous le souhaitez :
-- INSERT INTO departments (name, description, department_type) VALUES
-- ('Pôle Informatique', 'Services et solutions informatiques', 'Technique'),
-- ('Pôle Conseil', 'Services de conseil et expertise', 'Conseil'),
-- ('Pôle Support', 'Support client et maintenance', 'Support');

-- Vérifier que tout est en place
SELECT 'Mise à jour complète !' as status;
