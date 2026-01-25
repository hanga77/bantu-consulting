<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$order_pos = $_POST['order_pos'] ?? 0;
$image = null;

// Traiter l'image
if (!empty($_FILES['image']['name'])) {
    $upload_dir = '../uploads/carousel/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        $unique_name = 'carousel_' . time() . '_' . uniqid() . '.' . $file_ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $unique_name)) {
            $image = $unique_name;
        }
    }
}

if (!$image) {
    $_SESSION['error'] = 'Erreur lors du téléchargement de l\'image';
    header('Location: ../?page=admin-dashboard&section=carousel');
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO carousel (image, title, description, order_pos) VALUES (?, ?, ?, ?)");
    $stmt->execute([$image, $title, $description, $order_pos]);
    $_SESSION['success'] = 'Image ajoutée au carrousel !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=carousel');
exit;
?>
