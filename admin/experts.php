<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=admin-login');
    exit;
}
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h2><i class="fas fa-user-tie"></i> Gestion des Experts</h2>
    <a href="?page=admin-dashboard&section=experts&action=add" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Ajouter un Expert
    </a>
</div>

<?php if (($_GET['action'] ?? '') === 'add'): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-gradient text-white">
        <h5 class="mb-0"><i class="fas fa-plus"></i> Nouvel Expert</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="actions/save-expert.php" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nom complet *</label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name" required placeholder="Ex: Jean Dupont">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="specialty" class="form-label fw-bold">Spécialité *</label>
                        <input type="text" class="form-control form-control-lg" id="specialty" name="specialty" required placeholder="Ex: Consultant Stratégie">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Bio et expériences..."></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="expert@example.com">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-bold">Téléphone</label>
                        <input type="text" class="form-control form-control-lg" id="phone" name="phone" placeholder="+33 6 XX XX XX XX">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label fw-bold">Photo de profil</label>
                <input type="file" class="form-control form-control-lg" id="image" name="image" accept="image/*">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="?page=admin-dashboard&section=experts" class="btn btn-secondary btn-lg">
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
        <h5 class="mb-0"><i class="fas fa-edit"></i> Modifier l'Expert</h5>
    </div>
    <div class="card-body">
        <?php
        try {
            $exp_id = intval($_GET['id']);
            $stmt = $pdo->prepare("SELECT * FROM experts WHERE id = ?");
            $stmt->execute([$exp_id]);
            $expert = $stmt->fetch();
            
            if (!$expert) {
                echo '<div class="alert alert-warning">Expert non trouvé</div>';
            } else {
        ?>
        <form method="POST" action="actions/save-expert.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $expert['id']; ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nom complet *</label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name" value="<?php echo htmlspecialchars($expert['name']); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="specialty" class="form-label fw-bold">Spécialité *</label>
                        <input type="text" class="form-control form-control-lg" id="specialty" name="specialty" value="<?php echo htmlspecialchars($expert['specialty']); ?>" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($expert['description'] ?? ''); ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email" value="<?php echo htmlspecialchars($expert['email'] ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-bold">Téléphone</label>
                        <input type="text" class="form-control form-control-lg" id="phone" name="phone" value="<?php echo htmlspecialchars($expert['phone'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label fw-bold">Photo de profil</label>
                <input type="file" class="form-control form-control-lg" id="image" name="image" accept="image/*">
                <?php if ($expert['image']): ?>
                <small class="text-muted">Photo actuelle: <?php echo htmlspecialchars($expert['image']); ?></small>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="?page=admin-dashboard&section=experts" class="btn btn-secondary btn-lg">
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
        <h5 class="mb-0">Liste des Experts</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Nom</th>
                    <th>Spécialité</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $experts = $pdo->query("SELECT * FROM experts ORDER BY name")->fetchAll();
                    if (!empty($experts)):
                        foreach ($experts as $e):
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($e['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($e['specialty'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($e['email'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($e['phone'] ?? '-'); ?></td>
                    <td>
                        <a href="?page=admin-dashboard&section=experts&action=edit&id=<?php echo $e['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="actions/delete-expert.php?id=<?php echo $e['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center text-muted py-3">Aucun expert</td></tr>
                <?php endif; } catch (Exception $e) { echo '<tr><td colspan="5" class="alert alert-danger mb-0">' . htmlspecialchars($e->getMessage()) . '</td></tr>'; } ?>
            </tbody>
        </table>
    </div>
</div>
