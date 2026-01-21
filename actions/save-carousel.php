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

if (empty($_FILES['image']['name'])) {
    $_SESSION['error'] = 'Veuillez sélectionner une image';
    header('Location: ../?page=admin-dashboard&section=carousel');
    exit;
}

$uploads_dir = '../uploads';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0755, true);
}

$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($_FILES['image']['type'], $allowed_types)) {
    $_SESSION['error'] = 'Format de fichier non autorisé.';
    header('Location: ../?page=admin-dashboard&section=carousel');
    exit;
}

if ($_FILES['image']['size'] > 5242880) {
    $_SESSION['error'] = 'Le fichier est trop volumineux (max 5MB)';
    header('Location: ../?page=admin-dashboard&section=carousel');
    exit;
}

if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = 'Erreur lors de l\'upload';
    header('Location: ../?page=admin-dashboard&section=carousel');
    exit;
}

$image_file = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['image']['name']));
$target_path = $uploads_dir . '/' . $image_file;

if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
    $_SESSION['error'] = 'Erreur lors de l\'upload de l\'image.';
    header('Location: ../?page=admin-dashboard&section=carousel');
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO carousel (title, description, image, order_pos) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $description, $image_file, $order_pos]);
    $_SESSION['success'] = 'Image ajoutée avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de la création';
}

header('Location: ../?page=admin-dashboard&section=carousel');
exit;
?>
