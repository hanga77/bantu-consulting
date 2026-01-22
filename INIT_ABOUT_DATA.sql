-- Script pour initialiser les données de la table 'about'
-- À exécuter si la table about est vide

-- Vérifier et ajouter une ligne par défaut si vide
INSERT INTO about (id, motto, description) 
SELECT 1, 
       'Excellence, Intégrité, Innovation',
       'Bantu Consulting est un cabinet de conseil leader en transformation numérique et stratégie business. Depuis plus de 10 ans, nous accompagnons les entreprises à relever leurs plus grands défis à travers des solutions innovantes et durables. Notre équipe multidisciplinaire d\'experts travaille sans relâche pour transformer vos ambitions en succès concret.'
WHERE NOT EXISTS (SELECT 1 FROM about WHERE id = 1);

-- Si vous voulez remplacer complètement, utilisez :
-- DELETE FROM about;
-- INSERT INTO about (motto, description) VALUES (
--   'Excellence, Intégrité, Innovation',
--   'Bantu Consulting est un cabinet de conseil leader...'
-- );
