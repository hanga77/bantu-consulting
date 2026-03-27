<?php
/**
 * Configuration API de Traduction
 * Gère la traduction automatique du site entier
 * 
 * API utilisée: Google Translate API (Free)
 * Langues supportées: FR, EN, ES, PT, ZH, AR, etc.
 * 
 * CLÉS API: À mettre dans le fichier .env à la racine du projet
 */

// Charger les variables d'environnement
require_once __DIR__ . '/load-env.php';

// ════════════════════════════════════════════════════════════════
// CONFIGURATION API (depuis .env)
// ════════════════════════════════════════════════════════════════

define('TRANSLATION_API_ENABLED', true);
define('TRANSLATION_API_PROVIDER', env('TRANSLATION_PROVIDER', 'google'));
define('TRANSLATION_CACHE_ENABLED', env('TRANSLATION_CACHE_ENABLED', 'true') === 'true');
define('TRANSLATION_CACHE_TIME', (int)env('TRANSLATION_CACHE_DURATION', 2592000)); // 30 jours par défaut

// Clés API
define('GOOGLE_TRANSLATE_API_KEY', env('GOOGLE_TRANSLATE_API_KEY', ''));
define('LIBRE_TRANSLATE_API_KEY', env('LIBRE_TRANSLATE_API_KEY', 'libre'));

// Langue par défaut du site
define('DEFAULT_LANGUAGE', env('DEFAULT_LANGUAGE', 'fr'));

// Langues disponibles
$SUPPORTED_LANGUAGES = [
    'fr' => ['name' => 'Français', 'native' => 'Français', 'flag' => '🇫🇷'],
    'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇬🇧'],
    'es' => ['name' => 'Español', 'native' => 'Español', 'flag' => '🇪🇸'],
    'pt' => ['name' => 'Português', 'native' => 'Português', 'flag' => '🇵🇹'],
    'zh' => ['name' => '中文', 'native' => '中文', 'flag' => '🇨🇳'],
    'ar' => ['name' => 'العربية', 'native' => 'العربية', 'flag' => '🇸🇦'],
    'sw' => ['name' => 'Swahili', 'native' => 'Kiswahili', 'flag' => '🇹🇿'],
];

// ════════════════════════════════════════════════════════════════
// CLASSE TRADUCTION
// ════════════════════════════════════════════════════════════════

class TranslationAPI {
    private $provider;
    private $cache_dir;
    private $cache_enabled;
    private $pdo;

    public function __construct($pdo = null) {
        $this->provider = TRANSLATION_API_PROVIDER;
        $this->cache_enabled = TRANSLATION_CACHE_ENABLED;
        $this->pdo = $pdo;
        
        // Créer le répertoire cache s'il n'existe pas
        $this->cache_dir = __DIR__ . '/../cache/translations';
        if (!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir, 0755, true);
        }
    }

    /**
     * Traduire un texte
     * @param string $text Texte à traduire
     * @param string $target_lang Langue cible
     * @param string $source_lang Langue source (défaut: 'fr')
     * @return string Texte traduit
     */
    public function translate($text, $target_lang, $source_lang = 'fr') {
        if (!TRANSLATION_API_ENABLED || empty($text)) {
            return $text;
        }

        // Si c'est déjà la langue source, retourner le texte
        if ($source_lang === $target_lang) {
            return $text;
        }

        // Vérifier le cache
        $cache_key = $this->getCacheKey($text, $source_lang, $target_lang);
        $cached = $this->getFromCache($cache_key);
        if ($cached) {
            return $cached;
        }

        // Appel API selon le provider
        if ($this->provider === 'google') {
            $translated = $this->translateWithGoogle($text, $target_lang, $source_lang);
        } else {
            $translated = $this->translateWithLibreTranslate($text, $target_lang, $source_lang);
        }

        // Sauvegarder en cache
        if ($translated) {
            $this->saveToCache($cache_key, $translated);
        }

        return $translated ?? $text;
    }

    /**
     * Traduction avec Google Translate API
     */
    private function translateWithGoogle($text, $target_lang, $source_lang = 'auto') {
        try {
            // URL Google Translate API (Free/Unofficial)
            $text_encoded = urlencode($text);
            $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl={$source_lang}&tl={$target_lang}&dt=t&q={$text_encoded}";
            
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
                    'timeout' => 10
                ]
            ]);

            $response = @file_get_contents($url, false, $context);
            if ($response === false) {
                return null;
            }

            $json = json_decode($response, true);
            if (!$json || !isset($json[0][0][0])) {
                return null;
            }

            // Extraire le texte traduit
            $translated = '';
            foreach ($json[0] as $item) {
                $translated .= $item[0];
            }

            return trim($translated);
        } catch (Exception $e) {
            error_log("Google Translate Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Traduction avec LibreTranslate (Alternative)
     */
    private function translateWithLibreTranslate($text, $target_lang, $source_lang = 'auto') {
        try {
            $api_key = getenv('LIBRE_TRANSLATE_KEY') ?: 'libre';
            $url = "https://api.libretranslate.de/translate";

            $data = [
                'q' => $text,
                'source' => $source_lang,
                'target' => $target_lang,
                'api_key' => $api_key
            ];

            $options = [
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/json\r\n",
                    'content' => json_encode($data),
                    'timeout' => 10
                ]
            ];

            $context = stream_context_create($options);
            $response = @file_get_contents($url, false, $context);

            if ($response === false) {
                return null;
            }

            $json = json_decode($response, true);
            return $json['translatedText'] ?? null;
        } catch (Exception $e) {
            error_log("LibreTranslate Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Traduire un tableau associatif (ex: produit, article)
     */
    public function translateArray($array, $target_lang, $fields = ['title', 'description', 'name'], $source_lang = 'fr') {
        $translated = $array;
        
        foreach ($fields as $field) {
            if (isset($translated[$field]) && is_string($translated[$field])) {
                $translated[$field] = $this->translate($translated[$field], $target_lang, $source_lang);
            }
        }

        return $translated;
    }

    /**
     * Gestion du cache
     */
    private function getCacheKey($text, $source_lang, $target_lang) {
        return md5("{$source_lang}-{$target_lang}-{$text}");
    }

    private function getFromCache($key) {
        if (!$this->cache_enabled) {
            return null;
        }

        $cache_file = $this->cache_dir . '/' . $key . '.cache';
        
        if (file_exists($cache_file)) {
            $data = json_decode(file_get_contents($cache_file), true);
            if (is_array($data) && isset($data['expires'], $data['content']) && $data['expires'] > time()) {
                return $data['content'];
            } else {
                unlink($cache_file);
            }
        }

        return null;
    }

    private function saveToCache($key, $content) {
        if (!$this->cache_enabled) {
            return;
        }

        $cache_file = $this->cache_dir . '/' . $key . '.cache';
        $data = [
            'content' => $content,
            'expires' => time() + TRANSLATION_CACHE_TIME
        ];

        file_put_contents($cache_file, json_encode($data));
    }

    /**
     * Obtenir les langues supportées
     */
    public function getSupportedLanguages() {
        global $SUPPORTED_LANGUAGES;
        return $SUPPORTED_LANGUAGES;
    }

    /**
     * Obtenir la langue actuelle
     */
    public function getCurrentLanguage() {
        if (isset($_SESSION['language']) && $_SESSION['language']) {
            return $_SESSION['language'];
        }

        if (isset($_GET['lang'])) {
            global $SUPPORTED_LANGUAGES;
            if (isset($SUPPORTED_LANGUAGES[$_GET['lang']])) {
                $_SESSION['language'] = $_GET['lang'];
                return $_GET['lang'];
            }
        }

        if (isset($_COOKIE['language'])) {
            global $SUPPORTED_LANGUAGES;
            if (isset($SUPPORTED_LANGUAGES[$_COOKIE['language']])) {
                return $_COOKIE['language'];
            }
        }

        return DEFAULT_LANGUAGE;
    }

    /**
     * Définir la langue actuelle
     */
    public function setLanguage($lang) {
        global $SUPPORTED_LANGUAGES;
        
        if (!isset($SUPPORTED_LANGUAGES[$lang])) {
            return false;
        }

        $_SESSION['language'] = $lang;
        setcookie('language', $lang, time() + 86400 * 365, '/');
        
        return true;
    }
}

// Initialiser l'instance globale
global $translator;
$translator = new TranslationAPI($pdo ?? null);

// Fonction helper pour traduction rapide
if (!function_exists('__')) {
function __($text, $target_lang = null) {
    global $translator;
    
    if ($target_lang === null) {
        $target_lang = $translator->getCurrentLanguage();
    }

    if ($target_lang === DEFAULT_LANGUAGE) {
        return $text;
    }

    return $translator->translate($text, $target_lang, DEFAULT_LANGUAGE);
}
}

// Fonction pour afficher le sélecteur de langue
if (!function_exists('getLanguageSwitcher')) {
function getLanguageSwitcher() {
    global $translator;
    $current = $translator->getCurrentLanguage();
    $languages = $translator->getSupportedLanguages();
    
    $html = '<div class="language-switcher">';
    foreach ($languages as $code => $lang) {
        $active = ($code === $current) ? ' active' : '';
        $html .= "<a href='?lang={$code}' class='lang-btn{$active}' title='{$lang['native']}'>";
        $html .= "{$lang['flag']} {$lang['name']}";
        $html .= "</a>";
    }
    $html .= '</div>';
    
    return $html;
}}
?>