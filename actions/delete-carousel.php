<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'ID manquant';
    header('Location: ../?page=admin-dashboard&section=carousel');
    exit;
}

try {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM carousel WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = 'Image supprimée !';
} catch (PDOException $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header('Location: ../?page=admin-dashboard&section=carousel');
exit;
?>
