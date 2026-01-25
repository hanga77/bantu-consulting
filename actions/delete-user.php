<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'ID utilisateur manquant';
    header('Location: ../?page=admin-dashboard&section=users');
    exit;
}

$id = intval($_GET['id']);

// Empêcher la suppression de l'admin actuel
if ($id === $_SESSION['user_id']) {
    $_SESSION['error'] = 'Vous ne pouvez pas supprimer votre propre compte';
    header('Location: ../?page=admin-dashboard&section=users');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = 'Utilisateur supprimé avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=users');
exit;
?>
