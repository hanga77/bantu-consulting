<?php
require_once '../config/database.php';

try {
    // Vérifier si la colonne existe
    $stmt = $pdo->query("SHOW COLUMNS FROM site_settings LIKE 'presentation_video'");
    $exists = $stmt->rowCount() > 0;
    
    if (!$exists) {
        // Ajouter la colonne
        $pdo->exec("ALTER TABLE site_settings ADD COLUMN presentation_video VARCHAR(500) DEFAULT NULL AFTER id");
        echo "✓ Colonne 'presentation_video' ajoutée";
    } else {
        echo "✓ Colonne 'presentation_video' existe déjà";
    }
    
    // Vérifier si la table existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'site_settings'");
    if ($stmt->rowCount() == 0) {
        // Créer la table
        $pdo->exec("
            CREATE TABLE site_settings (
                id INT PRIMARY KEY DEFAULT 1,
                site_name VARCHAR(255),
                site_description TEXT,
                footer_text TEXT,
                contact_email VARCHAR(255),
                contact_email2 VARCHAR(255),
                phone VARCHAR(20),
                address VARCHAR(500),
                facebook_url VARCHAR(500),
                twitter_url VARCHAR(500),
                linkedin_url VARCHAR(500),
                instagram_url VARCHAR(500),
                meta_title VARCHAR(255),
                meta_description TEXT,
                site_keywords TEXT,
                latitude VARCHAR(20),
                longitude VARCHAR(20),
                presentation_video VARCHAR(500),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
        echo "✓ Table 'site_settings' créée";
    } else {
        echo "✓ Table 'site_settings' existe déjà";
    }
    
} catch (Exception $e) {
    echo "✗ Erreur: " . $e->getMessage();
}
?>
