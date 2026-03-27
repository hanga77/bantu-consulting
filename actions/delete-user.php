<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Requête invalide.';
    header('Location: ../?page=admin-dashboard&section=users');
    exit;
}

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    $_SESSION['error'] = 'ID utilisateur manquant.';
    header('Location: ../?page=admin-dashboard&section=users');
    exit;
}

// Empêcher la suppression de l'admin actuel
if ($id === (int)$_SESSION['user_id']) {
    $_SESSION['error'] = 'Vous ne pouvez pas supprimer votre propre compte';
    header('Location: ../?page=admin-dashboard&section=users');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = 'Utilisateur supprimé avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header('Location: ../?page=admin-dashboard&section=users');
exit;
?>
