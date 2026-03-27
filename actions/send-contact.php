<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../?page=accueil');
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Token de sécurité invalide.';
    header('Location: ../?page=accueil#contact');
    exit;
}

// Rate limiting : max 3 messages par 10 minutes par IP
$rl_key = 'contact_rl_' . md5($_SERVER['REMOTE_ADDR'] ?? '');
$rl = $_SESSION[$rl_key] ?? ['count' => 0, 'since' => time()];
if (time() - $rl['since'] > 600) {
    $rl = ['count' => 0, 'since' => time()];
}
if ($rl['count'] >= 3) {
    $_SESSION['error'] = 'Trop de messages envoyés. Veuillez patienter 10 minutes.';
    header('Location: ../?page=accueil#contact');
    exit;
}
$rl['count']++;
$_SESSION[$rl_key] = $rl;

// Validation et nettoyage des entrées
$name    = trim($_POST['name'] ?? '');
$message = trim($_POST['message'] ?? '');
$raw_email = trim($_POST['email'] ?? '');

if (empty($name) || empty($raw_email) || empty($message)) {
    $_SESSION['error'] = 'Tous les champs sont obligatoires.';
    header('Location: ../?page=accueil#contact');
    exit;
}

// Valider l'email proprement (sans htmlspecialchars avant)
$email = filter_var($raw_email, FILTER_VALIDATE_EMAIL);
if ($email === false) {
    $_SESSION['error'] = 'Adresse email invalide.';
    header('Location: ../?page=accueil#contact');
    exit;
}
// Supprimer tout retour chariot dans l'email (protection injection header)
$email = str_replace(["\r", "\n", "%0a", "%0d"], '', $email);

// Insérer en base de données (entrées brutes — la BDD n'est pas du HTML)
try {
    $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $message]);
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur lors de l\'enregistrement.';
    header('Location: ../?page=accueil#contact');
    exit;
}

// Envoi email — From = adresse du site, Reply-To = expéditeur
$settings = getSiteSettings();
$to = array_filter([
    $settings['contact_email'] ?? '',
    $settings['contact_email2'] ?? ''
]);

if (!empty($to)) {
    $site_email = $settings['contact_email'] ?? 'noreply@bantu-consulting.com';
    $site_name  = $settings['site_name'] ?? 'Bantu Consulting';

    $to_str  = implode(',', $to);
    $subject = '[Contact] Nouveau message de ' . mb_encode_mimeheader($name, 'UTF-8');
    $body    = "Nom : $name\nEmail : $email\n\nMessage :\n$message\n\n---\nEnvoyé depuis le formulaire de contact";
    $headers = "From: =?UTF-8?B?" . base64_encode($site_name) . "?= <$site_email>\r\n"
             . "Reply-To: $email\r\n"
             . "Content-Type: text/plain; charset=UTF-8\r\n"
             . "X-Mailer: PHP/" . PHP_VERSION;

    if (!mail($to_str, $subject, $body, $headers)) {
        error_log("Contact mail failed from $email");
    }
}

$_SESSION['success'] = 'Message envoyé avec succès ! Nous vous répondrons prochainement.';
header('Location: ../?page=accueil#contact');
exit;
?>
