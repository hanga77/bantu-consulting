<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$short_description = $_POST['short_description'] ?? '';
$client = $_POST['client'] ?? '';
$status = $_POST['status'] ?? '';
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$budget = $_POST['budget'] ?? null;
$image = null;

if (empty($title) || empty($description)) {
    $_SESSION['error'] = 'Titre et description sont obligatoires';
    header('Location: ../?page=admin-dashboard&section=projects');
    exit;
}

// Traiter l'image si présente
if (!empty($_FILES['image']['name'])) {
    $upload_dir = '../uploads/projects/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        $unique_name = time() . '_' . uniqid() . '.' . $file_ext;
        $file_path = $upload_dir . $unique_name;
        
        if (move_uploaded_file($file_tmp, $file_path)) {
            $image = $unique_name;
        }
    }
}

try {
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Mise à jour
        $id = intval($_POST['id']);
        $sql = "UPDATE projects SET title = ?, description = ?, short_description = ?, client = ?, status = ?, start_date = ?, end_date = ?, budget = ?";
        $params = [$title, $description, $short_description, $client, $status, $start_date ?: null, $end_date ?: null, $budget ?: null];
        
        if ($image) {
            $sql .= ", image = ?";
            $params[] = $image;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    } else {
        // Création
        $stmt = $pdo->prepare("INSERT INTO projects (title, description, short_description, client, status, start_date, end_date, budget, image) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $short_description, $client, $status, $start_date ?: null, $end_date ?: null, $budget ?: null, $image]);
    }
    
    $_SESSION['success'] = 'Projet sauvegardé avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=projects');
exit;
?>
