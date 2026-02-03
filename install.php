<?php
/**
 * Script d'installation - SÉCURISÉ POUR PRODUCTION
 * À exécuter UNE SEULE FOIS lors du déploiement initial
 */

// ⚠️ SÉCURITÉ: Vérifier que c'est une première installation
define('INSTALLATION_TOKEN', 'CHANGE_ME_IN_PRODUCTION');
define('MAX_INSTALLATIONS', 1);

// Vérifier le token d'installation
if (empty($_GET['token']) || $_GET['token'] !== INSTALLATION_TOKEN) {
    http_response_code(403);
    echo '<div style="font-family: Arial; margin: 50px; color: red; text-align: center;">';
    echo '<h1>❌ Accès Refusé</h1>';
    echo '<p>Ce script ne peut être exécuté que lors de la première installation.</p>';
    echo '<p>Pour des raisons de sécurité, cette page est protégée.</p>';
    echo '<p><strong>En production, supprimez ce fichier après l\'installation.</strong></p>';
    echo '</div>';
    exit;
}

// Vérifier si déjà installé
$checkInstalled = false;
try {
    require_once 'config/database.php';
    $result = $pdo->query("SELECT COUNT(*) as count FROM users");
    if ($result->fetch()['count'] > 0) {
        $checkInstalled = true;
    }
} catch (Exception $e) {
    // BD n'existe pas encore, c'est normal
}

if ($checkInstalled) {
    http_response_code(403);
    echo '<div style="font-family: Arial; margin: 50px; color: orange; text-align: center;">';
    echo '<h1>⚠️ Système Déjà Installé</h1>';
    echo '<p>La base de données contient déjà des données.</p>';
    echo '<p>Pour des raisons de sécurité, cette page ne peut pas être réexécutée.</p>';
    echo '<p><a href="index.php" style="padding: 10px 20px; background: #1a5490; color: white; text-decoration: none; border-radius: 5px; display: inline-block;">Retour au site</a></p>';
    echo '<p style="margin-top: 20px; font-size: 12px;"><strong>Supprimez install.php du serveur après l\'installation.</strong></p>';
    echo '</div>';
    exit;
}

echo '<div style="font-family: Arial; margin: 50px;">';
echo '<h1>📦 Installation Bantu Consulting</h1>';
echo '<hr>';

try {
    require_once 'config/database.php';
    
    // Vérifier la connexion
    echo '<h3>1️⃣ Vérification de la base de données...</h3>';
    $stmt = $pdo->query("SELECT DATABASE()");
    $result = $stmt->fetch();
    echo '<p style="color: green;">✅ Connecté à: <strong>' . htmlspecialchars($result[0]) . '</strong></p>';

    // Créer les tables si elles n'existent pas
    echo '<h3>2️⃣ Création des tables...</h3>';
    require_once 'migrate.php';

    // Insérer les données par défaut
    echo '<h3>3️⃣ Création des données par défaut...</h3>';

    // Admin principal
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $password = password_hash('admin123', PASSWORD_BCRYPT);
        $insert = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $insert->execute(['admin', $password, 'admin@bantu-consulting.com']);
        echo '<p style="color: green;">✅ Admin créé <strong>(admin / admin123)</strong></p>';
        echo '<p style="color: red;"><strong>⚠️ IMPORTANT: Changez ce mot de passe immédiatement!</strong></p>';
    } else {
        echo '<p style="color: blue;">✓ Admin existe déjà</p>';
    }

    // À Propos
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM about");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $insert = $pdo->prepare("INSERT INTO about (motto, description) VALUES (?, ?)");
        $insert->execute([
            'Expertise et passion au service des organisations',
            'Bantu Consulting est un cabinet de conseil de droit camerounais, spécialisé dans l\'accompagnement stratégique et opérationnel des institutions publiques, parapubliques et privées en Afrique.'
        ]);
        echo '<p style="color: green;">✅ Informations "À Propos" créées</p>';
    } else {
        echo '<p style="color: blue;">✓ Informations "À Propos" existent déjà</p>';
    }

    // Départements
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM departments");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $departments = [
            ['Pôle LBC/FT/FP', 'Lutte contre le Blanchiment des Capitaux et le Financement du Terrorisme', 'pole'],
            ['Pôle DCA/DIH/DIDH', 'Droit des Conflits Armés / Droit International Humanitaire / Droit International des Droits de l\'Homme', 'pole'],
            ['Département RH', 'Ressources Humaines', 'department'],
            ['Département GCTD', 'Gestion des Collectivités Territoriales Décentralisées', 'department']
        ];
        
        $insert = $pdo->prepare("INSERT INTO departments (name, description, department_type) VALUES (?, ?, ?)");
        foreach ($departments as $dept) {
            $insert->execute($dept);
        }
        echo '<p style="color: green;">✅ Départements créés (4)</p>';
    } else {
        echo '<p style="color: blue;">✓ Départements existent déjà</p>';
    }

    // Settings (Paramètres du site)
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM settings");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $settings = [
            ['site_name', 'Bantu Consulting'],
            ['site_description', 'Cabinet de conseil spécialisé en stratégie et transformation digitale'],
            ['contact_email', 'contact@bantu-consulting.com'],
            ['phone', '+243 818 818 818'],
            ['address', 'Kinshasa, République Démocratique du Congo'],
            ['meta_title', 'Bantu Consulting | Conseil & Stratégie'],
            ['meta_description', 'Cabinet de conseil en stratégie, transformation digitale et gestion de projets'],
            ['site_keywords', 'consulting, conseil, stratégie, transformation digitale, RH'],
            ['footer_text', '© 2024 Bantu Consulting. Tous droits réservés.'],
            ['facebook_url', 'https://facebook.com/bantu-consulting'],
            ['twitter_url', 'https://twitter.com/bantu-consulting'],
            ['linkedin_url', 'https://linkedin.com/company/bantu-consulting'],
            ['instagram_url', 'https://instagram.com/bantu-consulting']
        ];
        
        $insert = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
        foreach ($settings as $setting) {
            $insert->execute($setting);
        }
        echo '<p style="color: green;">✅ Paramètres du site créés (13)</p>';
    } else {
        echo '<p style="color: blue;">✓ Paramètres du site existent déjà</p>';
    }

    // Footer Settings
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM footer_settings");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $insert = $pdo->prepare("INSERT INTO footer_settings (address, phone, email, facebook, twitter, linkedin, instagram, copyright) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->execute([
            'Kinshasa, République Démocratique du Congo',
            '+243 818 818 818',
            'contact@bantu-consulting.com',
            'https://facebook.com/bantu-consulting',
            'https://twitter.com/bantu-consulting',
            'https://linkedin.com/company/bantu-consulting',
            'https://instagram.com/bantu-consulting',
            '© 2024 Bantu Consulting. Tous droits réservés.'
        ]);
        echo '<p style="color: green;">✅ Paramètres footer créés</p>';
    } else {
        echo '<p style="color: blue;">✓ Paramètres footer existent déjà</p>';
    }

    // Site Settings
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM site_settings");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $insert = $pdo->prepare("INSERT INTO site_settings (site_name, site_description, site_keywords, contact_email, phone, address, meta_title, meta_description, footer_text) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->execute([
            'Bantu Consulting',
            'Cabinet de conseil spécialisé en stratégie et transformation digitale',
            'consulting, conseil, stratégie, transformation digitale',
            'contact@bantu-consulting.com',
            '+243 818 818 818',
            'Kinshasa, République Démocratique du Congo',
            'Bantu Consulting | Conseil & Stratégie',
            'Cabinet de conseil en stratégie, transformation digitale et gestion de projets',
            '© 2024 Bantu Consulting. Tous droits réservés.'
        ]);
        echo '<p style="color: green;">✅ Paramètres site_settings créés</p>';
    } else {
        echo '<p style="color: blue;">✓ Paramètres site_settings existent déjà</p>';
    }

    // Services
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM services");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $services = [
            [
                'Lutte contre le blanchiment des capitaux et le financement du terrorisme (LBC/FT)',
                'Formation des acteurs publics et privés, Renforcement des capacités institutionnelles, conformité réglementaire',
                'contact@bantu-consulting.com',
                '+243 818 818 818',
                'https://bantu-consulting.com'
            ],
            [
                'Gestion des ressources humaines',
                'Audits RH, réorganisation, Politique de recrutement, Formation et développement des compétences',
                'contact@bantu-consulting.com',
                '+243 818 818 818',
                'https://bantu-consulting.com'
            ],
            [
                'Gestion des collectivités territoriales décentralisées',
                'Gouvernance locale, Planification stratégique, Ingénierie institutionnelle',
                'contact@bantu-consulting.com',
                '+243 818 818 818',
                'https://bantu-consulting.com'
            ]
        ];
        
        $insert = $pdo->prepare("INSERT INTO services (title, description, contact_email, contact_phone, website) VALUES (?, ?, ?, ?, ?)");
        foreach ($services as $service) {
            $insert->execute($service);
        }
        echo '<p style="color: green;">✅ Services créés (3)</p>';
    } else {
        echo '<p style="color: blue;">✓ Services existent déjà</p>';
    }

    // Projets
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM projects");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $projects = [
            ['Transformation Digitale - Banque XYZ', 'Accompagnement complet de la transformation digitale d\'une grande banque avec mise en place de nouveaux outils numériques', 'Terminé', '2023-01-15', '2023-09-30'],
            ['Restructuration Organisationnelle', 'Réorganisation complète d\'une structure avec redéfinition des processus', 'Terminé', '2023-03-01', '2023-10-31'],
            ['Optimisation Logistique', 'Analyse et optimisation de la chaîne logistique', 'En cours', '2024-01-10', null]
        ];
        
        $insert = $pdo->prepare("INSERT INTO projects (title, description, status, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
        foreach ($projects as $project) {
            $insert->execute($project);
        }
        echo '<p style="color: green;">✅ Projets créés (3)</p>';
    } else {
        echo '<p style="color: blue;">✓ Projets existent déjà</p>';
    }

    echo '<hr>';
    echo '<div style="background: #d4edda; padding: 20px; border-radius: 8px; margin-top: 20px;">';
    echo '<h3 style="color: #155724;">✅ Installation terminée avec succès !</h3>';
    echo '<p><strong>Identifiants Admin:</strong> admin / admin123</p>';
    echo '<p style="color: red;"><strong>⚠️ ACTIONS RECOMMANDÉES:</strong></p>';
    echo '<ol style="color: #155724;">';
    echo '<li>Modifiez immédiatement le mot de passe admin (utilisez un mot de passe fort)</li>';
    echo '<li>Supprimez ce fichier (install.php) du serveur</li>';
    echo '<li>Supprimez également migrate.php du serveur</li>';
    echo '<li>Configurez les permissions de fichiers (chmod 644 pour les fichiers, 755 pour les dossiers)</li>';
    echo '<li>Testez le site en production</li>';
    echo '</ol>';
    echo '<p><a href="index.php" style="padding: 10px 20px; background: #1a5490; color: white; text-decoration: none; border-radius: 5px; display: inline-block;">Aller au site</a></p>';
    echo '</div>';
    
    // Log d'installation
    $logFile = __DIR__ . '/logs/installation.log';
    @mkdir(dirname($logFile), 0755, true);
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Installation terminée depuis: " . $_SERVER['REMOTE_ADDR'] . "\n", FILE_APPEND);
    
    echo '</div>';

} catch (PDOException $e) {
    echo '<div style="background: #f8d7da; padding: 20px; border-radius: 8px; margin-top: 20px; color: #721c24;">';
    echo '<h3>❌ Erreur d\'installation</h3>';
    echo '<p><strong>Erreur:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><a href="install.php?token=' . INSTALLATION_TOKEN . '" style="padding: 10px 20px; background: #dc2626; color: white; text-decoration: none; border-radius: 5px; display: inline-block;">Réessayer</a></p>';
    echo '</div>';
    echo '</div>';
}
?>
