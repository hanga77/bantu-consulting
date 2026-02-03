<?php
/**
 * Bibliothèque de traitement et optimisation d'images
 * Redimensionne, compresse et optimise les images pour le web
 */

function processAndSaveImage($file, $prefix = 'img', $maxWidth = 1200, $maxHeight = 1200, $quality = 85) {
    $uploadDir = __DIR__ . '/../uploads/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($file['type'], $allowed)) {
        throw new Exception("Format non autorisé. Utilisez JPG, PNG, WebP ou GIF.");
    }
    
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception("Fichier trop volumineux (max 5MB).");
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'Fichier trop volumineux (limite serveur)',
            UPLOAD_ERR_FORM_SIZE => 'Fichier trop volumineux (limite formulaire)',
            UPLOAD_ERR_PARTIAL => 'Téléchargement partiel',
            UPLOAD_ERR_NO_FILE => 'Aucun fichier',
            UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant',
            UPLOAD_ERR_CANT_WRITE => 'Erreur d\'écriture disque'
        ];
        throw new Exception($errors[$file['error']] ?? 'Erreur lors du téléchargement');
    }
    
    $imageInfo = getimagesize($file['tmp_name']);
    if (!$imageInfo) {
        throw new Exception("Fichier image invalide.");
    }
    
    list($width, $height, $type) = $imageInfo;
    
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($file['tmp_name']);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($file['tmp_name']);
            break;
        case IMAGETYPE_WEBP:
            $source = imagecreatefromwebp($file['tmp_name']);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($file['tmp_name']);
            break;
        default:
            throw new Exception("Type d'image non supporté.");
    }
    
    if (!$source) {
        throw new Exception("Impossible de charger l'image.");
    }
    
    // Calcul du ratio de redimensionnement
    $ratio = min($maxWidth / $width, $maxHeight / $height);
    
    if ($ratio < 1) {
        $newWidth = intval($width * $ratio);
        $newHeight = intval($height * $ratio);
    } else {
        $newWidth = $width;
        $newHeight = $height;
    }
    
    // Créer une image de destination avec dimensions carrées si maxWidth == maxHeight
    if ($maxWidth === $maxHeight) {
        // Mode carré: redimensionner et centrer
        $destination = imagecreatetruecolor($maxWidth, $maxHeight);
        
        // Remplir avec blanc
        $white = imagecolorallocate($destination, 255, 255, 255);
        imagefilledrectangle($destination, 0, 0, $maxWidth, $maxHeight, $white);
        
        // Calculer la position de centrage
        $offsetX = intval(($maxWidth - $newWidth) / 2);
        $offsetY = intval(($maxHeight - $newHeight) / 2);
        
        imagecopyresampled($destination, $source, $offsetX, $offsetY, 0, 0, $newWidth, $newHeight, $width, $height);
        
        $finalWidth = $maxWidth;
        $finalHeight = $maxHeight;
    } else {
        // Mode rectangle: redimensionner avec aspect ratio
        $destination = imagecreatetruecolor($newWidth, $newHeight);
        
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
            imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        $finalWidth = $newWidth;
        $finalHeight = $newHeight;
    }
    
    $filename = $prefix . '_' . uniqid() . '_' . time() . '.jpg';
    $filepath = $uploadDir . $filename;
    
    imagejpeg($destination, $filepath, $quality);
    
    imagedestroy($source);
    imagedestroy($destination);
    
    return [
        'filename' => $filename,
        'filepath' => $filepath,
        'width' => $finalWidth,
        'height' => $finalHeight,
        'size' => filesize($filepath)
    ];
}

function deleteImage($filename) {
    if (empty($filename)) return;
    
    $filepath = __DIR__ . '/../uploads/' . $filename;
    if (file_exists($filepath)) {
        unlink($filepath);
    }
}
?>