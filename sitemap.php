<?php
header('Content-Type: application/xml; charset=UTF-8');
require_once 'config/database.php';

$base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/Bantu-test2/';

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Pages statiques
$static_pages = [
    ['loc' => '?page=accueil', 'priority' => '1.0', 'changefreq' => 'weekly'],
    ['loc' => '?page=projets', 'priority' => '0.9', 'changefreq' => 'weekly'],
    ['loc' => '?page=equipes', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => '?page=services', 'priority' => '0.9', 'changefreq' => 'monthly'],
    ['loc' => '?page=apropos', 'priority' => '0.7', 'changefreq' => 'monthly'],
];

foreach ($static_pages as $page) {
    echo '<url>';
    echo '<loc>' . htmlspecialchars($base_url . $page['loc']) . '</loc>';
    echo '<priority>' . $page['priority'] . '</priority>';
    echo '<changefreq>' . $page['changefreq'] . '</changefreq>';
    echo '<lastmod>' . date('Y-m-d') . '</lastmod>';
    echo '</url>';
}

// Projets dynamiques
try {
    $projects = $pdo->query("SELECT id, updated_at FROM projects ORDER BY id DESC")->fetchAll();
    foreach ($projects as $project) {
        echo '<url>';
        echo '<loc>' . htmlspecialchars($base_url . '?page=projet-detail&id=' . $project['id']) . '</loc>';
        echo '<lastmod>' . date('Y-m-d', strtotime($project['updated_at'])) . '</lastmod>';
        echo '<priority>0.8</priority>';
        echo '<changefreq>monthly</changefreq>';
        echo '</url>';
    }
} catch (Exception $e) {}

// Départements dynamiques
try {
    $depts = $pdo->query("SELECT id FROM departments ORDER BY id DESC")->fetchAll();
    foreach ($depts as $dept) {
        echo '<url>';
        echo '<loc>' . htmlspecialchars($base_url . '?page=departement-detail&id=' . $dept['id']) . '</loc>';
        echo '<priority>0.7</priority>';
        echo '<changefreq>monthly</changefreq>';
        echo '<lastmod>' . date('Y-m-d') . '</lastmod>';
        echo '</url>';
    }
} catch (Exception $e) {}

echo '</urlset>';
?>
