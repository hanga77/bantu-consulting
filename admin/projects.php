<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=admin-login');
    exit;
}

require_once 'config/database.php';

$project = null;
$is_edit = false;

// Charger le projet si en édition
if (isset($_GET['edit'])) {
    $project = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $project->execute([intval($_GET['edit'])]);
    $project = $project->fetch();
    $is_edit = !empty($project);
}

$projects = $pdo->query("SELECT id, title, created_at FROM projects ORDER BY created_at DESC")->fetchAll();
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8">
            <h2><?php echo $is_edit ? 'Modifier le projet' : 'Ajouter un projet'; ?></h2>
            
            <form action="actions/save-project.php" method="POST" enctype="multipart/form-data" class="card p-4">
                <?php if ($is_edit): ?>
                <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                <?php endif; ?>
                
                <div class="mb-3">
                    <label class="form-label">Titre *</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($project['title'] ?? ''); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Description courte</label>
                    <input type="text" name="short_description" class="form-control" value="<?php echo htmlspecialchars($project['short_description'] ?? ''); ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Description complète *</label>
                    <textarea name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($project['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Client</label>
                        <input type="text" name="client" class="form-control" value="<?php echo htmlspecialchars($project['client'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-control">
                            <option value="en cours" <?php echo ($project['status'] ?? '') === 'en cours' ? 'selected' : ''; ?>>En cours</option>
                            <option value="terminé" <?php echo ($project['status'] ?? '') === 'terminé' ? 'selected' : ''; ?>>Terminé</option>
                            <option value="en attente" <?php echo ($project['status'] ?? '') === 'en attente' ? 'selected' : ''; ?>>En attente</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de début</label>
                        <input type="date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($project['start_date'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de fin</label>
                        <input type="date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($project['end_date'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Budget</label>
                    <input type="number" step="0.01" name="budget" class="form-control" value="<?php echo htmlspecialchars($project['budget'] ?? ''); ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <?php if ($is_edit && $project['image']): ?>
                    <small class="text-muted">Image actuelle: <?php echo htmlspecialchars($project['image']); ?></small>
                    <?php endif; ?>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="?page=admin-dashboard&section=projects" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
        
        <div class="col-md-4">
            <h3>Projets existants</h3>
            <div class="list-group">
                <?php foreach ($projects as $p): ?>
                <a href="?page=admin-projects&edit=<?php echo $p['id']; ?>" class="list-group-item list-group-item-action">
                    <strong><?php echo htmlspecialchars($p['title']); ?></strong>
                    <br><small class="text-muted"><?php echo date('d/m/Y', strtotime($p['created_at'])); ?></small>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>