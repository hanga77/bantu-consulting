<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$file_id = intval($_GET['id'] ?? 0);
$service_id = intval($_GET['service_id'] ?? 0);

if ($file_id <= 0) {
    $_SESSION['error'] = 'Fichier invalide';
    header('Location: ../?page=admin-dashboard&section=services');
    exit;
}

try {
    // Récupérer les infos du fichier
    $stmt = $pdo->prepare("SELECT * FROM service_files WHERE id = ?");
    $stmt->execute([$file_id]);
    $file = $stmt->fetch();
    
    if (!$file) {
        $_SESSION['error'] = 'Fichier non trouvé';
        header('Location: ../?page=admin-dashboard&section=services');
        exit;
    }
    
    // Supprimer le fichier physique
    $file_path = '../uploads/services/' . $file['file_path'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
    
    // Supprimer l'enregistrement dans la base de données
    $stmt = $pdo->prepare("DELETE FROM service_files WHERE id = ?");
    $stmt->execute([$file_id]);
    
    $_SESSION['success'] = 'Fichier supprimé avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de la suppression: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=services&action=edit&id=' . $service_id);
exit;
?>
