<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Token de sécurité invalide.';
    header('Location: ../?page=admin-dashboard&section=departments');
    exit;
}

$name = $_POST['name'] ?? '';

if (empty($name)) {
    $_SESSION['error'] = 'Le nom du département est requis';
    header('Location: ../?page=admin-dashboard&section=departments');
    exit;
}

try {
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Mise à jour
        $id = intval($_POST['id']);
        $description = $_POST['description'] ?? '';
        $department_type = $_POST['department_type'] ?? '';
        
        $stmt = $pdo->prepare("UPDATE departments SET name = ?, description = ?, department_type = ? WHERE id = ?");
        $stmt->execute([$name, $description, $department_type, $id]);
        $_SESSION['success'] = 'Département mis à jour avec succès !';
    } else {
        // Création
        $description = $_POST['description'] ?? '';
        $department_type = $_POST['department_type'] ?? '';
        
        $stmt = $pdo->prepare("INSERT INTO departments (name, description, department_type) VALUES (?, ?, ?)");
        $stmt->execute([$name, $description, $department_type]);
        $_SESSION['success'] = 'Département créé avec succès !';
    }
} catch (PDOException $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header('Location: ../?page=admin-dashboard&section=departments');
exit;
?>
