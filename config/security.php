<?php
/**
 * Configuration de sécurité
 * Paramètres de protection de la plateforme
 */

// ========================================
// SESSIONS ET AUTHENTIFICATION
// ========================================

// Durée de session (30 minutes)
define('SESSION_TIMEOUT', 1800);

// Régénérer l'ID de session
session_regenerate_id(true);

// Définir les paramètres de cookie sécurisés
if (!headers_sent()) {
    session_set_cookie_params([
        'lifetime' => SESSION_TIMEOUT,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'), // HTTPS only
        'httponly' => true, // JavaScript ne peut pas accéder
        'samesite' => 'Strict' // Protection CSRF
    ]);
}

// ========================================
// PROTECTION CONTRE LES ATTAQUES
// ========================================

// CSRF Token - Générer un token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Vérifier CSRF Token
function verifyCsrfToken($token) {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

// Générer un formulaire HTML avec CSRF Token
function renderCsrfToken() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
}

// ========================================
// VALIDATION ET SANITISATION
// ========================================

// Sanitiser les entrées
function sanitizeInput($input, $type = 'string') {
    if (is_array($input)) {
        return array_map(function($value) use ($type) {
            return sanitizeInput($value, $type);
        }, $input);
    }
    
    switch ($type) {
        case 'email':
            return filter_var($input, FILTER_SANITIZE_EMAIL);
        case 'url':
            return filter_var($input, FILTER_SANITIZE_URL);
        case 'int':
            return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        case 'string':
        default:
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

// Valider les entrées
function validateInput($input, $type = 'string') {
    switch ($type) {
        case 'email':
            return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
        case 'url':
            return filter_var($input, FILTER_VALIDATE_URL) !== false;
        case 'int':
            return filter_var($input, FILTER_VALIDATE_INT) !== false;
        case 'string':
            return !empty($input) && is_string($input);
        default:
            return true;
    }
}

// ========================================
// RATE LIMITING (Protection DDoS)
// ========================================

class RateLimiter {
    private $storage_file;
    private $max_attempts;
    private $timeout;
    
    public function __construct($max_attempts = 5, $timeout = 300) {
        $this->storage_file = __DIR__ . '/../logs/rate_limit.json';
        $this->max_attempts = $max_attempts;
        $this->timeout = $timeout;
        
        if (!is_dir(dirname($this->storage_file))) {
            mkdir(dirname($this->storage_file), 0755, true);
        }
    }
    
    public function isLimited($identifier) {
        $data = $this->loadData();
        $current_time = time();
        
        if (!isset($data[$identifier])) {
            $data[$identifier] = ['attempts' => 1, 'first_attempt' => $current_time];
            $this->saveData($data);
            return false;
        }
        
        $time_passed = $current_time - $data[$identifier]['first_attempt'];
        
        if ($time_passed > $this->timeout) {
            // Réinitialiser après timeout
            $data[$identifier] = ['attempts' => 1, 'first_attempt' => $current_time];
            $this->saveData($data);
            return false;
        }
        
        $data[$identifier]['attempts']++;
        $this->saveData($data);
        
        return $data[$identifier]['attempts'] > $this->max_attempts;
    }
    
    private function loadData() {
        if (file_exists($this->storage_file)) {
            return json_decode(file_get_contents($this->storage_file), true) ?? [];
        }
        return [];
    }
    
    private function saveData($data) {
        file_put_contents($this->storage_file, json_encode($data));
    }
}

// ========================================
// LOGGING DE SÉCURITÉ
// ========================================

function logSecurityEvent($event_type, $description, $user_id = null) {
    $log_file = __DIR__ . '/../logs/security.log';
    
    if (!is_dir(dirname($log_file))) {
        mkdir(dirname($log_file), 0755, true);
    }
    
    $log_entry = json_encode([
        'timestamp' => date('Y-m-d H:i:s'),
        'event_type' => $event_type,
        'description' => $description,
        'user_id' => $user_id,
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
    ]) . PHP_EOL;
    
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// ========================================
// PROTECTION CONTRE LES INJECTIONS
// ========================================

// Préparation des requêtes SQL
function prepareQuery($pdo, $sql, $params = []) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        logSecurityEvent('SQL_ERROR', $e->getMessage());
        throw new Exception("Erreur de base de données");
    }
}

// ========================================
// VALIDATION DE SESSION
// ========================================

function validateSession() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ?page=admin-login');
        exit;
    }
    
    // Vérifier le timeout
    if (isset($_SESSION['last_activity'])) {
        $inactive_time = time() - $_SESSION['last_activity'];
        
        if ($inactive_time > SESSION_TIMEOUT) {
            session_destroy();
            header('Location: ?page=admin-login&timeout=1');
            exit;
        }
    }
    
    $_SESSION['last_activity'] = time();
}

// ========================================
// PROTECTION DE MOT DE PASSE
// ========================================

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function isStrongPassword($password) {
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $special = preg_match('@[^\w]@', $password);
    $length = strlen($password) >= 12;
    
    return $uppercase && $lowercase && $number && $special && $length;
}

// ========================================
// GESTION DES ERREURS SÉCURISÉES
// ========================================

function handleSecureError($errno, $errstr, $errfile, $errline) {
    $error_message = "[$errno] $errstr on line $errline of $errfile";
    logSecurityEvent('PHP_ERROR', $error_message);
    
    // En production, afficher un message générique
    if (strpos($_SERVER['HTTP_HOST'], 'localhost') === false) {
        echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        exit;
    }
    
    // En développement, afficher l'erreur
    return false;
}

set_error_handler('handleSecureError');

// ========================================
// VÉRIFICATION DES PERMISSIONS
// ========================================

function checkPermission($required_role = 'admin') {
    validateSession();
    
    if ($_SESSION['role'] !== $required_role) {
        logSecurityEvent('UNAUTHORIZED_ACCESS', "Tentative d'accès non autorisé", $_SESSION['user_id']);
        http_response_code(403);
        die("Accès refusé");
    }
}

// ========================================
// GÉNÉRATION DE NONCE
// ========================================

function generateNonce($action) {
    $nonce = wp_create_nonce($action);
    $_SESSION['nonce_' . $action] = $nonce;
    return $nonce;
}

function verifyNonce($nonce, $action) {
    return isset($_SESSION['nonce_' . $action]) && 
           hash_equals($_SESSION['nonce_' . $action], $nonce);
}

function wp_create_nonce($action = -1) {
    return substr(hash('sha256', $action . $_SESSION['csrf_token']), 0, 10);
}

?>
