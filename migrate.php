<?php
/**
 * Script de migration - Ajoute les colonnes manquantes à la base de données
 * Accédez à http://localhost/Bantu-test2/migrate.php
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bantu_consulting');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo '<div style="font-family: Arial; margin: 50px;">';
    echo '<h1>🔧 Migration de la Base de Données</h1>';
    echo '<hr>';

    // Vérifier si la colonne presentation_video existe
    $result = $pdo->query("SHOW COLUMNS FROM site_settings LIKE 'presentation_video'");
    if ($result->rowCount() === 0) {
        echo '<p>Ajout de la colonne <strong>presentation_video</strong>...</p>';
        $pdo->exec("ALTER TABLE site_settings ADD COLUMN presentation_video VARCHAR(500) AFTER address");
        echo '<p style="color: green;">✅ Colonne presentation_video ajoutée</p>';
    } else {
        echo '<p style="color: blue;">✓ Colonne presentation_video existe déjà</p>';
    }

    // Vérifier et ajouter les autres colonnes manquantes
    $columns_to_add = [
        ['name' => 'meta_title', 'definition' => 'VARCHAR(255)', 'after' => 'presentation_video'],
        ['name' => 'meta_description', 'definition' => 'VARCHAR(255)', 'after' => 'meta_title'],
        ['name' => 'footer_text', 'definition' => 'TEXT', 'after' => 'meta_description'],
    ];

    foreach ($columns_to_add as $column) {
        $result = $pdo->query("SHOW COLUMNS FROM site_settings LIKE '{$column['name']}'");
        if ($result->rowCount() === 0) {
            echo '<p>Ajout de la colonne <strong>' . $column['name'] . '</strong>...</p>';
            $pdo->exec("ALTER TABLE site_settings ADD COLUMN {$column['name']} {$column['definition']} AFTER {$column['after']}");
            echo '<p style="color: green;">✅ Colonne ' . $column['name'] . ' ajoutée</p>';
        } else {
            echo '<p style="color: blue;">✓ Colonne ' . $column['name'] . ' existe déjà</p>';
        }
    }

    // Mettre à jour les données par défaut si nécessaire
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM site_settings");
    $result = $stmt->fetch();

    if ($result['count'] === 0) {
        echo '<p>Création des paramètres par défaut...</p>';
        $pdo->exec("INSERT INTO site_settings (
            site_name, site_description, site_keywords, 
            contact_email, contact_email2, phone, address,
            presentation_video,
            meta_title, meta_description, footer_text
        ) VALUES (
            'Bantu Consulting',
            'Solutions innovantes en conseil, stratégie et transformation digitale',
            'consulting, conseil, stratégie, transformation digitale',
            'contact@bantu-consulting.com',
            'info@bantu-consulting.com',
            '+243 818 818 818',
            'Kinshasa, République Démocratique du Congo',
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'Bantu Consulting | Conseil & Stratégie',
            'Solutions complètes de conseil en stratégie et transformation digitale',
            '© 2024 Bantu Consulting. Tous droits réservés.'
        )");
        echo '<p style="color: green;">✅ Paramètres par défaut créés</p>';
    } else {
        echo '<p style="color: blue;">✓ Paramètres existent déjà</p>';
    }

    // Créer la table project_images si elle n'existe pas
    echo '<p>Création de la table <strong>project_images</strong>...</p>';
    $pdo->exec("CREATE TABLE IF NOT EXISTS project_images (
        id INT PRIMARY KEY AUTO_INCREMENT,
        project_id INT NOT NULL,
        image VARCHAR(255) NOT NULL,
        title VARCHAR(100),
        description TEXT,
        order_pos INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
    )");
    echo '<p style="color: green;">✅ Table project_images créée</p>';

    echo '<hr>';
    echo '<p style="color: green; font-size: 18px;"><strong>✅ Migration terminée avec succès !</strong></p>';
    echo '<p><a href="index.php" style="padding: 10px 20px; background: #1a5490; color: white; text-decoration: none; border-radius: 5px;">Retour au site</a></p>';
    echo '</div>';

} catch (PDOException $e) {
    echo '<div style="font-family: Arial; margin: 50px; color: red;">';
    echo '<h1>❌ Erreur de migration</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><a href="migrate.php">Réessayer</a></p>';
    echo '</div>';
}
?>
