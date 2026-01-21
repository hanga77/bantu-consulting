<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$id = $_GET['id'] ?? 0;
$project_id = $_GET['project_id'] ?? 0;

try {
    $pdo->prepare("DELETE FROM project_images WHERE id=?")->execute([$id]);
    $_SESSION['success'] = 'Image supprimée avec succès';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de la suppression';
}

header('Location: ../?page=admin-dashboard&section=projects&action=gallery&id=' . $project_id);
exit;
?>
