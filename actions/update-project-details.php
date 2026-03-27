<?php
session_start();
require_once '../config/database.php';
require_once '../includes/image-processing.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Token de sécurité invalide.';
    header('Location: ../?page=admin-dashboard&section=projects');
    exit;
}

$project_id = intval($_POST['project_id'] ?? 0);
$tab = $_POST['tab'] ?? 'info';

if ($project_id <= 0) {
    $_SESSION['error'] = 'ID projet invalide';
    header('Location: ../?page=admin-dashboard&section=projects');
    exit;
}

try {
    if ($tab === 'info') {
        // Mise à jour des informations
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $short_description = trim($_POST['short_description'] ?? '');
        $status = trim($_POST['status'] ?? 'en cours');
        $client = trim($_POST['client'] ?? '');
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;
        $budget = !empty($_POST['budget']) ? floatval($_POST['budget']) : null;

        if (empty($title) || empty($description)) {
            throw new Exception('Titre et description sont obligatoires');
        }

        $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, short_description = ?, status = ?, client = ?, start_date = ?, end_date = ?, budget = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$title, $description, $short_description, $status, $client, $start_date, $end_date, $budget, $project_id]);

        $_SESSION['success'] = 'Informations mises à jour !';
        
    } elseif ($tab === 'gallery') {
        // Ajouter des images à la galerie
        if (!empty($_FILES['gallery_images']['name'][0])) {
            // Récupérer le nombre d'images existantes
            $stmt = $pdo->query("SELECT MAX(order_pos) as max_pos FROM project_images WHERE project_id = $project_id");
            $result = $stmt->fetch();
            $order = intval($result['max_pos']) + 1;

            for ($i = 0; $i < count($_FILES['gallery_images']['name']); $i++) {
                if (!empty($_FILES['gallery_images']['name'][$i])) {
                    try {
                        $file = [
                            'name' => $_FILES['gallery_images']['name'][$i],
                            'type' => $_FILES['gallery_images']['type'][$i],
                            'tmp_name' => $_FILES['gallery_images']['tmp_name'][$i],
                            'error' => $_FILES['gallery_images']['error'][$i],
                            'size' => $_FILES['gallery_images']['size'][$i]
                        ];

                        $result = processAndSaveImage($file, 'gallery', 1200, 800, 85);
                        
                        $stmt = $pdo->prepare("INSERT INTO project_images (project_id, image, order_pos) VALUES (?, ?, ?)");
                        $stmt->execute([$project_id, $result['filename'], $order]);
                        $order++;
                    } catch (Exception $e) {
                        error_log("Erreur galerie: " . $e->getMessage());
                    }
                }
            }
            $_SESSION['success'] = 'Images ajoutées avec succès !';
        } else {
            throw new Exception('Aucune image sélectionnée');
        }
    }

} catch (Exception $e) {
    $_SESSION['error'] = safeErrorMessage($e);
}

header("Location: ../?page=admin-dashboard&section=project-details&id=$project_id");
exit;
?>
