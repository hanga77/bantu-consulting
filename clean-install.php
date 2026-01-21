<?php
/**
 * Script de réinstallation complète avec données
 * Supprime et recrée tout depuis zéro
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bantu_consulting');

// Créer les dossiers
$base_dir = __DIR__;
$folders = ['uploads', 'pages', 'templates', 'admin', 'actions', 'config', 'assets'];
foreach ($folders as $folder) {
    $path = $base_dir . '/' . $folder;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo '<div style="font-family: Arial; margin: 50px;">';
    echo '<h1>🔄 Réinstallation complète...</h1>';

    // Supprimer la base existante
    $pdo->exec("DROP DATABASE IF EXISTS " . DB_NAME);
    echo '<p style="color: orange;">✓ Base de données supprimée</p>';

    // Recréer la base
    $pdo->exec("CREATE DATABASE " . DB_NAME);
    $pdo->exec("USE " . DB_NAME);
    echo '<p style="color: green;">✓ Base de données recréée</p>';

    // Inclure et exécuter install.php
    ob_start();
    include 'install.php';
    ob_end_flush();

} catch (PDOException $e) {
    echo '<div style="font-family: Arial; margin: 50px; color: red;">';
    echo '<h1>❌ Erreur</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '</div>';
}
?>
