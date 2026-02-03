<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$project_id = intval($_POST['project_id'] ?? 0);
$member_name = trim($_POST['member_name'] ?? '');
$role = trim($_POST['role'] ?? '');

if ($project_id <= 0 || empty($member_name) || empty($role)) {
    $_SESSION['error'] = 'Données invalides';
    header("Location: ../?page=admin-dashboard&section=project-details&id=$project_id");
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO project_members (project_id, member_name, role) VALUES (?, ?, ?)");
    $stmt->execute([$project_id, $member_name, $role]);
    $_SESSION['success'] = 'Membre ajouté !';
} catch (Exception $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header("Location: ../?page=admin-dashboard&section=project-details&id=$project_id");
exit;
?>
