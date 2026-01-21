<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$site_name = $_POST['site_name'] ?? '';
$site_description = $_POST['site_description'] ?? '';
$site_keywords = $_POST['site_keywords'] ?? '';
$contact_email = $_POST['contact_email'] ?? '';
$contact_email2 = $_POST['contact_email2'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$meta_title = $_POST['meta_title'] ?? '';
$meta_description = $_POST['meta_description'] ?? '';
$footer_text = $_POST['footer_text'] ?? '';
$presentation_video = $_POST['presentation_video'] ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ';

$site_logo = '';
$site_favicon = '';

// Traitement du logo
if (!empty($_FILES['site_logo']['name'])) {
    $uploads_dir = '../uploads';
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0755, true);
    }
    
    // Vérifications
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($_FILES['site_logo']['type'], $allowed_types)) {
        $_SESSION['error'] = 'Format logo non autorisé';
        header('Location: ../?page=admin-dashboard&section=settings');
        exit;
    }
    
    if ($_FILES['site_logo']['size'] > 2097152) {
        $_SESSION['error'] = 'Logo trop volumineux (max 2MB)';
        header('Location: ../?page=admin-dashboard&section=settings');
        exit;
    }
    
    if ($_FILES['site_logo']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'Erreur lors de l\'upload du logo';
        header('Location: ../?page=admin-dashboard&section=settings');
        exit;
    }
    
    $site_logo = time() . '_logo_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['site_logo']['name']));
    if (!move_uploaded_file($_FILES['site_logo']['tmp_name'], $uploads_dir . '/' . $site_logo)) {
        $_SESSION['error'] = 'Erreur lors de l\'upload du logo. Vérifiez les permissions.';
        header('Location: ../?page=admin-dashboard&section=settings');
        exit;
    }
}

// Traitement du favicon
if (!empty($_FILES['site_favicon']['name'])) {
    $uploads_dir = '../uploads';
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0755, true);
    }
    
    // Vérifications
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/x-icon', 'image/webp'];
    if (!in_array($_FILES['site_favicon']['type'], $allowed_types)) {
        $_SESSION['error'] = 'Format favicon non autorisé';
        header('Location: ../?page=admin-dashboard&section=settings');
        exit;
    }
    
    if ($_FILES['site_favicon']['size'] > 1048576) {
        $_SESSION['error'] = 'Favicon trop volumineux (max 1MB)';
        header('Location: ../?page=admin-dashboard&section=settings');
        exit;
    }
    
    if ($_FILES['site_favicon']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'Erreur lors de l\'upload du favicon';
        header('Location: ../?page=admin-dashboard&section=settings');
        exit;
    }
    
    $site_favicon = time() . '_favicon_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['site_favicon']['name']));
    if (!move_uploaded_file($_FILES['site_favicon']['tmp_name'], $uploads_dir . '/' . $site_favicon)) {
        $_SESSION['error'] = 'Erreur lors de l\'upload du favicon. Vérifiez les permissions.';
        header('Location: ../?page=admin-dashboard&section=settings');
        exit;
    }
}

// Traitement vidéo locale
$video_file = '';
if (!empty($_FILES['video_file']['name'])) {
    $uploads_dir = '../uploads';
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0755, true);
    }
    
    $allowed_types = ['video/mp4', 'video/webm', 'video/ogg'];
    if (!in_array($_FILES['video_file']['type'], $allowed_types)) {
        $_SESSION['error'] = 'Format vidéo non autorisé. Utilisez MP4, WebM ou OGG.';
        header('Location: ../?page=admin-dashboard&section=settings');
        exit;
    }
    
    if ($_FILES['video_file']['size'] > 52428800) {
        $_SESSION['error'] = 'La vidéo est trop volumineuse (max 50MB)';
        header('Location: ../?page=admin-dashboard&section=settings');
        exit;
    }
    
    if ($_FILES['video_file']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'Erreur lors de l\'upload de la vidéo';
        header('Location: ../?page=admin-dashboard&section=settings');
        exit;
    }
    
    $video_file = time() . '_video_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['video_file']['name']));
    if (!move_uploaded_file($_FILES['video_file']['tmp_name'], $uploads_dir . '/' . $video_file)) {
        $_SESSION['error'] = 'Erreur lors de l\'upload de la vidéo';
        header('Location: ../?page=admin-dashboard&section=settings');
        exit;
    }
    
    $presentation_video = 'uploads/' . $video_file;
}

try {
    $update_query = "UPDATE site_settings SET
        site_name = ?,
        site_description = ?,
        site_keywords = ?,
        contact_email = ?,
        contact_email2 = ?,
        phone = ?,
        address = ?,
        presentation_video = ?,
        meta_title = ?,
        meta_description = ?,
        footer_text = ?";
    
    $params = [$site_name, $site_description, $site_keywords, $contact_email, $contact_email2, $phone, $address, $presentation_video, $meta_title, $meta_description, $footer_text];
    
    if ($site_logo) {
        $update_query .= ", site_logo = ?";
        $params[] = $site_logo;
    }
    
    if ($site_favicon) {
        $update_query .= ", site_favicon = ?";
        $params[] = $site_favicon;
    }
    
    $update_query .= " WHERE id = 1";
    
    $stmt = $pdo->prepare($update_query);
    $stmt->execute($params);
    
    $_SESSION['success'] = 'Paramètres du site mis à jour avec succès';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de la mise à jour : ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=settings');
exit;
?>