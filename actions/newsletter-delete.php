<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

try {
    $id = intval($_GET['id'] ?? 0);

    if ($id <= 0) {
        throw new Exception('ID invalide');
    }

    $stmt = $pdo->prepare("DELETE FROM newsletter_subscribers WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['success'] = 'Abonné supprimé avec succès!';
} catch (Exception $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=newsletter');
exit;
?>
