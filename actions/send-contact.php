<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../?page=accueil');
    exit;
}

$name = htmlspecialchars($_POST['name'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$message = htmlspecialchars($_POST['message'] ?? '');

if (empty($name) || empty($email) || empty($message)) {
    $_SESSION['error'] = 'Tous les champs sont obligatoires';
    header('Location: ../?page=accueil#contact');
    exit;
}

// Valider l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Email invalide';
    header('Location: ../?page=accueil#contact');
    exit;
}

// Insérer en base de données
try {
    $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $message]);
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de l\'enregistrement';
    header('Location: ../?page=accueil#contact');
    exit;
}

// Récupérer les emails depuis les paramètres
$settings = getSiteSettings();
$to = array_filter([
    $settings['contact_email'] ?? '',
    $settings['contact_email2'] ?? ''
]);

if (!empty($to)) {
    $to_str = implode(',', $to);
    $subject = 'Nouveau message de contact - ' . htmlspecialchars($settings['site_name'] ?? 'Bantu Consulting');
    $body = "De: $name\nEmail: $email\n\nMessage:\n$message\n\n---\nEnvoyé depuis le formulaire de contact du site";
    $headers = "From: $email\r\nContent-Type: text/plain; charset=UTF-8";

    @mail($to_str, $subject, $body, $headers);
}

$_SESSION['success'] = 'Message envoyé avec succès ! Nous vous répondrons prochainement.';
header('Location: ../?page=accueil#contact');
exit;
?>
