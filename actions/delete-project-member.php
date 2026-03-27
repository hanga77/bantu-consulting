<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Requête invalide.';
    header('Location: ../?page=admin-dashboard&section=projects');
    exit;
}

$id = intval($_POST['id'] ?? 0);
$project_id = intval($_POST['project_id'] ?? 0);

try {
    $stmt = $pdo->prepare("DELETE FROM project_members WHERE id = ? AND project_id = ?");
    $stmt->execute([$id, $project_id]);
    $_SESSION['success'] = 'Membre supprimé !';
} catch (Exception $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header("Location: ../?page=admin-dashboard&section=project-details&id=$project_id");
exit;
?>
