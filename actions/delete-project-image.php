<?php
session_start();
require_once '../config/database.php';
require_once '../includes/image-processing.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

try {
    $image_id = intval($_GET['id'] ?? 0);
    
    if ($image_id === 0) {
        throw new Exception('ID image invalide');
    }
    
    $stmt = $pdo->prepare("SELECT * FROM project_images WHERE id = ?");
    $stmt->execute([$image_id]);
    $image = $stmt->fetch();
    
    if (!$image) {
        throw new Exception('Image non trouvée');
    }
    
    // Supprimer le fichier
    deleteImage($image['image']);
    
    // Supprimer de la BD
    $stmt = $pdo->prepare("DELETE FROM project_images WHERE id = ?");
    $stmt->execute([$image_id]);
    
    $_SESSION['success'] = 'Image supprimée avec succès!';
} catch (Exception $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header('Location: ../?page=admin-dashboard&section=projects');
exit;
?>
