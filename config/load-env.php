<?php
/**
 * Charger les variables d'environnement depuis .env
 */

function loadEnvFile($filepath) {
    if (!file_exists($filepath)) {
        throw new Exception(".env file not found at: $filepath");
    }
    
    $lines = file($filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Ignorer les commentaires
        if (strpos(trim($line), '#') === 0) continue;
        
        // Parser les variables
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Supprimer les guillemets
            $value = trim($value, '"\'');
            
            // Définir la variable
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

// Charger le fichier .env
try {
    $env_file = __DIR__ . '/.env';
    if (file_exists($env_file)) {
        loadEnvFile($env_file);
    }
} catch (Exception $e) {
    error_log("Erreur: " . $e->getMessage());
}

// Fonctions d'accès aux variables
function env($key, $default = null) {
    $value = getenv($key);
    return $value !== false ? $value : $default;
}

function isProduction() {
    return env('ENVIRONMENT') === 'production';
}

function isDebugMode() {
    return env('DEBUG') === 'true';
}
?>
