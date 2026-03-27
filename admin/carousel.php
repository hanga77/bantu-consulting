<h2><i class="fas fa-image"></i> Gestion du Carrousel</h2>
<a href="?page=admin-dashboard&section=carousel&action=add" class="btn btn-primary mb-3"><i class="fas fa-plus-circle"></i> Ajouter une Image</a>

<?php if (($_GET['action'] ?? '') === 'add'): ?>
<div class="card mb-4 border-0 shadow-lg">
    <div class="card-header bg-gradient text-white">
        <h5 class="mb-0"><i class="fas fa-upload"></i> Nouvelle Image Carrousel</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="actions/save-carousel.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Titre de la diapo</label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" placeholder="Ex: Nos Services">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Texte affiché sur l'image..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="order_pos" class="form-label fw-bold">Ordre d'affichage</label>
                        <input type="number" class="form-control" id="order_pos" name="order_pos" value="0" min="0">
                        <small class="text-muted">0 = première position</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control-lg" id="image" name="image" accept="image/*" required>
                        <small class="text-muted d-block mt-2">
                            Format: JPG, PNG | Taille max: 5MB<br>
                            <strong>Dimensions recommandées: 1920x1080px (16:9)</strong>
                        </small>
                    </div>

                    <!-- Aperçu d'image -->
                    <div id="image-preview" class="mt-3" style="display: none;">
                        <label class="form-label fw-bold">Aperçu:</label>
                        <div style="max-height: 250px; overflow: hidden; border-radius: 8px; border: 2px solid #1e40af;">
                            <img id="preview-img" src="" alt="Aperçu" style="width: 100%; height: auto; display: block;">
                        </div>
                        <small class="text-muted d-block mt-2" id="image-info"></small>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <i class="fas fa-lightbulb"></i> 
                <strong>Conseil:</strong> Les images du carrousel seront automatiquement optimisées en 1920x1080px pour une meilleure performance.
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="?page=admin-dashboard&section=carousel" class="btn btn-secondary btn-lg">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Aperçu d'image en temps réel
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = document.getElementById('preview-img');
            img.src = event.target.result;
            
            // Obtenir les dimensions réelles
            const image = new Image();
            image.onload = function() {
                const info = document.getElementById('image-info');
                info.innerHTML = `<i class="fas fa-info-circle"></i> Dimensions: ${image.width}x${image.height}px | Taille: ${(file.size / 1024 / 1024).toFixed(2)}MB`;
            };
            image.src = event.target.result;
            
            document.getElementById('image-preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('image-preview').style.display = 'none';
    }
});
</script>
<?php endif; ?>

<!-- Liste des images -->
<div class="card border-0 shadow-lg">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> Images du Carrousel</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th style="width: 150px;">Aperçu</th>
                    <th>Titre</th>
                    <th>Ordre</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $carousel = $pdo->query("SELECT * FROM carousel ORDER BY order_pos ASC")->fetchAll();
                    if (!empty($carousel)):
                        foreach ($carousel as $item):
                ?>
                <tr>
                    <td>
                        <?php if (!empty($item['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="Aperçu" 
                             style="max-width: 140px; max-height: 80px; border-radius: 4px;">
                        <?php else: ?>
                        <span class="text-muted"><i class="fas fa-image"></i> Pas d'image</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($item['title'] ?? 'Sans titre'); ?></strong><br>
                        <small class="text-muted"><?php echo htmlspecialchars(substr($item['description'] ?? '', 0, 50)); ?></small>
                    </td>
                    <td><span class="badge bg-primary"><?php echo $item['order_pos']; ?></span></td>
                    <td>
                        <form method="POST" action="actions/delete-carousel.php" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="4" class="text-center text-muted py-4"><i class="fas fa-inbox"></i> Aucune image</td></tr>
                <?php endif; } catch (Exception $e) {
                    echo '<tr><td colspan="4" class="text-center text-danger">Erreur: ' . safeErrorMessage($e) . '</td></tr>';
                } ?>
            </tbody>
        </table>
    </div>
</div>
