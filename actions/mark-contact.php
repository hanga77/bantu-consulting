<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$id = intval($_GET['id'] ?? 0);
$status = $_GET['status'] ?? 'read';

if ($id > 0 && in_array($status, ['new', 'read'])) {
    $stmt = $pdo->prepare("UPDATE contacts SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    $_SESSION['success'] = 'Message marqué comme ' . ($status === 'read' ? 'lu' : 'non lu');
}

header('Location: ../?page=admin-dashboard&section=contacts');
exit;
?>
