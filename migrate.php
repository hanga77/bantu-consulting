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

    // Migration 1: Ajouter les colonnes de traitement d'image à teams
    $result = $pdo->query("SHOW COLUMNS FROM teams LIKE 'image_width'");
    if ($result->rowCount() === 0) {
        echo '<p>Ajout des colonnes de traitement d\'image à <strong>teams</strong>...</p>';
        $pdo->exec("ALTER TABLE teams ADD COLUMN image_width INT DEFAULT 0 AFTER image");
        $pdo->exec("ALTER TABLE teams ADD COLUMN image_height INT DEFAULT 0 AFTER image_width");
        $pdo->exec("ALTER TABLE teams ADD COLUMN image_processed_at TIMESTAMP NULL AFTER image_height");
        echo '<p style="color: green;">✅ Colonnes image_width, image_height, image_processed_at ajoutées à teams</p>';
    } else {
        echo '<p style="color: blue;">✓ Colonnes de traitement d\'image existent déjà dans teams</p>';
    }

    // Migration 2: Ajouter la colonne experience à teams si manquante
    $result = $pdo->query("SHOW COLUMNS FROM teams LIKE 'experience'");
    if ($result->rowCount() === 0) {
        echo '<p>Ajout de la colonne <strong>experience</strong> à teams...</p>';
        $pdo->exec("ALTER TABLE teams ADD COLUMN experience INT DEFAULT 0 AFTER role");
        echo '<p style="color: green;">✅ Colonne experience ajoutée à teams</p>';
    } else {
        echo '<p style="color: blue;">✓ Colonne experience existe déjà dans teams</p>';
    }

    // Migration 3: Ajouter les colonnes de traitement d'image à carousel
    $result = $pdo->query("SHOW COLUMNS FROM carousel LIKE 'image_width'");
    if ($result->rowCount() === 0) {
        echo '<p>Ajout des colonnes de traitement d\'image à <strong>carousel</strong>...</p>';
        $pdo->exec("ALTER TABLE carousel ADD COLUMN image_width INT DEFAULT 0 AFTER image");
        $pdo->exec("ALTER TABLE carousel ADD COLUMN image_height INT DEFAULT 0 AFTER image_width");
        $pdo->exec("ALTER TABLE carousel ADD COLUMN image_processed_at TIMESTAMP NULL AFTER image_height");
        echo '<p style="color: green;">✅ Colonnes image_width, image_height, image_processed_at ajoutées à carousel</p>';
    } else {
        echo '<p style="color: blue;">✓ Colonnes de traitement d\'image existent déjà dans carousel</p>';
    }

    // Migration 4: Ajouter les colonnes de réseaux sociaux à teams si manquantes
    $columns_sociaux = ['linkedin', 'twitter', 'facebook', 'instagram', 'website'];
    foreach ($columns_sociaux as $col) {
        $result = $pdo->query("SHOW COLUMNS FROM teams LIKE '{$col}'");
        if ($result->rowCount() === 0) {
            echo '<p>Ajout de la colonne <strong>' . $col . '</strong> à teams...</p>';
            $pdo->exec("ALTER TABLE teams ADD COLUMN {$col} VARCHAR(255) DEFAULT '' AFTER linkedin");
            echo '<p style="color: green;">✅ Colonne ' . $col . ' ajoutée</p>';
        }
    }

    // Migration 5: Corriger la structure de project_images
    $result = $pdo->query("SHOW COLUMNS FROM project_images LIKE 'sort_order'");
    if ($result->rowCount() > 0) {
        echo '<p>Renommage de <strong>sort_order</strong> en <strong>order_pos</strong> dans project_images...</p>';
        $pdo->exec("ALTER TABLE project_images CHANGE sort_order order_pos INT DEFAULT 0");
        echo '<p style="color: green;">✅ Colonne renommée</p>';
    }

    // Migration 6: Ajouter les colonnes manquantes à service_files
    $columns_service_files = ['file_name', 'file_type', 'sort_order'];
    $result = $pdo->query("SHOW COLUMNS FROM service_files");
    $existing_cols = [];
    while ($row = $result->fetch()) {
        $existing_cols[] = $row['Field'];
    }

    if (!in_array('file_name', $existing_cols)) {
        echo '<p>Ajout de <strong>file_name</strong> à service_files...</p>';
        $pdo->exec("ALTER TABLE service_files ADD COLUMN file_name VARCHAR(255) AFTER file_path");
        echo '<p style="color: green;">✅ Colonne file_name ajoutée</p>';
    }

    if (!in_array('file_type', $existing_cols)) {
        echo '<p>Ajout de <strong>file_type</strong> à service_files...</p>';
        $pdo->exec("ALTER TABLE service_files ADD COLUMN file_type VARCHAR(50) AFTER file_name");
        echo '<p style="color: green;">✅ Colonne file_type ajoutée</p>';
    }

    if (!in_array('sort_order', $existing_cols)) {
        echo '<p>Ajout de <strong>sort_order</strong> à service_files...</p>';
        $pdo->exec("ALTER TABLE service_files ADD COLUMN sort_order INT DEFAULT 0 AFTER file_type");
        echo '<p style="color: green;">✅ Colonne sort_order ajoutée</p>';
    }

    // Migration 7: Ajouter les colonnes de localisation à site_settings
    $result = $pdo->query("SHOW COLUMNS FROM site_settings LIKE 'latitude'");
    if ($result->rowCount() === 0) {
        echo '<p>Ajout des colonnes de localisation à <strong>site_settings</strong>...</p>';
        $pdo->exec("ALTER TABLE site_settings ADD COLUMN latitude VARCHAR(50) DEFAULT '4.0511' AFTER address");
        $pdo->exec("ALTER TABLE site_settings ADD COLUMN longitude VARCHAR(50) DEFAULT '9.7679' AFTER latitude");
        echo '<p style="color: green;">✅ Colonnes latitude et longitude ajoutées à site_settings</p>';
    } else {
        echo '<p style="color: blue;">✓ Colonnes de localisation existent déjà</p>';
    }

    // Migration 8: Créer la table newsletter
    $result = $pdo->query("SHOW TABLES LIKE 'newsletter_subscribers'");
    if ($result->rowCount() === 0) {
        echo '<p>Création de la table <strong>newsletter_subscribers</strong>...</p>';
        $pdo->exec("
            CREATE TABLE newsletter_subscribers (
                id INT PRIMARY KEY AUTO_INCREMENT,
                email VARCHAR(255) UNIQUE NOT NULL,
                name VARCHAR(100),
                status VARCHAR(50) DEFAULT 'active',
                subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
        echo '<p style="color: green;">✅ Table newsletter_subscribers créée</p>';
    } else {
        echo '<p style="color: blue;">✓ Table newsletter_subscribers existe déjà</p>';
    }

    // Migration 9: Ajouter les colonnes de traitement d'image à projects
    $result = $pdo->query("SHOW COLUMNS FROM projects LIKE 'image_width'");
    if ($result->rowCount() === 0) {
        echo '<p>Ajout des colonnes de traitement d\'image à <strong>projects</strong>...</p>';
        $pdo->exec("ALTER TABLE projects ADD COLUMN image_width INT DEFAULT 0 AFTER image");
        $pdo->exec("ALTER TABLE projects ADD COLUMN image_height INT DEFAULT 0 AFTER image_width");
        $pdo->exec("ALTER TABLE projects ADD COLUMN image_processed_at TIMESTAMP NULL AFTER image_height");
        echo '<p style="color: green;">✅ Colonnes image_width, image_height, image_processed_at ajoutées à projects</p>';
    } else {
        echo '<p style="color: blue;">✓ Colonnes de traitement d\'image existent déjà dans projects</p>';
    }

    // Migration 10: Ajouter la colonne status à contacts
    $result = $pdo->query("SHOW COLUMNS FROM contacts LIKE 'status'");
    if ($result->rowCount() === 0) {
        echo '<p>Ajout de la colonne <strong>status</strong> à contacts...</p>';
        $pdo->exec("ALTER TABLE contacts ADD COLUMN status VARCHAR(50) DEFAULT 'new' AFTER message");
        echo '<p style="color: green;">✅ Colonne status ajoutée à contacts</p>';
    } else {
        echo '<p style="color: blue;">✓ Colonne status existe déjà dans contacts</p>';
    }

    // Migration 11: Ajouter la colonne phone à contacts
    $result = $pdo->query("SHOW COLUMNS FROM contacts LIKE 'phone'");
    if ($result->rowCount() === 0) {
        echo '<p>Ajout de la colonne <strong>phone</strong> à contacts...</p>';
        $pdo->exec("ALTER TABLE contacts ADD COLUMN phone VARCHAR(20) AFTER email");
        echo '<p style="color: green;">✅ Colonne phone ajoutée à contacts</p>';
    } else {
        echo '<p style="color: blue;">✓ Colonne phone existe déjà dans contacts</p>';
    }

    // Migration 12: Ajouter la colonne subject à contacts
    $result = $pdo->query("SHOW COLUMNS FROM contacts LIKE 'subject'");
    if ($result->rowCount() === 0) {
        echo '<p>Ajout de la colonne <strong>subject</strong> à contacts...</p>';
        $pdo->exec("ALTER TABLE contacts ADD COLUMN subject VARCHAR(100) AFTER phone");
        echo '<p style="color: green;">✅ Colonne subject ajoutée à contacts</p>';
    } else {
        echo '<p style="color: blue;">✓ Colonne subject existe déjà dans contacts</p>';
    }

    echo '<hr>';
    echo '<p style="color: green; font-size: 18px;"><strong>✅ Migration terminée avec succès !</strong></p>';
    echo '<p><a href="index.php" style="padding: 10px 20px; background: #1a5490; color: white; text-decoration: none; border-radius: 5px;">Retour au site</a></p>';
    echo '</div>';

} catch (PDOException $e) {
    echo '<div style="font-family: Arial; margin: 50px; color: red;">';
    echo '<h1>❌ Erreur de migration</h1>';
    echo '<p><strong>Erreur:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><a href="migrate.php" style="padding: 10px 20px; background: #dc2626; color: white; text-decoration: none; border-radius: 5px;">Réessayer</a></p>';
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
