<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$settings = [
    'site_name' => $_POST['site_name'] ?? '',
    'site_description' => $_POST['site_description'] ?? '',
    'contact_email' => $_POST['contact_email'] ?? '',
    'phone' => $_POST['phone'] ?? '',
    'address' => $_POST['address'] ?? '',
    'facebook_url' => $_POST['facebook_url'] ?? '',
    'twitter_url' => $_POST['twitter_url'] ?? '',
    'linkedin_url' => $_POST['linkedin_url'] ?? '',
    'instagram_url' => $_POST['instagram_url'] ?? ''
];

try {
    foreach ($settings as $key => $value) {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                               ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$key, $value, $value]);
    }
    
    $_SESSION['success'] = 'Paramètres sauvegardés avec succès !';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=settings');
exit;
?>