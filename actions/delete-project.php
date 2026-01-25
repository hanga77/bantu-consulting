<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'ID projet manquant';
    header('Location: ../?page=admin-dashboard&section=projects');
    exit;
}

try {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = 'Projet supprimé avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=projects');
exit;
?>
