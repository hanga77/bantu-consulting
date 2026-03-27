<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Token de sécurité invalide.';
    header('Location: ../?page=admin-dashboard&section=settings');
    exit;
}

try {
    // Récupérer le chemin actuel pour supprimer le fichier
    $stmt = $pdo->query("SELECT site_favicon FROM site_settings WHERE id = 1 LIMIT 1");
    $row = $stmt->fetch();
    if (!empty($row['site_favicon'])) {
        $path = __DIR__ . '/../uploads/' . basename($row['site_favicon']);
        if (file_exists($path)) {
            unlink($path);
        }
    }

    $stmt = $pdo->prepare("UPDATE site_settings SET site_favicon = NULL WHERE id = 1");
    $stmt->execute();
    $_SESSION['success'] = 'Favicon supprimé !';
} catch (PDOException $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header('Location: ../?page=admin-dashboard&section=settings');
exit;
