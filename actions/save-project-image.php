<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$project_id = $_POST['project_id'] ?? 0;
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';

if (empty($_FILES['image']['name']) || empty($project_id)) {
    $_SESSION['error'] = 'Sélectionnez une image et un projet';
    header('Location: ../?page=admin-dashboard&section=projects&action=gallery&id=' . $project_id);
    exit;
}

$uploads_dir = '../uploads';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0755, true);
}

$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($_FILES['image']['type'], $allowed_types)) {
    $_SESSION['error'] = 'Format de fichier non autorisé.';
    header('Location: ../?page=admin-dashboard&section=projects&action=gallery&id=' . $project_id);
    exit;
}

if ($_FILES['image']['size'] > 2097152) {
    $_SESSION['error'] = 'Le fichier est trop volumineux (max 2MB)';
    header('Location: ../?page=admin-dashboard&section=projects&action=gallery&id=' . $project_id);
    exit;
}

if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = 'Erreur lors de l\'upload';
    header('Location: ../?page=admin-dashboard&section=projects&action=gallery&id=' . $project_id);
    exit;
}

$image_file = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['image']['name']));
$target_path = $uploads_dir . '/' . $image_file;

if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
    $_SESSION['error'] = 'Erreur lors de l\'upload de l\'image.';
    header('Location: ../?page=admin-dashboard&section=projects&action=gallery&id=' . $project_id);
    exit;
}

try {
    // Obtenir le prochain order_pos
    $stmt = $pdo->prepare("SELECT MAX(order_pos) as max_pos FROM project_images WHERE project_id = ?");
    $stmt->execute([$project_id]);
    $result = $stmt->fetch();
    $next_pos = ($result['max_pos'] ?? -1) + 1;

    $stmt = $pdo->prepare("INSERT INTO project_images (project_id, image, title, description, order_pos) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$project_id, $image_file, $title, $description, $next_pos]);
    $_SESSION['success'] = 'Image ajoutée à la galerie !';
} catch (PDOException $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header('Location: ../?page=admin-dashboard&section=projects&action=gallery&id=' . $project_id);
exit;
?>
