<?php
session_start();
require_once '../config/database.php';
require_once '../includes/image-processing.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$name = $_POST['name'] ?? '';
$position = $_POST['position'] ?? '';
$role = $_POST['role'] ?? '';
$importance = $_POST['importance'] ?? '';
$experience = intval($_POST['experience'] ?? 0);
$department_id = !empty($_POST['department_id']) ? intval($_POST['department_id']) : null;
$linkedin = $_POST['linkedin'] ?? '';
$twitter = $_POST['twitter'] ?? '';
$facebook = $_POST['facebook'] ?? '';
$instagram = $_POST['instagram'] ?? '';
$website = $_POST['website'] ?? '';

if (empty($name) || empty($position)) {
    $_SESSION['error'] = 'Remplissez tous les champs obligatoires';
    header('Location: ../?page=admin-dashboard&section=teams');
    exit;
}

$image_file = '';
$image_width = 0;
$image_height = 0;

try {
    // Traiter l'image s'il y en a une
    if (!empty($_FILES['image']['name'])) {
        try {
            // Images carrées 400x400px pour avatars circulaires
            $result = processAndSaveImage($_FILES['image'], 'team', 400, 400, 90);
            
            $image_file = $result['filename'];
            $image_width = $result['width'];
            $image_height = $result['height'];
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erreur traitement image: ' . $e->getMessage();
            header('Location: ../?page=admin-dashboard&section=teams');
            exit;
        }
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Mise à jour
        $id = intval($_POST['id']);
        
        $stmt = $pdo->prepare("SELECT * FROM teams WHERE id = ?");
        $stmt->execute([$id]);
        $team = $stmt->fetch();
        
        if (!$team) {
            $_SESSION['error'] = 'Membre non trouvé';
            header('Location: ../?page=admin-dashboard&section=teams');
            exit;
        }
        
        // Si pas de nouvelle image, garder l'ancienne
        if (empty($image_file)) {
            $image_file = $team['image'];
            $image_width = $team['image_width'] ?? 0;
            $image_height = $team['image_height'] ?? 0;
        } else {
            // Supprimer l'ancienne image
            if (!empty($team['image'])) {
                deleteImage($team['image']);
            }
        }
        
        $stmt = $pdo->prepare("UPDATE teams SET name = ?, position = ?, role = ?, importance = ?, experience = ?, image = ?, image_width = ?, image_height = ?, image_processed_at = NOW(), department_id = ?, linkedin = ?, twitter = ?, facebook = ?, instagram = ?, website = ? WHERE id = ?");
        $stmt->execute([$name, $position, $role, $importance, $experience, $image_file, $image_width, $image_height, $department_id, $linkedin, $twitter, $facebook, $instagram, $website, $id]);
        $_SESSION['success'] = 'Membre mis à jour avec succès !';
    } else {
        // Création
        if (empty($image_file)) {
            $_SESSION['error'] = 'Une photo est requise pour créer un nouveau membre';
            header('Location: ../?page=admin-dashboard&section=teams');
            exit;
        }
        
        $stmt = $pdo->prepare("INSERT INTO teams (name, position, role, importance, experience, image, image_width, image_height, image_processed_at, department_id, linkedin, twitter, facebook, instagram, website) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $position, $role, $importance, $experience, $image_file, $image_width, $image_height, $department_id, $linkedin, $twitter, $facebook, $instagram, $website]);
        $_SESSION['success'] = 'Membre ajouté avec succès !';
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de l\'enregistrement : ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=teams');
exit;
?>
