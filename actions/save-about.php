<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$motto = $_POST['motto'] ?? '';
$description = $_POST['description'] ?? '';

try {
    $stmt = $pdo->prepare("UPDATE about SET motto=?, description=? WHERE id=1");
    $stmt->execute([$motto, $description]);
    $_SESSION['success'] = 'Informations mises à jour avec succès';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de la mise à jour';
}

header('Location: ../?page=admin-dashboard&section=about');
exit;
?>
