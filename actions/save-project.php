<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$title = $_POST['title'] ?? '';
$short_description = $_POST['short_description'] ?? '';
$description = $_POST['description'] ?? '';
$status = $_POST['status'] ?? '';
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;

if (empty($title) || empty($description)) {
    $_SESSION['error'] = 'Remplissez tous les champs obligatoires';
    header('Location: ../?page=admin-dashboard&section=projects');
    exit;
}

$image_file = '';
if (!empty($_FILES['image']['name'])) {
    $uploads_dir = '../uploads';
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0755, true);
    }
    
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        $_SESSION['error'] = 'Format de fichier non autorisé.';
        header('Location: ../?page=admin-dashboard&section=projects');
        exit;
    }
    
    if ($_FILES['image']['size'] > 2097152) {
        $_SESSION['error'] = 'Le fichier est trop volumineux (max 2MB)';
        header('Location: ../?page=admin-dashboard&section=projects');
        exit;
    }
    
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'Erreur lors de l\'upload';
        header('Location: ../?page=admin-dashboard&section=projects');
        exit;
    }
    
    $image_file = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['image']['name']));
    $target_path = $uploads_dir . '/' . $image_file;
    
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        $_SESSION['error'] = 'Erreur lors de l\'upload de l\'image.';
        header('Location: ../?page=admin-dashboard&section=projects');
        exit;
    }
}

try {
    $stmt = $pdo->prepare("INSERT INTO projects (title, short_description, description, image, status, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $short_description, $description, $image_file, $status, $start_date, $end_date]);
    $_SESSION['success'] = 'Projet créé avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de la création : ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=projects');
exit;
?>
