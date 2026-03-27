<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Token de sécurité invalide.';
    header('Location: ../?page=admin-dashboard&section=settings');
    exit;
}

$section = $_POST['section'] ?? 'general';

try {
    if ($section === 'video') {
        // Gérer le téléchargement vidéo
        if (!isset($_FILES['presentation_video']) || $_FILES['presentation_video']['error'] === UPLOAD_ERR_NO_FILE) {
            $_SESSION['error'] = 'Veuillez sélectionner une vidéo';
            header('Location: ../?page=admin-dashboard&section=settings');
            exit;
        }
        
        $file = $_FILES['presentation_video'];
        
        $uploadDir = __DIR__ . '/../uploads/videos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $allowed_types = ['video/mp4', 'video/webm', 'video/quicktime'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $real_mime = $finfo->file($file['tmp_name']);
        if (!in_array($real_mime, $allowed_types)) {
            throw new Exception("Format vidéo non autorisé. Utilisez MP4 ou WebM.");
        }
        
        if ($file['size'] > 200 * 1024 * 1024) {
            throw new Exception("Fichier vidéo trop volumineux (max 200MB).");
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur lors du téléchargement.");
        }
        
        $filename = 'video_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $filepath = $uploadDir . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception("Erreur lors du déplacement du fichier.");
        }
        
        $video_path = 'uploads/videos/' . $filename;
        
        $stmt = $pdo->prepare("UPDATE site_settings SET presentation_video = ? WHERE id = 1");
        $stmt->execute([$video_path]);
        
        $_SESSION['success'] = 'Vidéo mise à jour avec succès !';
        
    } elseif ($section === 'location') {
        // Gérer la localisation
        $latitude = trim($_POST['latitude'] ?? '4.0511');
        $longitude = trim($_POST['longitude'] ?? '9.7679');
        
        $lat = floatval($latitude);
        $lon = floatval($longitude);
        
        if ($lat < -90 || $lat > 90) {
            $_SESSION['error'] = 'Latitude invalide (doit être entre -90 et 90)';
            header('Location: ../?page=admin-dashboard&section=settings');
            exit;
        }
        
        if ($lon < -180 || $lon > 180) {
            $_SESSION['error'] = 'Longitude invalide (doit être entre -180 et 180)';
            header('Location: ../?page=admin-dashboard&section=settings');
            exit;
        }
        
        $stmt = $pdo->prepare("UPDATE site_settings SET latitude = ?, longitude = ? WHERE id = 1");
        $stmt->execute([$latitude, $longitude]);
        
        $_SESSION['success'] = 'Localisation mise à jour avec succès !';
        
    } else {
        // Champs texte à mettre à jour
        $text_fields = [
            'site_name', 'site_description', 'site_keywords', 'contact_email', 'contact_email2',
            'phone', 'address', 'meta_title', 'meta_description', 'footer_text',
            'facebook_url', 'twitter_url', 'linkedin_url', 'instagram_url'
        ];
        
        foreach ($text_fields as $field) {
            if (isset($_POST[$field])) {
                $value = trim($_POST[$field]);
                $stmt = $pdo->prepare("UPDATE site_settings SET " . $field . " = ? WHERE id = 1");
                $stmt->execute([$value]);
            }
        }
        
        $_SESSION['success'] = 'Paramètres mis à jour !';
    }

} catch (Exception $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=settings');
exit;
?>
