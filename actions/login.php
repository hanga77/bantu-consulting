<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../?page=admin-login');
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Token de sécurité invalide.';
    header('Location: ../?page=admin-login');
    exit;
}

// Rate limiting : max 5 tentatives par 5 minutes par IP
$rl_key = 'login_rl_' . md5($_SERVER['REMOTE_ADDR'] ?? '');
$rl = $_SESSION[$rl_key] ?? ['count' => 0, 'since' => time()];
if (time() - $rl['since'] > 300) {
    $rl = ['count' => 0, 'since' => time()];
}
if ($rl['count'] >= 5) {
    $_SESSION['error'] = 'Trop de tentatives. Réessayez dans 5 minutes.';
    header('Location: ../?page=admin-login');
    exit;
}
$rl['count']++;
$_SESSION[$rl_key] = $rl;

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    $_SESSION['error'] = 'Veuillez remplir tous les champs';
    header('Location: ../?page=admin-login');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['success'] = 'Connecté avec succès';
        header('Location: ../?page=admin-dashboard');
    } else {
        $_SESSION['error'] = 'Identifiants incorrects';
        header('Location: ../?page=admin-login');
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur de connexion';
    header('Location: ../?page=admin-login');
}
exit;
?>
