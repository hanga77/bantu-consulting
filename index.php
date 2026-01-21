<?php
session_start();

// Charger la base de données d'abord
require_once 'config/database.php';

// Puis charger les langues
require_once 'config/languages.php';

// Gérer la langue
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$page = $_GET['page'] ?? 'accueil';
$page = preg_replace('/[^a-z0-9_-]/', '', $page);

// Routes publiques
$public_pages = ['accueil', 'projets', 'projet-detail', 'equipes', 'departement-detail', 'services', 'apropos', 'admin-login', 'service-detail'];

// Vérification authentification admin
if (strpos($page, 'admin') === 0 && $page !== 'admin-login') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ?page=admin-login');
        exit;
    }
}

// Gestion du sitemap
if ($page === 'sitemap') {
    header('Content-Type: application/xml');
    include 'sitemap.php';
    exit;
}

// Chargement de la page
$file = "pages/{$page}.php";
if (file_exists($file)) {
    include $file;
} else {
    include "pages/accueil.php";
}

// Vider les messages de session après affichage
if (isset($_SESSION['success'])) {
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}
?>
