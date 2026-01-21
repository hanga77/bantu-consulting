<?php
/**
 * Script d'initialisation - Crée les dossiers et la base de données
 * Accédez à http://localhost/Bantu-test2/setup.php
 */

$base_dir = __DIR__;
$folders = [
    'uploads',
    'pages',
    'templates',
    'admin',
    'actions',
    'config',
    'assets'
];

echo "<h2>Initialisation du projet Bantu Consulting</h2>";
echo "<hr>";

// Création des dossiers
echo "<h3>Création des dossiers...</h3>";
foreach ($folders as $folder) {
    $path = $base_dir . '/' . $folder;
    if (!is_dir($path)) {
        if (mkdir($path, 0755, true)) {
            echo "✅ Dossier créé : <strong>$folder/</strong><br>";
        } else {
            echo "❌ Erreur lors de la création de : <strong>$folder/</strong><br>";
        }
    } else {
        echo "✓ Dossier existe déjà : <strong>$folder/</strong><br>";
    }
}

echo "<hr>";

// Création de la base de données
echo "<h3>Création de la base de données...</h3>";

require_once 'config/database.php';

$sql = <<<SQL
-- Créer la base de données
CREATE DATABASE IF NOT EXISTS bantu_consulting;
USE bantu_consulting;

-- Table des utilisateurs admin
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des services
CREATE TABLE IF NOT EXISTS services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des projets
CREATE TABLE IF NOT EXISTS projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    short_description VARCHAR(255),
    image VARCHAR(255),
    status VARCHAR(50),
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des membres de projet
CREATE TABLE IF NOT EXISTS project_members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    member_name VARCHAR(100),
    role VARCHAR(100),
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);

-- Table des départements/pôles
CREATE TABLE IF NOT EXISTS departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    department_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des équipes
CREATE TABLE IF NOT EXISTS teams (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100),
    role VARCHAR(255),
    importance VARCHAR(255),
    image VARCHAR(255),
    department_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- Table des contacts/formulaires
CREATE TABLE IF NOT EXISTS contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table carrousel
CREATE TABLE IF NOT EXISTS carousel (
    id INT PRIMARY KEY AUTO_INCREMENT,
    image VARCHAR(255),
    title VARCHAR(100),
    description TEXT,
    order_pos INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table à propos
CREATE TABLE IF NOT EXISTS about (
    id INT PRIMARY KEY AUTO_INCREMENT,
    motto TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
SQL;

try {
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    echo "✅ Base de données créée avec succès<br>";
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Création des données par défaut
echo "<h3>Création des données par défaut...</h3>";

try {
    // Vérifier si l'admin existe
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $password = password_hash('admin123', PASSWORD_BCRYPT);
        $insert = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $insert->execute(['admin', $password, 'admin@bantu-consulting.com']);
        echo "✅ Compte admin créé<br>";
        echo "&nbsp;&nbsp;&nbsp;Login : <strong>admin</strong><br>";
        echo "&nbsp;&nbsp;&nbsp;Mot de passe : <strong>admin123</strong><br>";
    } else {
        echo "✓ Compte admin existe déjà<br>";
    }
    
    // Créer les départements par défaut
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM departments");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $departments = [
            ['Pôle LBC/FT', 'Logistique, Banque et Commerce / Finance Trésorerie'],
            ['Pôle DCA/DID/DIDH', 'Direction des Contrats et Assurances / Développement International'],
            ['Département RH', 'Ressources Humaines'],
            ['Département GCTD', 'Gestion, Contrôle et Technologies Digitales']
        ];
        
        $insert = $pdo->prepare("INSERT INTO departments (name, description) VALUES (?, ?)");
        foreach ($departments as $dept) {
            $insert->execute($dept);
        }
        echo "✅ Départements créés<br>";
    } else {
        echo "✓ Départements existent déjà<br>";
    }
    
    // Créer la devise par défaut
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM about");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $insert = $pdo->prepare("INSERT INTO about (motto, description) VALUES (?, ?)");
        $insert->execute([
            'Votre succès est notre mission',
            'Bantu Consulting est une entreprise de conseil spécialisée dans le développement stratégique et opérationnel des organisations.'
        ]);
        echo "✅ Informations À Propos créées<br>";
    } else {
        echo "✓ Informations À Propos existent déjà<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin-top: 20px;'>";
echo "<h4>✅ Initialisation terminée !</h4>";
echo "<p><strong>Accédez au site :</strong> <a href='index.php'>http://localhost/Bantu-test2/</a></p>";
echo "<p><strong>Accédez à l'admin :</strong> <a href='?page=admin-login'>http://localhost/Bantu-test2/?page=admin-login</a></p>";
echo "<p style='color: #155724; margin-top: 10px;'>Identifiants : <strong>admin</strong> / <strong>admin123</strong></p>";
echo "</div>";
?>
