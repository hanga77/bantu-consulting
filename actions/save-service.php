<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';

if (empty($title) || empty($description)) {
    $_SESSION['error'] = 'Remplissez tous les champs';
    header('Location: ../?page=admin-dashboard&section=services');
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO services (title, description) VALUES (?, ?)");
    $stmt->execute([$title, $description]);
    $_SESSION['success'] = 'Service créé avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de la création';
}

header('Location: ../?page=admin-dashboard&section=services');
exit;
?>
