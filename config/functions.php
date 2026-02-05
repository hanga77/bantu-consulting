<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Accès refusé';
    header('Location: ../?page=admin-login');
    exit;
}

// Debug
error_log("=== UPLOAD VIDEO DEBUG ===");
error_log("FILES: " . json_encode($_FILES));
error_log("POST: " . json_encode($_POST));

try {
    // Vérifier si le fichier est présent
    if (!isset($_FILES['presentation_video']) || $_FILES['presentation_video']['error'] === UPLOAD_ERR_NO_FILE) {
        throw new Exception("Veuillez sélectionner une vidéo");
    }
    
    $file = $_FILES['presentation_video'];
    
    // Vérifier les erreurs d'upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'Fichier trop volumineux (limite serveur php.ini)',
            UPLOAD_ERR_FORM_SIZE => 'Fichier trop volumineux (limite formulaire)',
            UPLOAD_ERR_PARTIAL => 'Téléchargement partiel - Réessayez',
            UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant',
            UPLOAD_ERR_CANT_WRITE => 'Erreur d\'écriture sur le disque'
        ];
        throw new Exception($errors[$file['error']] ?? 'Erreur lors du téléchargement');
    }
    
    // Vérifier que le fichier a un nom
    if (empty($file['name'])) {
        throw new Exception("Nom de fichier invalide");
    }
    
    // Vérifier que la taille est supérieure à 0
    if ($file['size'] <= 0) {
        throw new Exception("Fichier vide ou taille incorrecte");
    }
    
    // Vérifier le type MIME
    $allowed_types = ['video/mp4', 'video/webm', 'video/quicktime'];
    $file_type = strtolower($file['type']);
    
    if (!in_array($file_type, $allowed_types)) {
        throw new Exception("Format vidéo non autorisé. Utilisez MP4 ou WebM. Type détecté: " . $file_type);
    }
    
    // Vérifier la taille (max 200MB)
    $max_size = 200 * 1024 * 1024;
    if ($file['size'] > $max_size) {
        $size_mb = round($file['size'] / 1024 / 1024, 2);
        throw new Exception("Fichier trop volumineux ({$size_mb}MB). Maximum: 200MB");
    }
    
    // Créer le dossier s'il n'existe pas
    $uploadDir = __DIR__ . '/../uploads/videos/';
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0755, true);
    }
    
    // Générer un nom unique
    $filename = 'video_' . time() . '_' . rand(1000, 9999) . '.mp4';
    $filepath = $uploadDir . $filename;
    
    // Déplacer le fichier
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception("Erreur lors du téléchargement du fichier");
    }
    
    $video_path = 'uploads/videos/' . $filename;
    
    // Mettre à jour la base de données
    $stmt = $pdo->prepare("UPDATE site_settings SET presentation_video = ? WHERE id = 1");
    $stmt->execute([$video_path]);
    
    $_SESSION['success'] = 'Vidéo mise à jour avec succès !';
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
    error_log("Upload error: " . $e->getMessage());
}

header('Location: ../?page=admin-dashboard&section=settings');
exit;
?>