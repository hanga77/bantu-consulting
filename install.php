<?php
/**
 * Script d'installation - Initialise la base de données
 */

require_once 'config/database.php';

echo "<h2>Installation Bantu Consulting</h2>";
echo "<hr>";

// Créer la base de données
echo "<h3>1. Création de la base de données...</h3>";

try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS bantu_consulting");
    $pdo->exec("USE bantu_consulting");
    echo "✅ Base de données créée<br>";
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
    exit;
}

echo "<hr>";

// Exécuter les migrations
echo "<h3>2. Création des tables...</h3>";

require_once 'migrate.php';
runMigrations($pdo);

echo "<hr>";

// Insérer les données par défaut
echo "<h3>3. Création des données par défaut...</h3>";

try {
    // Admin
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $password = password_hash('admin123', PASSWORD_BCRYPT);
        $insert = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $insert->execute(['admin', $password, 'admin@bantu-consulting.com']);
        echo "✅ Admin créé (admin / admin123)<br>";
    } else {
        echo "✓ Admin existe déjà<br>";
    }
    
    // Services par défaut
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM services");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $services = [
            [
                'Consulting Stratégique',
                'Nous aidons les organisations à définir et mettre en œuvre leurs stratégies de développement.',
                'consulting@bantu.com',
                '+243 XXX XXX XXX',
                'https://bantu-consulting.com'
            ],
            [
                'Gestion de Projets',
                'Accompagnement dans la gestion et le suivi de vos projets complexes.',
                'projets@bantu.com',
                '+243 XXX XXX XXX',
                'https://bantu-consulting.com'
            ],
            [
                'Développement RH',
                'Solutions complètes pour le développement des ressources humaines.',
                'rh@bantu.com',
                '+243 XXX XXX XXX',
                'https://bantu-consulting.com'
            ]
        ];
        
        $insert = $pdo->prepare("INSERT INTO services (title, description, contact_email, contact_phone, website) VALUES (?, ?, ?, ?, ?)");
        foreach ($services as $service) {
            $insert->execute($service);
        }
        echo "✅ Services créés<br>";
    } else {
        echo "✓ Services existent déjà<br>";
    }
    
    // Départements
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM departments");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $departments = [
            ['Pôle LBC/FT', 'Logistique, Banque et Commerce / Finance Trésorerie', 'pole'],
            ['Pôle DCA/DID/DIDH', 'Direction des Contrats et Assurances / Développement International', 'pole'],
            ['Département RH', 'Ressources Humaines', 'department'],
            ['Département GCTD', 'Gestion, Contrôle et Technologies Digitales', 'department']
        ];
        
        $insert = $pdo->prepare("INSERT INTO departments (name, description, department_type) VALUES (?, ?, ?)");
        foreach ($departments as $dept) {
            $insert->execute($dept);
        }
        echo "✅ Départements créés<br>";
    } else {
        echo "✓ Départements existent déjà<br>";
    }
    
    // À Propos
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM about");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $insert = $pdo->prepare("INSERT INTO about (motto, description) VALUES (?, ?)");
        $insert->execute([
            'Votre succès est notre mission',
            'Bantu Consulting est une entreprise de conseil spécialisée dans le développement stratégique et opérationnel des organisations.'
        ]);
        echo "✅ Informations créées<br>";
    } else {
        echo "✓ Informations existent déjà<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin-top: 20px;'>";
echo "<h4>✅ Installation terminée !</h4>";
echo "<p><a href='index.php'>Retour au site</a> | <a href='?page=admin-login'>Admin</a></p>";
echo "</div>";
?>
