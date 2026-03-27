<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Token de sécurité invalide.';
    header('Location: ../?page=admin-dashboard&section=services');
    exit;
}

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$contact_email = $_POST['contact_email'] ?? '';
$contact_phone = $_POST['contact_phone'] ?? '';
$website = $_POST['website'] ?? '';

// Avantages
$benefit1_title = $_POST['benefit1_title'] ?? '';
$benefit1_desc = $_POST['benefit1_desc'] ?? '';
$benefit2_title = $_POST['benefit2_title'] ?? '';
$benefit2_desc = $_POST['benefit2_desc'] ?? '';
$benefit3_title = $_POST['benefit3_title'] ?? '';
$benefit3_desc = $_POST['benefit3_desc'] ?? '';
$benefit4_title = $_POST['benefit4_title'] ?? '';
$benefit4_desc = $_POST['benefit4_desc'] ?? '';

// Processus
$process1_title = $_POST['process1_title'] ?? '';
$process1_desc = $_POST['process1_desc'] ?? '';
$process2_title = $_POST['process2_title'] ?? '';
$process2_desc = $_POST['process2_desc'] ?? '';
$process3_title = $_POST['process3_title'] ?? '';
$process3_desc = $_POST['process3_desc'] ?? '';
$process4_title = $_POST['process4_title'] ?? '';
$process4_desc = $_POST['process4_desc'] ?? '';

// Faits
$fact1 = $_POST['fact1'] ?? '';
$fact2 = $_POST['fact2'] ?? '';
$fact3 = $_POST['fact3'] ?? '';
$fact4 = $_POST['fact4'] ?? '';

if (empty($title) || empty($description)) {
    $_SESSION['error'] = 'Titre et description sont obligatoires';
    header('Location: ../?page=admin-dashboard&section=services');
    exit;
}

try {
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Mise à jour
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare("UPDATE services SET 
            title = :title, 
            description = :description, 
            contact_email = :contact_email, 
            contact_phone = :contact_phone, 
            website = :website,
            benefit1_title = :benefit1_title,
            benefit1_desc = :benefit1_desc,
            benefit2_title = :benefit2_title,
            benefit2_desc = :benefit2_desc,
            benefit3_title = :benefit3_title,
            benefit3_desc = :benefit3_desc,
            benefit4_title = :benefit4_title,
            benefit4_desc = :benefit4_desc,
            process1_title = :process1_title,
            process1_desc = :process1_desc,
            process2_title = :process2_title,
            process2_desc = :process2_desc,
            process3_title = :process3_title,
            process3_desc = :process3_desc,
            process4_title = :process4_title,
            process4_desc = :process4_desc,
            fact1 = :fact1,
            fact2 = :fact2,
            fact3 = :fact3,
            fact4 = :fact4
            WHERE id = :id");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':contact_email' => $contact_email,
            ':contact_phone' => $contact_phone,
            ':website' => $website,
            ':benefit1_title' => $benefit1_title,
            ':benefit1_desc' => $benefit1_desc,
            ':benefit2_title' => $benefit2_title,
            ':benefit2_desc' => $benefit2_desc,
            ':benefit3_title' => $benefit3_title,
            ':benefit3_desc' => $benefit3_desc,
            ':benefit4_title' => $benefit4_title,
            ':benefit4_desc' => $benefit4_desc,
            ':process1_title' => $process1_title,
            ':process1_desc' => $process1_desc,
            ':process2_title' => $process2_title,
            ':process2_desc' => $process2_desc,
            ':process3_title' => $process3_title,
            ':process3_desc' => $process3_desc,
            ':process4_title' => $process4_title,
            ':process4_desc' => $process4_desc,
            ':fact1' => $fact1,
            ':fact2' => $fact2,
            ':fact3' => $fact3,
            ':fact4' => $fact4,
            ':id' => $id
        ]);
        $service_id = $id;
    } else {
        // Création
        $stmt = $pdo->prepare("INSERT INTO services (
            title, description, contact_email, contact_phone, website,
            benefit1_title, benefit1_desc, benefit2_title, benefit2_desc,
            benefit3_title, benefit3_desc, benefit4_title, benefit4_desc,
            process1_title, process1_desc, process2_title, process2_desc,
            process3_title, process3_desc, process4_title, process4_desc,
            fact1, fact2, fact3, fact4
        ) VALUES (
            :title, :description, :contact_email, :contact_phone, :website,
            :benefit1_title, :benefit1_desc, :benefit2_title, :benefit2_desc,
            :benefit3_title, :benefit3_desc, :benefit4_title, :benefit4_desc,
            :process1_title, :process1_desc, :process2_title, :process2_desc,
            :process3_title, :process3_desc, :process4_title, :process4_desc,
            :fact1, :fact2, :fact3, :fact4
        )");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':contact_email' => $contact_email,
            ':contact_phone' => $contact_phone,
            ':website' => $website,
            ':benefit1_title' => $benefit1_title,
            ':benefit1_desc' => $benefit1_desc,
            ':benefit2_title' => $benefit2_title,
            ':benefit2_desc' => $benefit2_desc,
            ':benefit3_title' => $benefit3_title,
            ':benefit3_desc' => $benefit3_desc,
            ':benefit4_title' => $benefit4_title,
            ':benefit4_desc' => $benefit4_desc,
            ':process1_title' => $process1_title,
            ':process1_desc' => $process1_desc,
            ':process2_title' => $process2_title,
            ':process2_desc' => $process2_desc,
            ':process3_title' => $process3_title,
            ':process3_desc' => $process3_desc,
            ':process4_title' => $process4_title,
            ':process4_desc' => $process4_desc,
            ':fact1' => $fact1,
            ':fact2' => $fact2,
            ':fact3' => $fact3,
            ':fact4' => $fact4
        ]);
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
                    $stmt = $pdo->prepare("INSERT INTO service_files (service_id, file_name, file_path, file_type) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$service_id, $file_name, $unique_name, 'pdf']);
                    $uploaded++;
                }
            }
        }
    }

    $_SESSION['success'] = 'Service sauvegardé avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header('Location: ../?page=admin-dashboard&section=services');
exit;
?>
