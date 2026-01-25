<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=admin-login');
    exit;
}
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h2><i class="fas fa-briefcase"></i> Gestion des Projets</h2>
    <a href="?page=admin-dashboard&section=projects&action=add" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Ajouter un Projet
    </a>
</div>

<?php if (($_GET['action'] ?? '') === 'add'): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-gradient text-white">
        <h5 class="mb-0"><i class="fas fa-plus"></i> Nouveau Projet</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="actions/save-project.php" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Titre du Projet *</label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" required placeholder="Ex: Système de Gestion">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="client" class="form-label fw-bold">Client</label>
                        <input type="text" class="form-control form-control-lg" id="client" name="client" placeholder="Ex: Entreprise ABC">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Décrivez le projet..."></textarea>
            </div>

            <div class="mb-3">
                <label for="short_description" class="form-label fw-bold">Description courte</label>
                <input type="text" class="form-control" id="short_description" name="short_description" placeholder="Résumé court du projet">
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Statut</label>
                        <select class="form-control form-control-lg" id="status" name="status">
                            <option value="en cours">En cours</option>
                            <option value="terminé">Terminé</option>
                            <option value="en attente">En attente</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="start_date" class="form-label fw-bold">Date de début</label>
                        <input type="date" class="form-control form-control-lg" id="start_date" name="start_date">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="end_date" class="form-label fw-bold">Date de fin</label>
                        <input type="date" class="form-control form-control-lg" id="end_date" name="end_date">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="budget" class="form-label fw-bold">Budget</label>
                        <input type="number" step="0.01" class="form-control form-control-lg" id="budget" name="budget" placeholder="0.00">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Image du projet</label>
                        <input type="file" class="form-control form-control-lg" id="image" name="image" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="?page=admin-dashboard&section=projects" class="btn btn-secondary btn-lg">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if (($_GET['action'] ?? '') === 'edit' && isset($_GET['id'])): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-gradient text-white">
        <h5 class="mb-0"><i class="fas fa-edit"></i> Modifier le Projet</h5>
    </div>
    <div class="card-body">
        <?php
        try {
            $proj_id = intval($_GET['id']);
            $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
            $stmt->execute([$proj_id]);
            $project = $stmt->fetch();
            
            if (!$project) {
                echo '<div class="alert alert-warning">Projet non trouvé</div>';
            } else {
        ?>
        <form method="POST" action="actions/save-project.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Titre du Projet *</label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="client" class="form-label fw-bold">Client</label>
                        <input type="text" class="form-control form-control-lg" id="client" name="client" value="<?php echo htmlspecialchars($project['client'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($project['description']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="short_description" class="form-label fw-bold">Description courte</label>
                <input type="text" class="form-control" id="short_description" name="short_description" value="<?php echo htmlspecialchars($project['short_description'] ?? ''); ?>">
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Statut</label>
                        <select class="form-control form-control-lg" id="status" name="status">
                            <option value="en cours" <?php echo $project['status'] === 'en cours' ? 'selected' : ''; ?>>En cours</option>
                            <option value="terminé" <?php echo $project['status'] === 'terminé' ? 'selected' : ''; ?>>Terminé</option>
                            <option value="en attente" <?php echo $project['status'] === 'en attente' ? 'selected' : ''; ?>>En attente</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="start_date" class="form-label fw-bold">Date de début</label>
                        <input type="date" class="form-control form-control-lg" id="start_date" name="start_date" value="<?php echo htmlspecialchars($project['start_date'] ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="end_date" class="form-label fw-bold">Date de fin</label>
                        <input type="date" class="form-control form-control-lg" id="end_date" name="end_date" value="<?php echo htmlspecialchars($project['end_date'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="budget" class="form-label fw-bold">Budget</label>
                        <input type="number" step="0.01" class="form-control form-control-lg" id="budget" name="budget" value="<?php echo htmlspecialchars($project['budget'] ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Image du projet</label>
                        <input type="file" class="form-control form-control-lg" id="image" name="image" accept="image/*">
                        <?php if ($project['image']): ?>
                        <small class="text-muted">Image actuelle: <?php echo htmlspecialchars($project['image']); ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="?page=admin-dashboard&section=projects" class="btn btn-secondary btn-lg">
                    Annuler
                </a>
            </div>
        </form>
        <?php }} catch (Exception $e) { echo '<div class="alert alert-danger">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>'; } ?>
    </div>
</div>
<?php endif; ?>

<div class="card border-0 shadow">
    <div class="card-header bg-light">
        <h5 class="mb-0">Liste des Projets</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Titre</th>
                    <th>Client</th>
                    <th>Statut</th>
                    <th>Dates</th>
                    <th>Budget</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
                    if (!empty($projects)):
                        foreach ($projects as $p):
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($p['title']); ?></strong></td>
                    <td><?php echo htmlspecialchars($p['client'] ?? '-'); ?></td>
                    <td><span class="badge bg-<?php echo $p['status'] === 'terminé' ? 'success' : ($p['status'] === 'en cours' ? 'primary' : 'warning'); ?>"><?php echo ucfirst($p['status']); ?></span></td>
                    <td><?php echo $p['start_date'] ? date('d/m/Y', strtotime($p['start_date'])) : '-'; ?> → <?php echo $p['end_date'] ? date('d/m/Y', strtotime($p['end_date'])) : '-'; ?></td>
                    <td><?php echo isset($p['budget']) && $p['budget'] !== null ? number_format((float)$p['budget'], 2) . ' FCAF'     : '-'; ?></td>
                    <td>
                        <a href="?page=admin-dashboard&section=projects&action=edit&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="actions/delete-project.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center text-muted py-3">Aucun projet</td></tr>
                <?php endif; } catch (Exception $e) { echo '<tr><td colspan="6" class="alert alert-danger mb-0">' . htmlspecialchars($e->getMessage()) . '</td></tr>'; } ?>
            </tbody>
        </table>
    </div>
</div>