<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bantu_consulting');

$base_dir = __DIR__;
$uploads_dir = $base_dir . '/uploads';

// Créer les dossiers nécessaires
$folders = ['uploads', 'pages', 'templates', 'admin', 'actions', 'config', 'assets'];
$created_folders = [];
$failed_folders = [];

foreach ($folders as $folder) {
    $path = $base_dir . '/' . $folder;
    if (!is_dir($path)) {
        if (mkdir($path, 0755, true)) {
            $created_folders[] = $folder;
        } else {
            $failed_folders[] = $folder;
        }
    }
}

try {
    // Connexion sans sélectionner la base
    $pdo = new PDO(
        "mysql:host=" . DB_HOST,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Créer la base de données
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $pdo->exec("USE " . DB_NAME);

    // Table users
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE,
        password VARCHAR(255),
        email VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Table services
    $pdo->exec("CREATE TABLE IF NOT EXISTS services (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(100),
        description TEXT,
        icon VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Table projects
    $pdo->exec("CREATE TABLE IF NOT EXISTS projects (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(100),
        description TEXT,
        short_description VARCHAR(255),
        image VARCHAR(255),
        status VARCHAR(50),
        start_date DATE,
        end_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Table project_members
    $pdo->exec("CREATE TABLE IF NOT EXISTS project_members (
        id INT PRIMARY KEY AUTO_INCREMENT,
        project_id INT,
        member_name VARCHAR(100),
        role VARCHAR(100),
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
    )");

    // Table project_images - NOUVELLE
    $pdo->exec("CREATE TABLE IF NOT EXISTS project_images (
        id INT PRIMARY KEY AUTO_INCREMENT,
        project_id INT NOT NULL,
        image VARCHAR(255) NOT NULL,
        title VARCHAR(100),
        description TEXT,
        order_pos INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
    )");

    // Table departments
    $pdo->exec("CREATE TABLE IF NOT EXISTS departments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100),
        description TEXT,
        department_type VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Table teams
    $pdo->exec("CREATE TABLE IF NOT EXISTS teams (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100),
        position VARCHAR(100),
        role VARCHAR(255),
        importance VARCHAR(255),
        experience INT DEFAULT 0,
        image VARCHAR(255),
        department_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
    )");

    // Table contacts
    $pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100),
        email VARCHAR(100),
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Table carousel - CORRECTED
    $pdo->exec("CREATE TABLE IF NOT EXISTS carousel (
        id INT PRIMARY KEY AUTO_INCREMENT,
        image VARCHAR(255),
        title VARCHAR(100),
        description TEXT,
        order_pos INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Table about
    $pdo->exec("CREATE TABLE IF NOT EXISTS about (
        id INT PRIMARY KEY AUTO_INCREMENT,
        motto TEXT,
        description TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    // Table site_settings with video support
    $pdo->exec("CREATE TABLE IF NOT EXISTS site_settings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        site_name VARCHAR(100),
        site_logo VARCHAR(255),
        site_favicon VARCHAR(255),
        site_description TEXT,
        site_keywords VARCHAR(255),
        contact_email VARCHAR(100),
        contact_email2 VARCHAR(100),
        phone VARCHAR(20),
        address TEXT,
        presentation_video VARCHAR(500),
        meta_title VARCHAR(255),
        meta_description VARCHAR(255),
        footer_text TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    // Insérer paramètres par défaut
    $pdo->exec("INSERT IGNORE INTO site_settings (
        id, site_name, site_description, site_keywords, 
        contact_email, contact_email2, phone, address,
        presentation_video,
        meta_title, meta_description, footer_text
    ) VALUES (
        1, 
        'Bantu Consulting',
        'Solutions innovantes en conseil, stratégie et transformation digitale',
        'consulting, conseil, stratégie, transformation digitale',
        'contact@bantu-consulting.com',
        'info@bantu-consulting.com',
        '+243 818 818 818',
        'Kinshasa, République Démocratique du Congo',
        'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'Bantu Consulting | Conseil & Stratégie',
        'Solutions complètes de conseil en stratégie et transformation digitale',
        '© 2024 Bantu Consulting. Tous droits réservés.'
    )");

    // Insérer la devise et à propos
    $pdo->exec("INSERT IGNORE INTO about (id, motto, description) VALUES (1, 
        'Votre succès est notre mission',
        'Bantu Consulting est une entreprise spécialisée dans le conseil et l\'accompagnement stratégique des organisations. Fort d\'une équipe d\'experts expérimentés dans les domaines clés de la gestion d\'entreprise, nous mettons notre expertise au service de vos objectifs de croissance et de transformation. Depuis notre création, nous avons accompagné plus de 150 entreprises à travers l\'Afrique centrale dans leurs projets de développement, de restructuration et de digitalisation.'
    )");

    // Insérer les départements/pôles
    $pdo->exec("INSERT IGNORE INTO departments (name, description, department_type) VALUES 
        ('Pôle LBC/FT', 'Pôle Logistique et Business Consulting / Finance et Trésorerie', 'pole'),
        ('Pôle DCA/DID/DIDH', 'Pôle Digital et Cybersécurité', 'pole'),
        ('Département RH', 'Ressources Humaines', 'department'),
        ('Département GCTD', 'Gestion Comptabilité et Trésorerie', 'department')
    ");

    // Insérer des services enrichis
    $services = [
        ['Conseil Stratégique', 'Accompagnement dans l\'élaboration de stratégies d\'affaires durables et adaptées à votre contexte de marché. Nous vous aidons à définir votre vision, vos objectifs et votre plan d\'action pour les 3 à 5 prochaines années.'],
        ['Transformation Digitale', 'Solutions complètes de digitalisation pour moderniser vos processus et améliorer votre efficacité opérationnelle. Du diagnostic digital au déploiement, nous vous accompagnons à chaque étape.'],
        ['Optimisation Logistique', 'Analyse et optimisation de vos chaînes logistiques pour réduire les coûts et améliorer la qualité de service. Nous identifions les goulots d\'étranglement et proposons des solutions sur mesure.'],
        ['Cybersécurité', 'Protection complète de vos données et systèmes informatiques contre les menaces cyber actuelles. Audit, mise en conformité et déploiement de solutions de sécurité avancées.'],
        ['Recrutement & Formation', 'Services complets de recrutement et de formation pour renforcer les compétences de votre équipe. De la définition des besoins au suivi post-recrutement.'],
        ['Gestion Financière', 'Pilotage et optimisation de votre gestion financière et trésorerie pour maximiser votre rentabilité. Tableaux de bord, contrôle de gestion et prévisions financières.'],
        ['Audit & Conformité', 'Services d\'audit, de conformité et de contrôle interne pour sécuriser votre activité et vous assurer du respect des réglementations en vigueur.'],
        ['Gouvernance d\'Entreprise', 'Mise en place de structures de gouvernance robustes et transparentes. Nous vous aidons à mettre en place les mécanismes de contrôle et de décision appropriés.']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO services (title, description) VALUES (?, ?)");
    foreach ($services as $service) {
        $stmt->execute($service);
    }

    // Insérer des projets
    $projects = [
        ['Transformation Digitale - Banque XYZ', 'Accompagnement complet de la transformation digitale d\'une grande banque avec mise en place de nouveaux outils numériques, formation des collaborateurs et optimisation des processus.', 'Transformation digitale d\'une institution bancaire majeure', 'projet1.jpg', 'Terminé', '2023-01-15', '2023-09-30'],
        ['Restructuration Organisationnelle - Groupe Commerce', 'Réorganisation complète d\'un groupe commercial avec redéfinition des processus, optimisation des structures et accompagnement du changement.', 'Restructuration d\'un groupe commercial', 'projet2.jpg', 'Terminé', '2023-03-01', '2023-10-31'],
        ['Optimisation Logistique - Distribution Région Est', 'Analyse et optimisation de la chaîne logistique d\'une grande entreprise de distribution couvrant la région est du pays.', 'Optimisation logistique pour une entreprise de distribution', 'projet3.jpg', 'En cours', '2024-01-10', null],
        ['Implémentation ERP - Groupe Textile', 'Sélection, paramétrage et implémentation d\'une solution ERP pour un groupe textile avec formation et support utilisateur.', 'Implémentation d\'une solution ERP', 'projet4.jpg', 'Terminé', '2022-06-01', '2023-05-31'],
        ['Plan d\'Action 5 Ans - Holding Familial', 'Élaboration d\'un plan stratégique 5 ans pour un holding familial avec analyse de marché, redéfinition du portefeuille et plan d\'investissement.', 'Plan stratégique 5 ans pour holding', 'projet5.jpg', 'Terminé', '2023-02-01', '2023-06-30']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO projects (title, description, short_description, image, status, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($projects as $project) {
        $stmt->execute($project);
    }

    // Insérer des membres d'équipe
    $teams = [
        ['Jean Kamba Diba', 'Directeur du Pôle LBC/FT', 'Directeur du Pôle avec 15 ans d\'expérience en logistique et finance. Pilote les stratégies de réduction de coûts et d\'optimisation financière.', 'Responsable', 1, 'team1.jpg'],
        ['Marie Kasanda', 'Manager Logistique', 'Spécialiste en optimisation de chaînes logistiques avec expertise en supply chain management et lean logistics.', 'Manager', 1, 'team2.jpg'],
        ['Paul Mbelu', 'Consultant Finance', 'Expert en gestion financière et trésorerie avec expertise dans les PME et groupes moyens.', 'Consultant', 1, 'team3.jpg'],
        ['Sophie Tshishimbi', 'Directrice du Pôle Digital', 'Directrice du Pôle avec expertise en transformation digitale et cybersécurité. 12 ans d\'expérience dans le secteur IT.', 'Responsable', 2, 'team4.jpg'],
        ['David Masamba', 'Responsable Cybersécurité', 'Expert en sécurité informatique avec certifications CISSP et expertise en audit de sécurité.', 'Manager', 2, 'team5.jpg'],
        ['Yvonne Kenfondo', 'Consultante Transformation Digitale', 'Spécialiste en implémentation de solutions cloud et automatisation de processus.', 'Consultant', 2, 'team6.jpg'],
        ['Robert Mukendi', 'Directeur des Ressources Humaines', 'Directeur RH avec 18 ans d\'expérience en gestion des talents et développement organisationnel.', 'Responsable', 3, 'team7.jpg'],
        ['Fatima Diallo', 'Manager Recrutement', 'Responsable du recrutement avec expertise en sélection de cadres et professionnels.', 'Manager', 3, 'team8.jpg'],
        ['Laurent Ekila', 'Responsable Formation', 'Spécialiste en design pédagogique et développement des compétences.', 'Consultant', 3, 'team9.jpg'],
        ['Monique Mvemba', 'Directrice GCTD', 'Directrice du département avec expertise en comptabilité, fiscalité et trésorerie.', 'Responsable', 4, 'team10.jpg'],
        ['Christophe Balomba', 'Expert-Comptable', 'Expert-comptable avec 10 ans d\'expérience et maîtrise des normes IFRS et SYSCOA.', 'Consultant', 4, 'team11.jpg'],
        ['Nadine Mobeke', 'Auditrice Interne', 'Auditrice interne et consultante en audit financier et contrôle interne.', 'Consultant', 4, 'team12.jpg']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO teams (name, position, role, importance, image, department_id) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($teams as $team) {
        $stmt->execute($team);
    }

    // Insérer des images carrousel
    $carousel_items = [
        ['carousel1.jpg', 'Solutions de Conseil Innovantes', 'Accompagnement stratégique et opérationnel de votre entreprise', 0],
        ['carousel2.jpg', 'Transformation Digitale', 'Modernisez vos processus et améliorez votre efficacité', 1],
        ['carousel3.jpg', 'Expertise et Expérience', '15 ans d\'expérience au service de plus de 150 entreprises', 2]
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO carousel (image, title, description, order_pos) VALUES (?, ?, ?, ?)");
    foreach ($carousel_items as $item) {
        $stmt->execute($item);
    }

    // Créer un compte admin
    $admin_password = password_hash('admin123', PASSWORD_BCRYPT);
    $pdo->exec("INSERT IGNORE INTO users (username, password, email) VALUES ('admin', '$admin_password', 'admin@bantu-consulting.com')");

    echo '<div style="font-family: Arial; margin: 50px; text-align: center;">';
    echo '<h1 style="color: green;">✅ Installation réussie !</h1>';
    echo '<p>La base de données et les dossiers ont été créés avec succès.</p>';
    
    if (!empty($created_folders)) {
        echo '<p><strong>Dossiers créés :</strong> ' . implode(', ', $created_folders) . '</p>';
    }
    
    if (!empty($failed_folders)) {
        echo '<p style="color: orange;"><strong>⚠️ Dossiers existants :</strong> ' . implode(', ', $failed_folders) . '</p>';
    }
    
    echo '<hr>';
    echo '<p><strong>📊 Données ajoutées :</strong></p>';
    echo '<ul style="text-align: left; display: inline-block;">';
    echo '<li>✓ 5 projets de démonstration</li>';
    echo '<li>✓ 8 services</li>';
    echo '<li>✓ 12 membres d\'équipe</li>';
    echo '<li>✓ 4 départements/pôles</li>';
    echo '<li>✓ 3 images carrousel</li>';
    echo '</ul>';
    echo '<hr>';
    echo '<p><strong>📋 Identifiants de connexion Admin :</strong></p>';
    echo '<p>Utilisateur : <strong>admin</strong></p>';
    echo '<p>Mot de passe : <strong>admin123</strong></p>';
    echo '<hr>';
    echo '<p><a href="generate-sample-images.php" style="padding: 10px 20px; background: #f39c12; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px;">🖼️ Générer images</a>';
    echo '<a href="index.php" style="padding: 10px 20px; background: #1a5490; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px;">Accéder au site</a>';
    echo '<a href="?page=admin-login" style="padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px;">Se connecter Admin</a></p>';
    echo '<p style="margin-top: 30px; color: #666; font-size: 12px;">⚠️ Supprimez ce fichier (install.php) après installation pour des raisons de sécurité.</p>';
    echo '</div>';

} catch (PDOException $e) {
    echo '<div style="font-family: Arial; margin: 50px; color: red;">';
    echo '<h1>❌ Erreur d\'installation</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><a href="install.php">Réessayer</a></p>';
    echo '</div>';
}
?>
