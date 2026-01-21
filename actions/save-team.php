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
$experience = intval($_POST['experience'] ?? 0);
$department_id = $_POST['department_id'] ?? null;

if (empty($name) || empty($position) || empty($department_id)) {
    $_SESSION['error'] = 'Remplissez tous les champs obligatoires';
    header('Location: ../?page=admin-dashboard&section=teams');
    exit;
}

$image_file = '';
if (!empty($_FILES['image']['name'])) {
    // Vérifier que le dossier uploads existe
    $uploads_dir = '../uploads';
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0755, true);
    }
    
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

try {
    $stmt = $pdo->prepare("INSERT INTO teams (name, position, role, importance, experience, image, department_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $position, $role, $importance, $experience, $image_file, $department_id]);
    $_SESSION['success'] = 'Membre ajouté avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de la création : ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=teams');
exit;
?>
