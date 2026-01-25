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
$status = $_POST['status'] ?? 'en cours';
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$budget = $_POST['budget'] ?? null;
$image = null;

if (empty($title) || empty($description)) {
    $_SESSION['error'] = 'Titre et description sont obligatoires';
    header('Location: ../?page=admin-dashboard&section=projects');
    exit;
}

// Traiter l'image
if (!empty($_FILES['image']['name'])) {
    $upload_dir = '../uploads/projects/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        $unique_name = time() . '_' . uniqid() . '.' . $file_ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $unique_name)) {
            $image = $unique_name;
        }
    }
}

try {
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Mise à jour
        $id = intval($_POST['id']);
        $sql = "UPDATE projects SET title = :title, description = :description, short_description = :short_description, client = :client, status = :status, start_date = :start_date, end_date = :end_date, budget = :budget";
        $params = [
            ':title' => $title,
            ':description' => $description,
            ':short_description' => $short_description,
            ':client' => $client,
            ':status' => $status,
            ':start_date' => $start_date ?: null,
            ':end_date' => $end_date ?: null,
            ':budget' => $budget ?: null
        ];
        
        if ($image) {
            $sql .= ", image = :image";
            $params[':image'] = $image;
        }
        
        $sql .= " WHERE id = :id";
        $params[':id'] = $id;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    } else {
        // Création
        $stmt = $pdo->prepare("INSERT INTO projects (title, description, short_description, client, status, start_date, end_date, budget, image) 
                               VALUES (:title, :description, :short_description, :client, :status, :start_date, :end_date, :budget, :image)");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':short_description' => $short_description,
            ':client' => $client,
            ':status' => $status,
            ':start_date' => $start_date ?: null,
            ':end_date' => $end_date ?: null,
            ':budget' => $budget ?: null,
            ':image' => $image
        ]);
    }
    
    $_SESSION['success'] = 'Projet sauvegardé avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=projects');
exit;
?>
