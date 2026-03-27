<?php
/**
 * Script de réparation - Corrige les tables existantes
 * UTILISATION: CLI uniquement — php repair.php
 */

// Bloquer l'accès web
if (isset($_SERVER['HTTP_HOST'])) {
    http_response_code(403);
    die('Accès refusé. Ce script doit être exécuté en ligne de commande uniquement.');
}

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
    echo '<h1>🔧 Réparation des tables...</h1>';

    // Vérifier et corriger la table carousel
    try {
        $result = $pdo->query("SHOW COLUMNS FROM carousel LIKE 'order_pos'")->fetch();
        if (!$result) {
            $pdo->exec("ALTER TABLE carousel ADD COLUMN order_pos INT DEFAULT 0 AFTER description");
            echo '<p style="color: green;">✓ Colonne order_pos ajoutée à la table carousel</p>';
        } else {
            echo '<p style="color: blue;">✓ La colonne order_pos existe déjà</p>';
        }
    } catch (PDOException $e) {
        echo '<p style="color: red;">❌ Erreur : ' . $e->getMessage() . '</p>';
    }

    // Recréer la table carousel proprement si besoin
    try {
        $pdo->exec("DROP TABLE IF EXISTS carousel");
        $pdo->exec("CREATE TABLE carousel (
            id INT PRIMARY KEY AUTO_INCREMENT,
            image VARCHAR(255),
            title VARCHAR(100),
            description TEXT,
            order_pos INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        echo '<p style="color: green;">✓ Table carousel recréée</p>';
    } catch (PDOException $e) {
        echo '<p style="color: orange;">⚠️ Table carousel : ' . $e->getMessage() . '</p>';
    }

    echo '<hr>';
    echo '<p style="color: green; font-size: 16px;"><strong>✓ Réparation terminée !</strong></p>';
    echo '<p><a href="index.php" style="padding: 10px 20px; background: #1a5490; color: white; text-decoration: none; border-radius: 5px;">Retour au site</a></p>';
    echo '</div>';

} catch (PDOException $e) {
    echo '<div style="font-family: Arial; margin: 50px; color: red;">';
    echo '<h1>❌ Erreur de connexion</h1>';
    echo '<p>Vérifiez vos paramètres de base de données.</p>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '</div>';
}
?>
