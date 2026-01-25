<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$settings = [
    'site_name' => $_POST['site_name'] ?? '',
    'site_description' => $_POST['site_description'] ?? '',
    'contact_email' => $_POST['contact_email'] ?? '',
    'contact_email2' => $_POST['contact_email2'] ?? '',
    'phone' => $_POST['phone'] ?? '',
    'address' => $_POST['address'] ?? '',
    'facebook_url' => $_POST['facebook_url'] ?? '',
    'twitter_url' => $_POST['twitter_url'] ?? '',
    'linkedin_url' => $_POST['linkedin_url'] ?? '',
    'instagram_url' => $_POST['instagram_url'] ?? '',
    'meta_title' => $_POST['meta_title'] ?? '',
    'meta_description' => $_POST['meta_description'] ?? '',
    'site_keywords' => $_POST['site_keywords'] ?? '',
    'presentation_video' => $_POST['presentation_video'] ?? '',
    'footer_text' => $_POST['footer_text'] ?? ''
];

// Upload logo
if (!empty($_FILES['site_logo']['name'])) {
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $ext = strtolower(pathinfo($_FILES['site_logo']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['png','jpg','jpeg','gif'])) {
        $name = 'logo_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $upload_dir . $name)) {
            $settings['site_logo'] = $name;
        }
    }
}

// Upload favicon
if (!empty($_FILES['site_favicon']['name'])) {
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $ext = strtolower(pathinfo($_FILES['site_favicon']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['png','ico','jpg','jpeg'])) {
        $name = 'favicon_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['site_favicon']['tmp_name'], $upload_dir . $name)) {
            $settings['site_favicon'] = $name;
        }
    }
}

// Upload video
if (!empty($_FILES['video_file']['name'])) {
    $upload_dir = '../uploads/videos/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $ext = strtolower(pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['mp4','webm','ogg'])) {
        $name = 'video_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['video_file']['tmp_name'], $upload_dir . $name)) {
            $settings['presentation_video'] = 'uploads/videos/' . $name;
        }
    }
}

try {
    foreach ($settings as $key => $value) {
        // On enregistre même si c'est vide
        $stmt = $pdo->prepare("
            INSERT INTO settings (setting_key, setting_value)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE setting_value = ?
        ");
        $stmt->execute([$key, $value, $value]);
    }

    $_SESSION['success'] = 'Paramètres sauvegardés avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=settings');
exit;
?>
