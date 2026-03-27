<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['error'] = 'ID invalide';
    header('Location: ../?page=admin-dashboard&section=departments');
    exit;
}

try {
    // Vérifier que le département existe
    $stmt = $pdo->prepare("SELECT id FROM departments WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) {
        $_SESSION['error'] = 'Département non trouvé';
        header('Location: ../?page=admin-dashboard&section=departments');
        exit;
    }
    
    // Supprimer le département
    $stmt = $pdo->prepare("DELETE FROM departments WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = 'Département supprimé avec succès ! (Les membres conservent leurs données)';
} catch (PDOException $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header('Location: ../?page=admin-dashboard&section=departments');
exit;
?>
