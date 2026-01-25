<h2>Gestion du Carrousel</h2>
<a href="?page=admin-dashboard&section=carousel&action=add" class="btn btn-primary mb-3">+ Ajouter une Image</a>

<?php if (($_GET['action'] ?? '') === 'add'): ?>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Nouvelle Image Carrousel</h5>
        <form method="POST" action="actions/save-carousel.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label for="order_pos" class="form-label">Position</label>
                <input type="number" class="form-control" id="order_pos" name="order_pos" value="0">
            </div>
            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="?page=admin-dashboard&section=carousel" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
<?php endif; ?>

<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>Titre</th>
            <th>Position</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $carousel = $pdo->query("SELECT * FROM carousel ORDER BY order_pos ASC")->fetchAll();
        if (!empty($carousel)):
            foreach ($carousel as $item):
        ?>
        <tr>
            <td><?php echo htmlspecialchars($item['title']); ?></td>
            <td><?php echo $item['order_pos']; ?></td>
            <td>
                <a href="actions/delete-carousel.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="3" class="text-center text-muted">Aucune image</td></tr>
        <?php endif; ?>
    </tbody>
</table>
