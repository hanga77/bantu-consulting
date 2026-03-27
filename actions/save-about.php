<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Token de sécurité invalide.';
    header('Location: ../?page=admin-dashboard&section=about');
    exit;
}

$motto = $_POST['motto'] ?? '';
$description = $_POST['description'] ?? '';
$mission = $_POST['mission'] ?? '';
$vision = $_POST['vision'] ?? '';

try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM about");
    $result = $stmt->fetch();
    
    if ($result['count'] > 0) {
        $stmt = $pdo->prepare("UPDATE about SET motto = ?, description = ?, mission = ?, vision = ? WHERE id=1");
        $stmt->execute([$motto, $description, $mission, $vision]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO about (motto, description, mission, vision) VALUES (?, ?, ?, ?)");
        $stmt->execute([$motto, $description, $mission, $vision]);
    }
    
    $_SESSION['success'] = 'Informations mises à jour !';
} catch (PDOException $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header('Location: ../?page=admin-dashboard&section=about');
exit;
?>
