<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bantu_consulting');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage()));
}

// Fonction pour récupérer les paramètres du site
function getSiteSettings() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM site_settings WHERE id = 1");
        return $stmt->fetch() ?: [];
    } catch (PDOException $e) {
        return [];
    }
}

// Inclure les langues
require_once __DIR__ . '/languages.php';
?>
