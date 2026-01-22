<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';

if (empty($title) || empty($description)) {
    $_SESSION['error'] = 'Titre et description sont obligatoires';
    header('Location: ../?page=admin-dashboard&section=services');
    exit;
}

try {
    // Créer ou mettre à jour le service
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Mise à jour
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare("UPDATE services SET title = ?, description = ? WHERE id = ?");
        $stmt->execute([$title, $description, $id]);
        $service_id = $id;
    } else {
        // Création
        $stmt = $pdo->prepare("INSERT INTO services (title, description) VALUES (?, ?)");
        $stmt->execute([$title, $description]);
        $service_id = $pdo->lastInsertId();
    }

    // Traiter les fichiers PDF s'il y en a
    if (!empty($_FILES['files']['name'][0])) {
        $upload_dir = '../uploads/services/';
        
        // Créer le répertoire s'il n'existe pas
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_count = count($_FILES['files']['name']);
        $uploaded = 0;
        
        for ($i = 0; $i < $file_count; $i++) {
            if (!empty($_FILES['files']['name'][$i])) {
                $file_name = $_FILES['files']['name'][$i];
                $file_tmp = $_FILES['files']['tmp_name'][$i];
                $file_error = $_FILES['files']['error'][$i];
                
                // Vérifier l'extension
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                if ($file_ext !== 'pdf') {
                    continue;
                }
                
                // Vérifier les erreurs d'upload
                if ($file_error !== UPLOAD_ERR_OK) {
                    continue;
                }
                
                // Générer un nom unique
                $unique_name = time() . '_' . uniqid() . '_' . basename($file_name);
                $file_path = $upload_dir . $unique_name;
                
                // Déplacer le fichier
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Enregistrer dans la base de données
                    $stmt = $pdo->prepare("INSERT INTO service_files (service_id, file_name, file_path) VALUES (?, ?, ?)");
                    $stmt->execute([$service_id, $file_name, $unique_name]);
                    $uploaded++;
                }
            }
        }
    }

    $_SESSION['success'] = 'Service sauvegardé avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de la sauvegarde: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=services');
exit;
?>
