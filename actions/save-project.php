<?php
session_start();
require_once '../config/database.php';
require_once '../includes/image-processing.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$short_description = trim($_POST['short_description'] ?? '');
$status = trim($_POST['status'] ?? 'en cours');
$client = trim($_POST['client'] ?? '');
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$budget = !empty($_POST['budget']) ? floatval($_POST['budget']) : null;

if (empty($title) || empty($description)) {
    $_SESSION['error'] = 'Titre et description sont obligatoires';
    header('Location: ../?page=admin-dashboard&section=projects');
    exit;
}

try {
    // Traiter l'image principale
    $image_file = '';
    $image_width = 0;
    $image_height = 0;
    
    if (!empty($_FILES['image']['name'])) {
        try {
            $result = processAndSaveImage($_FILES['image'], 'project', 1200, 800, 85);
            $image_file = $result['filename'];
            $image_width = $result['width'];
            $image_height = $result['height'];
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erreur image principale: ' . $e->getMessage();
            header('Location: ../?page=admin-dashboard&section=projects');
            exit;
        }
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Mise à jour
        $id = intval($_POST['id']);
        
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $project = $stmt->fetch();
        
        if (!$project) {
            $_SESSION['error'] = 'Projet non trouvé';
            header('Location: ../?page=admin-dashboard&section=projects');
            exit;
        }
        
        if (empty($image_file)) {
            $image_file = $project['image'];
            $image_width = $project['image_width'] ?? 0;
            $image_height = $project['image_height'] ?? 0;
        } else {
            if (!empty($project['image'])) {
                deleteImage($project['image']);
            }
        }
        
        $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, short_description = ?, status = ?, client = ?, start_date = ?, end_date = ?, budget = ?, image = ?, image_width = ?, image_height = ?, image_processed_at = NOW() WHERE id = ?");
        $stmt->execute([$title, $description, $short_description, $status, $client, $start_date, $end_date, $budget, $image_file, $image_width, $image_height, $id]);
        
        $_SESSION['success'] = 'Projet mis à jour !';
    } else {
        // Création
        if (empty($image_file)) {
            $_SESSION['error'] = 'Image principale requise';
            header('Location: ../?page=admin-dashboard&section=projects');
            exit;
        }
        
        $stmt = $pdo->prepare("INSERT INTO projects (title, description, short_description, status, client, start_date, end_date, budget, image, image_width, image_height, image_processed_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$title, $description, $short_description, $status, $client, $start_date, $end_date, $budget, $image_file, $image_width, $image_height]);
        
        $project_id = $pdo->lastInsertId();
        
        $_SESSION['success'] = 'Projet créé !';
    }
    
    // Traiter les images de galerie
    if (!empty($_FILES['gallery_images']['name'][0])) {
        $project_id = $project_id ?? $id;
        
        for ($i = 0; $i < count($_FILES['gallery_images']['name']); $i++) {
            if (!empty($_FILES['gallery_images']['name'][$i])) {
                try {
                    $file = [
                        'name' => $_FILES['gallery_images']['name'][$i],
                        'type' => $_FILES['gallery_images']['type'][$i],
                        'tmp_name' => $_FILES['gallery_images']['tmp_name'][$i],
                        'error' => $_FILES['gallery_images']['error'][$i],
                        'size' => $_FILES['gallery_images']['size'][$i]
                    ];
                    
                    $result = processAndSaveImage($file, 'gallery', 1000, 800, 85);
                    
                    $stmt = $pdo->prepare("INSERT INTO project_images (project_id, image, order_pos) VALUES (?, ?, ?)");
                    $stmt->execute([$project_id, $result['filename'], $i]);
                } catch (Exception $e) {
                    error_log("Erreur galerie: " . $e->getMessage());
                }
            }
        }
    }

} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur DB: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=projects');
exit;
?>
