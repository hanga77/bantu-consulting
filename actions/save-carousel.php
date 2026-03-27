<?php
session_start();
require_once '../config/database.php';
require_once '../includes/image-processing.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Token de sécurité invalide.';
    header('Location: ../?page=admin-dashboard&section=carousel');
    exit;
}

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$order_pos = intval($_POST['order_pos'] ?? 0);

try {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        throw new Exception("Aucune image fournie.");
    }
    
    // Images optimisées pour carousel: 1920x1080 (16:9)
    $imageData = processAndSaveImage($_FILES['image'], 'carousel', 1920, 1080, 85);
    
    $imageName = $imageData['filename'];
    $imageWidth = $imageData['width'];
    $imageHeight = $imageData['height'];
    
    // Vérifier que les colonnes existent
    $stmt = $pdo->query("SHOW COLUMNS FROM carousel LIKE 'image_width'");
    if ($stmt->rowCount() === 0) {
        throw new Exception("Les colonnes de traitement d'image manquent. Veuillez exécuter la migration: migrate.php");
    }
    
    $stmt = $pdo->prepare("INSERT INTO carousel (image, image_width, image_height, image_processed_at, title, description, order_pos) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
    $stmt->execute([$imageName, $imageWidth, $imageHeight, $title, $description, $order_pos]);
    
    $_SESSION['success'] = 'Image ajoutée au carrousel avec succès!';
    
} catch (Exception $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header('Location: ../?page=admin-dashboard&section=carousel');
exit;
?>