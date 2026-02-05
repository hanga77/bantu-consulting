<?php
/**
 * 🧪 Script de Vérification de Santé - Bantu Consulting
 * Teste tous les systèmes critiques
 * Accès: http://localhost/test-health.php
 * À supprimer en production!
 */

// Restrictions de sécurité
define('ALLOWED_IPS', ['127.0.0.1', '::1', 'localhost']);
$client_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

if (!in_array($client_ip, ALLOWED_IPS) && php_sapi_name() !== 'cli') {
    die('⛔ Accès refusé. Ce script n\'est autorisé que localement.');
}

session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Test Santé - Bantu Consulting</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { font-size: 28px; margin-bottom: 5px; }
        .header p { opacity: 0.9; }
        .content { padding: 30px; }
        .test-section {
            margin-bottom: 25px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }
        .test-title {
            background: #f5f5f5;
            padding: 15px 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .test-content {
            padding: 15px 20px;
        }
        .test-result {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .test-result:last-child { border-bottom: none; }
        .status-icon {
            font-size: 18px;
            font-weight: bold;
        }
        .ok { color: #4caf50; }
        .warning { color: #ff9800; }
        .error { color: #f44336; }
        .summary {
            background: #f9f9f9;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .summary h3 { margin-bottom: 10px; }
        .progress-bar {
            width: 100%;
            height: 30px;
            background: #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            margin-top: 10px;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4caf50, #8bc34a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
            transition: width 0.3s;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th {
            background: #f5f5f5;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .footer {
            background: #f9f9f9;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 Test Santé du Système</h1>
            <p>Bantu Consulting - Vérification de la Configuration</p>
        </div>

        <div class="content">
            <?php
            // Collecter les résultats
            $tests = [];
            $passed = 0;
            $total = 0;

            // Fonction pour ajouter un test
            function addTest($category, $name, $condition, $info = '') {
                global $tests, $passed, $total;
                $total++;
                $result = [
                    'category' => $category,
                    'name' => $name,
                    'passed' => $condition,
                    'info' => $info
                ];
                if ($condition) $passed++;
                $tests[] = $result;
            }

            // TEST 1: PHP Configuration
            addTest('PHP', 'Version PHP', version_compare(PHP_VERSION, '7.4.0', '>='), 
                    'Version: ' . PHP_VERSION);
            addTest('PHP', 'PDO MySQL', extension_loaded('pdo_mysql'));
            addTest('PHP', 'GD Library', extension_loaded('gd'));
            addTest('PHP', 'cURL', extension_loaded('curl'));
            addTest('PHP', 'JSON', extension_loaded('json'));
            addTest('PHP', 'mbstring', extension_loaded('mbstring'));
            addTest('PHP', 'Zlib (Gzip)', extension_loaded('zlib'));
            
            // TEST 2: Configuration Fichiers
            addTest('Fichiers', '.env existe', file_exists('.env'), 
                    file_exists('.env') ? '✓ Trouvé' : '⚠️ À créer');
            addTest('Fichiers', '.htaccess existe', file_exists('.htaccess'));
            addTest('Fichiers', 'config/database.php', file_exists('config/database.php'));
            addTest('Fichiers', 'config/security.php', file_exists('config/security.php'));
            
            // TEST 3: Dossiers
            $upload_dirs = ['uploads', 'uploads/videos', 'uploads/projects', 'uploads/services', 'logs'];
            foreach ($upload_dirs as $dir) {
                $exists = is_dir($dir);
                $writable = $exists && is_writable($dir);
                addTest('Dossiers', "$dir/ (écriture)", $writable, 
                        $exists ? ($writable ? '✓' : '❌ Non writable') : '❌ Manquant');
            }

            // TEST 4: Base de Données
            try {
                require_once 'config/database.php';
                addTest('BD', 'Connexion MySQL', true, 'Connecté');
                
                // Test des tables
                $tables_required = ['site_settings', 'users', 'contacts', 'services', 'projects'];
                foreach ($tables_required as $table) {
                    try {
                        $result = $pdo->query("SHOW TABLES LIKE '$table'");
                        $exists = $result->rowCount() > 0;
                        addTest('BD', "Table: $table", $exists);
                    } catch (Exception $e) {
                        addTest('BD', "Table: $table", false, $e->getMessage());
                    }
                }
            } catch (Exception $e) {
                addTest('BD', 'Connexion MySQL', false, $e->getMessage());
            }

            // TEST 5: Sécurité
            $csrf_enabled = function_exists('verifyCsrfToken');
            addTest('Sécurité', 'CSRF Token', $csrf_enabled);
            addTest('Sécurité', 'Session HTTPS', 
                    (!empty($_SESSION) || php_sapi_name() === 'cli'));
            addTest('Sécurité', 'Sanitization', function_exists('sanitizeInput'));

            // TEST 6: Fichiers Critiques
            $critical_files = [
                'index.php',
                'config/functions.php',
                'templates/header.php',
                'templates/footer.php',
                'pages/accueil.php'
            ];
            foreach ($critical_files as $file) {
                addTest('App', basename($file), file_exists($file));
            }

            // Calculer le pourcentage
            $percentage = round(($passed / $total) * 100);
            ?>

            <div class="warning-box">
                ⚠️ <strong>Important:</strong> Ce script de test ne doit être accessible que localement.
                Supprimez le fichier avant de mettre en production!
            </div>

            <div class="summary">
                <h3>Résumé Global</h3>
                <p><strong><?php echo $passed; ?>/<?php echo $total; ?> tests réussis</strong></p>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo $percentage; ?>%">
                        <?php echo $percentage; ?>%
                    </div>
                </div>
                <p style="margin-top: 10px; font-size: 14px;">
                    Status: <strong>
                        <?php 
                        if ($percentage === 100) echo '✅ Tout est OK';
                        elseif ($percentage >= 80) echo '⚠️ Presque prêt';
                        else echo '❌ À corriger';
                        ?>
                    </strong>
                </p>
            </div>

            <?php
            // Afficher les résultats groupés par catégorie
            $grouped = [];
            foreach ($tests as $test) {
                $grouped[$test['category']][] = $test;
            }

            foreach ($grouped as $category => $tests_in_cat): ?>
                <div class="test-section">
                    <div class="test-title">
                        <span><?php echo $category; ?></span>
                        <span style="margin-left: auto; font-size: 14px;">
                            <?php 
                            $passed_cat = count(array_filter($tests_in_cat, fn($t) => $t['passed']));
                            echo "$passed_cat/" . count($tests_in_cat);
                            ?>
                        </span>
                    </div>
                    <div class="test-content">
                        <?php foreach ($tests_in_cat as $test): ?>
                            <div class="test-result">
                                <span><?php echo $test['name']; ?></span>
                                <span>
                                    <span class="status-icon <?php echo $test['passed'] ? 'ok' : 'error'; ?>">
                                        <?php echo $test['passed'] ? '✅' : '❌'; ?>
                                    </span>
                                    <?php if ($test['info']) echo ' (' . $test['info'] . ')'; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="test-section">
                <div class="test-title">📊 Informations Système</div>
                <div class="test-content">
                    <table>
                        <tr>
                            <th>Paramètre</th>
                            <th>Valeur</th>
                        </tr>
                        <tr>
                            <td>PHP Version</td>
                            <td><?php echo PHP_VERSION; ?></td>
                        </tr>
                        <tr>
                            <td>Upload Max</td>
                            <td><?php echo ini_get('upload_max_filesize'); ?></td>
                        </tr>
                        <tr>
                            <td>Post Max</td>
                            <td><?php echo ini_get('post_max_size'); ?></td>
                        </tr>
                        <tr>
                            <td>Max Execution</td>
                            <td><?php echo ini_get('max_execution_time'); ?> secondes</td>
                        </tr>
                        <tr>
                            <td>Mémoire Max</td>
                            <td><?php echo ini_get('memory_limit'); ?></td>
                        </tr>
                        <tr>
                            <td>Répertoire Courant</td>
                            <td><?php echo dirname(__FILE__); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="footer">
            ✅ Générée le <?php echo date('d/m/Y H:i:s'); ?> | 
            ⚠️ À supprimer avant déploiement en production
        </div>
    </div>
</body>
</html>
