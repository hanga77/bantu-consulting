<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'ID service manquant';
    header('Location: ../?page=admin-dashboard&section=services');
    exit;
}

try {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = 'Service supprimé avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header('Location: ../?page=admin-dashboard&section=services');
exit;
?>
