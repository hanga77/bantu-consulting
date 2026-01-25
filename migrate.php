<?php
/**
 * Script de migration - Ajoute les colonnes manquantes à la base de données
 * Accédez à http://localhost/Bantu-test2/migrate.php
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bantu_consulting');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo '<div style="font-family: Arial; margin: 50px;">';
    echo '<h1>🔧 Migration de la Base de Données</h1>';
    echo '<hr>';

    // Vérifier si la colonne presentation_video existe
    $result = $pdo->query("SHOW COLUMNS FROM site_settings LIKE 'presentation_video'");
    if ($result->rowCount() === 0) {
        echo '<p>Ajout de la colonne <strong>presentation_video</strong>...</p>';
        $pdo->exec("ALTER TABLE site_settings ADD COLUMN presentation_video VARCHAR(500) AFTER address");
        echo '<p style="color: green;">✅ Colonne presentation_video ajoutée</p>';
    } else {
        echo '<p style="color: blue;">✓ Colonne presentation_video existe déjà</p>';
    }

    // Vérifier et ajouter les autres colonnes manquantes
    $columns_to_add = [
        ['name' => 'meta_title', 'definition' => 'VARCHAR(255)', 'after' => 'presentation_video'],
        ['name' => 'meta_description', 'definition' => 'VARCHAR(255)', 'after' => 'meta_title'],
        ['name' => 'footer_text', 'definition' => 'TEXT', 'after' => 'meta_description'],
    ];

    foreach ($columns_to_add as $column) {
        $result = $pdo->query("SHOW COLUMNS FROM site_settings LIKE '{$column['name']}'");
        if ($result->rowCount() === 0) {
            echo '<p>Ajout de la colonne <strong>' . $column['name'] . '</strong>...</p>';
            $pdo->exec("ALTER TABLE site_settings ADD COLUMN {$column['name']} {$column['definition']} AFTER {$column['after']}");
            echo '<p style="color: green;">✅ Colonne ' . $column['name'] . ' ajoutée</p>';
        } else {
            echo '<p style="color: blue;">✓ Colonne ' . $column['name'] . ' existe déjà</p>';
        }
    }

    // Mettre à jour les données par défaut si nécessaire
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM site_settings");
    $result = $stmt->fetch();

    if ($result['count'] === 0) {
        echo '<p>Création des paramètres par défaut...</p>';
        $pdo->exec("INSERT INTO site_settings (
            site_name, site_description, site_keywords, 
            contact_email, contact_email2, phone, address,
            presentation_video,
            meta_title, meta_description, footer_text
        ) VALUES (
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
        echo '<p style="color: green;">✅ Paramètres par défaut créés</p>';
    } else {
        echo '<p style="color: blue;">✓ Paramètres existent déjà</p>';
    }

    // Créer la table project_images si elle n'existe pas
    echo '<p>Création de la table <strong>project_images</strong>...</p>';
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
    echo '<p style="color: green;">✅ Table project_images créée</p>';

    echo '<hr>';
    echo '<p style="color: green; font-size: 18px;"><strong>✅ Migration terminée avec succès !</strong></p>';
    echo '<p><a href="index.php" style="padding: 10px 20px; background: #1a5490; color: white; text-decoration: none; border-radius: 5px;">Retour au site</a></p>';
    echo '</div>';

} catch (PDOException $e) {
    echo '<div style="font-family: Arial; margin: 50px; color: red;">';
    echo '<h1>❌ Erreur de migration</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><a href="migrate.php">Réessayer</a></p>';
    echo '</div>';
}

/**
 * Gestion des migrations de base de données
 */

function runMigrations($pdo) {
    $migrations = [
        // Users
        "CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        // Services
        "CREATE TABLE IF NOT EXISTS services (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(100) NOT NULL,
            description TEXT,
            contact_email VARCHAR(100),
            contact_phone VARCHAR(20),
            website VARCHAR(255),
            benefit1_title VARCHAR(100),
            benefit1_desc TEXT,
            benefit2_title VARCHAR(100),
            benefit2_desc TEXT,
            benefit3_title VARCHAR(100),
            benefit3_desc TEXT,
            benefit4_title VARCHAR(100),
            benefit4_desc TEXT,
            process1_title VARCHAR(100),
            process1_desc TEXT,
            process2_title VARCHAR(100),
            process2_desc TEXT,
            process3_title VARCHAR(100),
            process3_desc TEXT,
            process4_title VARCHAR(100),
            process4_desc TEXT,
            fact1 VARCHAR(255),
            fact2 VARCHAR(255),
            fact3 VARCHAR(255),
            fact4 VARCHAR(255),
            order_pos INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // Service Files
        "CREATE TABLE IF NOT EXISTS service_files (
            id INT PRIMARY KEY AUTO_INCREMENT,
            service_id INT NOT NULL,
            file_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            file_type VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
        )",
        
        // Projects
        "CREATE TABLE IF NOT EXISTS projects (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(100) NOT NULL,
            description TEXT,
            short_description VARCHAR(255),
            image VARCHAR(255),
            status VARCHAR(50),
            client VARCHAR(100),
            start_date DATE,
            end_date DATE,
            budget DECIMAL(10, 2),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // Project Members
        "CREATE TABLE IF NOT EXISTS project_members (
            id INT PRIMARY KEY AUTO_INCREMENT,
            project_id INT NOT NULL,
            member_name VARCHAR(100),
            role VARCHAR(100),
            FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
        )",
        
        // Departments
        "CREATE TABLE IF NOT EXISTS departments (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            department_type VARCHAR(50),
            order_pos INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // Teams
        "CREATE TABLE IF NOT EXISTS teams (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            position VARCHAR(100),
            role VARCHAR(255),
            importance VARCHAR(50),
            image VARCHAR(255),
            department_id INT,
            bio TEXT,
            order_pos INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
        )",
        
        // Contacts
        "CREATE TABLE IF NOT EXISTS contacts (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(20),
            subject VARCHAR(100),
            message TEXT,
            status VARCHAR(50) DEFAULT 'new',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        // Carousel
        "CREATE TABLE IF NOT EXISTS carousel (
            id INT PRIMARY KEY AUTO_INCREMENT,
            image VARCHAR(255),
            title VARCHAR(100),
            description TEXT,
            button_text VARCHAR(50),
            button_url VARCHAR(255),
            order_pos INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // About
        "CREATE TABLE IF NOT EXISTS about (
            id INT PRIMARY KEY AUTO_INCREMENT,
            motto TEXT,
            description TEXT,
            vision TEXT,
            mission TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // Settings
        "CREATE TABLE IF NOT EXISTS settings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // Experts
        "CREATE TABLE IF NOT EXISTS experts (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            specialty VARCHAR(100),
            description TEXT,
            email VARCHAR(100),
            phone VARCHAR(20),
            image VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($migrations as $sql) {
        try {
            $pdo->exec($sql);
        } catch (PDOException $e) {
            echo "❌ Erreur migration : " . $e->getMessage() . "<br>";
            return false;
        }
    }
    
    echo "✅ Toutes les tables créées<br>";
    return true;
}
?>
