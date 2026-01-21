<h2>Gestion des Services</h2>
<a href="?page=admin-dashboard&section=services&action=add" class="btn btn-primary mb-3">+ Ajouter un Service</a>

<?php if (($_GET['action'] ?? '') === 'add'): ?>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Nouveau Service</h5>
        <form method="POST" action="actions/save-service.php">
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="?page=admin-dashboard&section=services" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
<?php endif; ?>

<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>Titre</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $services = $pdo->query("SELECT * FROM services ORDER BY created_at DESC")->fetchAll();
        if (!empty($services)):
            foreach ($services as $service):
        ?>
        <tr>
            <td><?php echo htmlspecialchars($service['title']); ?></td>
            <td>
                <a href="actions/delete-service.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="2" class="text-center text-muted">Aucun service</td></tr>
        <?php endif; ?>
    </tbody>
</table>
