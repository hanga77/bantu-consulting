<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../?page=admin-login');
    exit;
}

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
