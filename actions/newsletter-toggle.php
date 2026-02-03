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

    // Récupérer le statut actuel
    $stmt = $pdo->prepare("SELECT status FROM newsletter_subscribers WHERE id = ?");
    $stmt->execute([$id]);
    $subscriber = $stmt->fetch();

    if (!$subscriber) {
        throw new Exception('Abonné non trouvé');
    }

    $new_status = $subscriber['status'] === 'active' ? 'inactive' : 'active';

    $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $id]);

    $_SESSION['success'] = 'Statut mis à jour avec succès!';
} catch (Exception $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=newsletter');
exit;
?>
