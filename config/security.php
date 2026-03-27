<?php
/**
 * Fonctions de sécurité — bibliothèque pure, aucun effet de bord
 * Inclus automatiquement via config/database.php
 */

// ========================================
// VALIDATION DE SESSION
// ========================================

/**
 * Vérifie la session admin et le timeout.
 * @param string $base Préfixe du redirect ('' depuis pages/, '../' depuis actions/)
 */
function validateSession($base = '') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . $base . '?page=admin-login');
        exit;
    }

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        header('Location: ' . $base . '?page=admin-login&timeout=1');
        exit;
    }

    $_SESSION['last_activity'] = time();
}

// ========================================
// CSRF
// ========================================

function verifyCsrfToken($token) {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token ?? '');
}

function renderCsrfToken() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'] ?? '') . '">';
}

// ========================================
// MOT DE PASSE
// ========================================

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function isStrongPassword($password) {
    return strlen($password) >= 12
        && preg_match('@[A-Z]@', $password)
        && preg_match('@[a-z]@', $password)
        && preg_match('@[0-9]@', $password)
        && preg_match('@[^\w]@', $password);
}

// ========================================
// MESSAGES D'ERREUR SÉCURISÉS
// ========================================

/**
 * Retourne le message d'exception en développement,
 * ou un message générique en production.
 */
function safeErrorMessage(Exception $e, $generic = 'Une erreur est survenue. Veuillez réessayer.') {
    $env = getenv('APP_ENV') ?: 'production';
    if ($env === 'development' || $env === 'dev') {
        return $e->getMessage();
    }
    error_log($e->getMessage());
    return $generic;
}

// ========================================
// VALIDATION ET SANITISATION
// ========================================

function sanitizeInput($input, $type = 'string') {
    if (is_array($input)) {
        return array_map(fn($v) => sanitizeInput($v, $type), $input);
    }
    switch ($type) {
        case 'email': return filter_var($input, FILTER_SANITIZE_EMAIL);
        case 'url':   return filter_var($input, FILTER_SANITIZE_URL);
        case 'int':   return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        default:      return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

// ========================================
// LOGGING
// ========================================

function logSecurityEvent($event_type, $description, $user_id = null) {
    $log_dir = __DIR__ . '/../logs/';
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    $entry = json_encode([
        'timestamp'   => date('Y-m-d H:i:s'),
        'event_type'  => $event_type,
        'description' => $description,
        'user_id'     => $user_id,
        'ip'          => $_SERVER['REMOTE_ADDR'] ?? '',
        'ua'          => $_SERVER['HTTP_USER_AGENT'] ?? '',
    ]) . PHP_EOL;
    file_put_contents($log_dir . 'security.log', $entry, FILE_APPEND | LOCK_EX);
}
