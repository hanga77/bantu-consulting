<?php
session_start();
require_once '../config/database.php';
require_once '../includes/image-processing.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

try {
    $id = intval($_GET['id'] ?? 0);
    
    if ($id <= 0) {
        throw new Exception('ID invalide');
    }
    
    $stmt = $pdo->prepare("SELECT * FROM experts WHERE id = ?");
    $stmt->execute([$id]);
    $expert = $stmt->fetch();
    
    if (!$expert) {
        throw new Exception('Expert non trouvé');
    }
    
    // Supprimer l'image
    if (!empty($expert['image'])) {
        deleteImage($expert['image']);
    }
    
    // Supprimer l'expert
    $stmt = $pdo->prepare("DELETE FROM experts WHERE id = ?");
    $stmt->execute([$id]);
    
    $_SESSION['success'] = 'Expert supprimé avec succès !';
} catch (Exception $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header('Location: ../?page=admin-dashboard&section=experts');
exit;
?>
