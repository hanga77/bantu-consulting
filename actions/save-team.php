<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$name = $_POST['name'] ?? '';
$position = $_POST['position'] ?? '';
$role = $_POST['role'] ?? '';
$importance = $_POST['importance'] ?? '';
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
$uploads_dir = '../uploads';

try {
    // Vérifier que le dossier uploads existe
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0755, true);
    }

    // Traiter l'image s'il y en a une
    if (!empty($_FILES['image']['name'])) {
        // Vérifier le type de fichier
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $_SESSION['error'] = 'Format de fichier non autorisé. Utilisez JPG, PNG ou GIF.';
            header('Location: ../?page=admin-dashboard&section=teams');
            exit;
        }
        
        // Vérifier la taille (max 2MB)
        if ($_FILES['image']['size'] > 2097152) {
            $_SESSION['error'] = 'Le fichier est trop volumineux (max 2MB)';
            header('Location: ../?page=admin-dashboard&section=teams');
            exit;
        }
        
        // Vérifier les erreurs d'upload
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $error_messages = [
                UPLOAD_ERR_INI_SIZE => 'Fichier trop volumineux (dépassement limite serveur)',
                UPLOAD_ERR_FORM_SIZE => 'Fichier trop volumineux (dépassement limite formulaire)',
                UPLOAD_ERR_PARTIAL => 'Fichier partiellement uploadé',
                UPLOAD_ERR_NO_FILE => 'Aucun fichier uploadé',
                UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant',
                UPLOAD_ERR_CANT_WRITE => 'Erreur d\'écriture sur le disque'
            ];
            $_SESSION['error'] = $error_messages[$_FILES['image']['error']] ?? 'Erreur lors de l\'upload';
            header('Location: ../?page=admin-dashboard&section=teams');
            exit;
        }
        
        // Créer un nom de fichier unique et sécurisé
        $image_file = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['image']['name']));
        $target_path = $uploads_dir . '/' . $image_file;
        
        // Déplacer le fichier
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $_SESSION['error'] = 'Erreur lors de l\'upload de l\'image. Vérifiez les permissions du dossier /uploads/';
            header('Location: ../?page=admin-dashboard&section=teams');
            exit;
        }
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Mise à jour
        $id = intval($_POST['id']);
        
        // Récupérer les données actuelles
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
        }
        
        $stmt = $pdo->prepare("UPDATE teams SET name = ?, position = ?, role = ?, importance = ?, image = ?, department_id = ?, linkedin = ?, twitter = ?, facebook = ?, instagram = ?, website = ? WHERE id = ?");
        $stmt->execute([$name, $position, $role, $importance, $image_file, $department_id, $linkedin, $twitter, $facebook, $instagram, $website, $id]);
        $_SESSION['success'] = 'Membre mis à jour avec succès !';
    } else {
        // Création
        if (empty($image_file)) {
            $_SESSION['error'] = 'Une photo est requise pour créer un nouveau membre';
            header('Location: ../?page=admin-dashboard&section=teams');
            exit;
        }
        
        $stmt = $pdo->prepare("INSERT INTO teams (name, position, role, importance, image, department_id, linkedin, twitter, facebook, instagram, website) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $position, $role, $importance, $image_file, $department_id, $linkedin, $twitter, $facebook, $instagram, $website]);
        $_SESSION['success'] = 'Membre ajouté avec succès !';
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de l\'enregistrement : ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=teams');
exit;
?>
