<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Accès refusé';
    header('Location: ../?page=admin-login');
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Token de sécurité invalide.';
    header('Location: ../?page=admin-dashboard&section=settings');
    exit;
}

try {
    // Vérifier si le fichier est présent
    if (!isset($_FILES['presentation_video']) || $_FILES['presentation_video']['error'] === UPLOAD_ERR_NO_FILE) {
        throw new Exception("Veuillez sélectionner une vidéo");
    }
    
    $file = $_FILES['presentation_video'];
    
    // Vérifier les erreurs d'upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'Fichier trop volumineux (limite serveur)',
            UPLOAD_ERR_FORM_SIZE => 'Fichier trop volumineux (limite formulaire)',
            UPLOAD_ERR_PARTIAL => 'Téléchargement partiel',
            UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant',
            UPLOAD_ERR_CANT_WRITE => 'Erreur d\'écriture disque'
        ];
        throw new Exception($errors[$file['error']] ?? 'Erreur upload');
    }
    
    // Validations
    if (empty($file['name'])) throw new Exception("Nom invalide");
    if ($file['size'] <= 0) throw new Exception("Fichier vide");
    
    $allowed_types = ['video/mp4', 'video/webm', 'video/quicktime'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $real_mime = $finfo->file($file['tmp_name']);
    if (!in_array($real_mime, $allowed_types)) {
        throw new Exception("Format non autorisé: " . $real_mime);
    }
    
    $max_size = 200 * 1024 * 1024;
    if ($file['size'] > $max_size) {
        throw new Exception("Fichier > 200MB");
    }
    
    // Créer le dossier
    $uploadDir = __DIR__ . '/../uploads/videos/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new Exception("Impossible créer dossier");
        }
    }
    
    // Générer nom unique
    $filename = 'video_' . time() . '_' . rand(1000, 9999) . '.mp4';
    $filepath = $uploadDir . $filename;
    
    // Déplacer fichier
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception("Erreur déplacement fichier");
    }
    
    $video_path = 'uploads/videos/' . $filename;
    
    // Mettre à jour BD
    $stmt = $pdo->prepare("UPDATE site_settings SET presentation_video = ? WHERE id = 1");
    $result = $stmt->execute([$video_path]);
    
    if (!$result) {
        throw new Exception("Erreur mise à jour BD");
    }
    
    $_SESSION['success'] = 'Vidéo enregistrée !';
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
    error_log("Upload video: " . $e->getMessage());
}

header('Location: ../?page=admin-dashboard&section=settings');
exit;
?>
