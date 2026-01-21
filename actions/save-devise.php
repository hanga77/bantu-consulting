<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../?page=admin-dashboard&section=devise');
    exit;
}

$motto = trim($_POST['motto'] ?? '');
$description = trim($_POST['description'] ?? '');

// Validation
if (strlen($motto) > 100) {
    $_SESSION['error'] = 'La devise ne doit pas dépasser 100 caractères';
    header('Location: ../?page=admin-dashboard&section=devise');
    exit;
}

if (strlen($description) > 1000) {
    $_SESSION['error'] = 'La description ne doit pas dépasser 1000 caractères';
    header('Location: ../?page=admin-dashboard&section=devise');
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE about SET motto = ?, description = ? WHERE id = 1");
    $stmt->execute([$motto, $description]);
    
    $_SESSION['success'] = 'Devise mise à jour avec succès';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de la mise à jour : ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=devise');
exit;
?>
