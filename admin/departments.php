<div class="d-flex align-items-center justify-content-between mb-4">
    <h2><i class="fas fa-sitemap"></i> Gestion des Départements/Pôles</h2>
    <a href="?page=admin-dashboard&section=departments&action=add" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Ajouter un Département
    </a>
</div>

<?php if (($_GET['action'] ?? '') === 'add'): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-gradient text-white">
        <h5 class="mb-0"><i class="fas fa-plus"></i> Nouveau Département</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="actions/save-department.php">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nom du Département *</label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name" required placeholder="Ex: Pôle Informatique">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="department_type" class="form-label fw-bold">Type de Département</label>
                        <input type="text" class="form-control form-control-lg" id="department_type" name="department_type" placeholder="Ex: Technique, Conseil, Support">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Décrivez l'objectif et les fonctions de ce département..."></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="?page=admin-dashboard&section=departments" class="btn btn-secondary btn-lg">
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
        <h5 class="mb-0"><i class="fas fa-edit"></i> Modifier le Département</h5>
    </div>
    <div class="card-body">
        <?php
        try {
            $dept_id = intval($_GET['id']);
            $stmt = $pdo->prepare("SELECT * FROM departments WHERE id = ?");
            $stmt->execute([$dept_id]);
            $dept = $stmt->fetch();
            
            if (!$dept) {
                echo '<div class="alert alert-warning">Département non trouvé</div>';
            } else {
        ?>
        <form method="POST" action="actions/save-department.php">
            <input type="hidden" name="id" value="<?php echo $dept['id']; ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nom du Département *</label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name" value="<?php echo htmlspecialchars($dept['name']); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="department_type" class="form-label fw-bold">Type de Département</label>
                        <input type="text" class="form-control form-control-lg" id="department_type" name="department_type" value="<?php echo htmlspecialchars($dept['department_type'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($dept['description'] ?? ''); ?></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="?page=admin-dashboard&section=departments" class="btn btn-secondary btn-lg">
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
        <h5 class="mb-0">Liste des Départements</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Membres</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $depts = $pdo->query("SELECT d.*, COUNT(t.id) as member_count FROM departments d LEFT JOIN teams t ON d.id = t.department_id GROUP BY d.id ORDER BY d.name")->fetchAll();
                    if (!empty($depts)):
                        foreach ($depts as $dept):
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($dept['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($dept['department_type'] ?? '-'); ?></td>
                    <td><?php echo substr(htmlspecialchars($dept['description'] ?? ''), 0, 50) . (strlen($dept['description'] ?? '') > 50 ? '...' : ''); ?></td>
                    <td><span class="badge bg-info"><?php echo $dept['member_count']; ?></span></td>
                    <td>
                        <a href="?page=admin-dashboard&section=departments&action=edit&id=<?php echo $dept['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="actions/delete-department.php?id=<?php echo $dept['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ? Les membres seront conservés sans département.')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center text-muted py-3">Aucun département</td></tr>
                <?php endif; } catch (Exception $e) { echo '<tr><td colspan="5" class="alert alert-danger mb-0">' . htmlspecialchars($e->getMessage()) . '</td></tr>'; } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Section Membres sans Département -->
<div class="card border-0 shadow mt-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-user-slash"></i> Membres sans Département</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Nom</th>
                    <th>Poste</th>
                    <th>Responsabilité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $teams_no_dept = $pdo->query("SELECT * FROM teams WHERE department_id IS NULL OR department_id = 0 ORDER BY name")->fetchAll();
                    if (!empty($teams_no_dept)):
                        foreach ($teams_no_dept as $team):
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($team['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($team['position']); ?></td>
                    <td><?php echo htmlspecialchars($team['importance'] ?? 'N/A'); ?></td>
                    <td>
                        <a href="?page=admin-dashboard&section=teams&action=edit&id=<?php echo $team['id']; ?>" class="btn btn-sm btn-info" title="Modifier et assigner un département">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="4" class="text-center text-muted py-3">Tous les membres sont assignés à un département</td></tr>
                <?php endif; } catch (Exception $e) {} ?>
            </tbody>
        </table>
    </div>
</div>
