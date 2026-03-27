<?php
session_start();
require_once '../config/database.php';
require_once '../includes/image-processing.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Token de sécurité invalide.';
    header('Location: ../?page=admin-dashboard&section=experts');
    exit;
}

$name = trim($_POST['name'] ?? '');
$specialty = trim($_POST['specialty'] ?? '');
$description = trim($_POST['description'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if (empty($name) || empty($specialty)) {
    $_SESSION['error'] = 'Remplissez tous les champs obligatoires';
    header('Location: ../?page=admin-dashboard&section=experts');
    exit;
}

$image_file = '';

try {
    // Traiter l'image s'il y en a une
    if (!empty($_FILES['image']['name'])) {
        try {
            $result = processAndSaveImage($_FILES['image'], 'expert', 300, 350, 90);
            $image_file = $result['filename'];
        } catch (Exception $e) {
            $_SESSION['error'] = safeErrorMessage($e);
            header('Location: ../?page=admin-dashboard&section=experts');
            exit;
        }
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Mise à jour
        $id = intval($_POST['id']);
        
        $stmt = $pdo->prepare("SELECT * FROM experts WHERE id = ?");
        $stmt->execute([$id]);
        $expert = $stmt->fetch();
        
        if (!$expert) {
            $_SESSION['error'] = 'Expert non trouvé';
            header('Location: ../?page=admin-dashboard&section=experts');
            exit;
        }
        
        // Si pas de nouvelle image, garder l'ancienne
        if (empty($image_file)) {
            $image_file = $expert['image'];
        } else {
            // Supprimer l'ancienne image
            if (!empty($expert['image'])) {
                deleteImage($expert['image']);
            }
        }
        
        $stmt = $pdo->prepare("UPDATE experts SET name = ?, specialty = ?, description = ?, email = ?, phone = ?, image = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$name, $specialty, $description, $email, $phone, $image_file, $id]);
        $_SESSION['success'] = 'Expert mis à jour avec succès !';
    } else {
        // Création
        $stmt = $pdo->prepare("INSERT INTO experts (name, specialty, description, email, phone, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $specialty, $description, $email, $phone, $image_file]);
        $_SESSION['success'] = 'Expert ajouté avec succès !';
    }
} catch (PDOException $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header('Location: ../?page=admin-dashboard&section=experts');
exit;
?>
