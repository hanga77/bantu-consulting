<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=admin-login');
    exit;
}
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h2><i class="fas fa-users"></i> Gestion des Utilisateurs</h2>
    <a href="?page=admin-dashboard&section=users&action=add" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Ajouter un Utilisateur
    </a>
</div>

<?php if (($_GET['action'] ?? '') === 'add'): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-gradient text-white">
        <h5 class="mb-0"><i class="fas fa-plus"></i> Nouvel Utilisateur</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="actions/save-user.php">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Nom d'utilisateur *</label>
                        <input type="text" class="form-control form-control-lg" id="username" name="username" required placeholder="Ex: jean.dupont">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email *</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email" required placeholder="Ex: jean@example.com">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Mot de passe *</label>
                <input type="password" class="form-control form-control-lg" id="password" name="password" required placeholder="Entrez un mot de passe sécurisé">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="?page=admin-dashboard&section=users" class="btn btn-secondary btn-lg">
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
        <h5 class="mb-0"><i class="fas fa-edit"></i> Modifier l'Utilisateur</h5>
    </div>
    <div class="card-body">
        <?php
        try {
            $user_id = intval($_GET['id']);
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            
            if (!$user) {
                echo '<div class="alert alert-warning">Utilisateur non trouvé</div>';
            } else {
        ?>
        <form method="POST" action="actions/save-user.php">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Nom d'utilisateur *</label>
                        <input type="text" class="form-control form-control-lg" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email *</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Nouveau mot de passe</label>
                <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Laisser vide pour ne pas changer">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="?page=admin-dashboard&section=users" class="btn btn-secondary btn-lg">
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
        <h5 class="mb-0">Liste des Utilisateurs</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Nom d'utilisateur</th>
                    <th>Email</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $users = $pdo->query("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC")->fetchAll();
                    if (!empty($users)):
                        foreach ($users as $u):
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($u['username']); ?></strong></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($u['created_at'])); ?></td>
                    <td>
                        <a href="?page=admin-dashboard&section=users&action=edit&id=<?php echo $u['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                        <a href="actions/delete-user.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression de cet utilisateur ?')">
                            <i class="fas fa-trash"></i>
                        </a>
                        
                        <?php else: ?>
                        <span class="badge bg-secondary">Votre compte</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="4" class="text-center text-muted py-3">Aucun utilisateur</td></tr>
                <?php endif; } catch (Exception $e) { echo '<tr><td colspan="4" class="alert alert-danger mb-0">' . htmlspecialchars($e->getMessage()) . '</td></tr>'; } ?>
            </tbody>
        </table>
    </div>
</div>
