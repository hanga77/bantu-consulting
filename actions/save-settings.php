<?php
session_start();
require_once '../config/database.php';
require_once '../includes/image-processing.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

try {
    // Champs texte à mettre à jour
    $text_fields = [
        'site_name', 'site_description', 'site_keywords', 'contact_email', 'contact_email2',
        'phone', 'address', 'meta_title', 'meta_description', 'footer_text',
        'facebook_url', 'twitter_url', 'linkedin_url', 'instagram_url'
    ];
    
    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            $value = trim($_POST[$field]);
            $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
            $stmt->execute([$value, $field]);
        }
    }

    // Validation et mise à jour des coordonnées GPS
    if (isset($_POST['latitude'])) {
        $lat = floatval($_POST['latitude']);
        if ($lat < -90 || $lat > 90) {
            throw new Exception('Latitude invalide (doit être entre -90 et 90)');
        }
        $stmt = $pdo->prepare("UPDATE site_settings SET latitude = ? WHERE id = 1");
        $stmt->execute([$lat]);
    }

    if (isset($_POST['longitude'])) {
        $lon = floatval($_POST['longitude']);
        if ($lon < -180 || $lon > 180) {
            throw new Exception('Longitude invalide (doit être entre -180 et 180)');
        }
        $stmt = $pdo->prepare("UPDATE site_settings SET longitude = ? WHERE id = 1");
        $stmt->execute([$lon]);
    }

    // Traitement des images
    $image_fields = ['site_logo' => 'logo', 'site_favicon' => 'favicon'];
    
    foreach ($image_fields as $field => $prefix) {
        if (!empty($_FILES[$field]['name'])) {
            try {
                $result = processAndSaveImage($_FILES[$field], $prefix, 512, 512, 90);
                $filename = $result['filename'];
                
                // Récupérer l'ancienne image pour la supprimer
                $stmt = $pdo->prepare("SELECT {$field} FROM site_settings WHERE id = 1");
                $stmt->execute();
                $old = $stmt->fetch();
                if (!empty($old[$field])) {
                    deleteImage($old[$field]);
                }
                
                // Sauvegarder la nouvelle
                $stmt = $pdo->prepare("UPDATE site_settings SET {$field} = ? WHERE id = 1");
                $stmt->execute([$filename]);
            } catch (Exception $e) {
                throw new Exception("Erreur upload {$field}: " . $e->getMessage());
            }
        }
    }

    $_SESSION['success'] = 'Paramètres du site mis à jour avec succès!';
} catch (Exception $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=settings');
exit;
?>
