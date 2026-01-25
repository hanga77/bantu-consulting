<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM settings WHERE setting_key = ?");
    $stmt->execute(['site_favicon']);
    $_SESSION['success'] = 'Favicon supprimé !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=settings');
exit;
?>
