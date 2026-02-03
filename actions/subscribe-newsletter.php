<?php
session_start();
require_once '../config/database.php';

$response = [
    'success' => false,
    'message' => 'Une erreur est survenue'
];

try {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $name = trim($_POST['name'] ?? '');
    
    if (!$email) {
        throw new Exception('Email invalide');
    }
    
    // Vérifier si déjà abonné
    $stmt = $pdo->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        throw new Exception('Cet email est déjà abonné');
    }
    
    // Ajouter à la base de données
    $stmt = $pdo->prepare("INSERT INTO newsletter_subscribers (email, name, status) VALUES (?, ?, 'active')");
    $stmt->execute([$email, $name]);
    
    // Envoyer email de confirmation
    require_once '../config/mail-config.php';
    $emailSent = sendNewsletterEmail($email, $name);
    
    $response['success'] = true;
    $response['message'] = 'Inscription réussie! Vérifiez votre email.';
    
    if (!$emailSent) {
        $response['message'] .= ' (Email non envoyé - vérifiez la configuration)';
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
