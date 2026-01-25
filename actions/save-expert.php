<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$name = $_POST['name'] ?? '';
$specialty = $_POST['specialty'] ?? '';
$description = $_POST['description'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$image = null;

if (empty($name) || empty($specialty)) {
    $_SESSION['error'] = 'Nom et spécialité sont obligatoires';
    header('Location: ../?page=admin-dashboard&section=experts');
    exit;
}

// Traiter l'image
if (!empty($_FILES['image']['name'])) {
    $upload_dir = '../uploads/experts/';
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
        $sql = "UPDATE experts SET name = :name, specialty = :specialty, description = :description, email = :email, phone = :phone";
        $params = [
            ':name' => $name,
            ':specialty' => $specialty,
            ':description' => $description,
            ':email' => $email,
            ':phone' => $phone
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
        $stmt = $pdo->prepare("INSERT INTO experts (name, specialty, description, email, phone, image) 
                               VALUES (:name, :specialty, :description, :email, :phone, :image)");
        $stmt->execute([
            ':name' => $name,
            ':specialty' => $specialty,
            ':description' => $description,
            ':email' => $email,
            ':phone' => $phone,
            ':image' => $image
        ]);
    }
    
    $_SESSION['success'] = 'Expert sauvegardé avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=experts');
exit;
?>
