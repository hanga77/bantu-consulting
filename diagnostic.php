<?php
/**
 * DIAGNOSTIC DE LA BASE DE DONNÉES
 * Affiche l'état de toutes les tables et données
 */

require_once 'config/database.php';

// Vérifier la connexion
echo "<h1>📊 Diagnostic de la Base de Données</h1>";
echo "<p style='color: green; font-weight: bold;'>✓ Connexion à la base de données réussie !</p>";

echo "<hr>";

// Fonction pour afficher une table
function displayTableInfo($pdo, $tableName, $displayFields = ['id', 'name', 'title', 'description']) {
    echo "<h3>Table: <code>$tableName</code></h3>";
    
    try {
        // Compter les lignes
        $countStmt = $pdo->query("SELECT COUNT(*) as count FROM $tableName");
        $count = $countStmt->fetch()['count'];
        
        echo "<p><strong>Nombre d'enregistrements:</strong> <span style='color: #0066cc; font-size: 1.3em; font-weight: bold;'>$count</span></p>";
        
        if ($count > 0) {
            echo "<details>";
            echo "<summary>👁️ Voir les détails</summary>";
            echo "<table border='1' cellpadding='10' style='width: 100%; margin-top: 10px;'>";
            echo "<thead style='background-color: #f0f0f0;'>";
            
            // Récupérer les noms des colonnes
            $stmt = $pdo->query("DESCRIBE $tableName");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($columns as $col) {
                echo "<th>$col</th>";
            }
            echo "</thead>";
            echo "<tbody>";
            
            // Afficher les données
            $data = $pdo->query("SELECT * FROM $tableName LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($data as $row) {
                echo "<tr>";
                foreach ($columns as $col) {
                    $value = $row[$col] ?? '-';
                    if (is_string($value) && strlen($value) > 50) {
                        $value = substr($value, 0, 50) . '...';
                    }
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
            echo "</details>";
        } else {
            echo "<p style='color: #ff6600;'><strong>⚠️ Cette table est vide !</strong></p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'><strong>❌ Erreur:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    echo "<hr>";
}

// Vérifier toutes les tables principales
$tables = [
    'about' => 'Table de présentation',
    'departments' => 'Départements/Pôles',
    'teams' => 'Membres/Équipes',
    'services' => 'Services offerts',
    'service_files' => 'Fichiers PDF des services',
    'projects' => 'Projets',
    'contacts' => 'Formulaires de contact',
    'carousel' => 'Images du carrousel',
];

foreach ($tables as $table => $description) {
    echo "<div style='background-color: #f9f9f9; padding: 10px; margin: 10px 0; border-left: 4px solid #0066cc;'>";
    echo "<p><strong>$description</strong></p>";
    displayTableInfo($pdo, $table);
    echo "</div>";
}

// Résumé
echo "<h2>📈 Résumé</h2>";
echo "<ul>";
echo "<li><strong>About:</strong> Remplir pour la page À Propos</li>";
echo "<li><strong>Departments:</strong> Créer au moins 1 département</li>";
echo "<li><strong>Teams:</strong> Ajouter au moins 1 membre</li>";
echo "<li><strong>Services:</strong> Créer au least 1 service</li>";
echo "</ul>";

echo "<p style='color: green;'>✅ Utilisez ce diagnostic pour vérifier que votre base est correctement remplie !</p>";
?>
