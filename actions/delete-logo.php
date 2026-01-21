<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

try {
    $pdo->exec("UPDATE site_settings SET site_logo = NULL WHERE id = 1");
    $_SESSION['success'] = 'Logo supprimé';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de la suppression';
}

header('Location: ../?page=admin-dashboard&section=settings');
exit;
?>
