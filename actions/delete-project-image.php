<?php
session_start();
require_once '../config/database.php';
require_once '../includes/image-processing.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Requête invalide.';
    header('Location: ../?page=admin-dashboard&section=projects');
    exit;
}

try {
    $image_id = intval($_POST['id'] ?? 0);
    
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
