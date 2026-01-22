<h2>Gestion des Services</h2>
<a href="?page=admin-dashboard&section=services&action=add" class="btn btn-primary mb-3">+ Ajouter un Service</a>

<?php if (($_GET['action'] ?? '') === 'add'): ?>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Nouveau Service</h5>
        <form method="POST" action="actions/save-service.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Titre *</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="files" class="form-label">Fichiers PDF (optionnel)</label>
                <input type="file" class="form-control" id="files" name="files[]" accept=".pdf" multiple>
                <small class="text-muted d-block mt-2">Vous pouvez télécharger plusieurs fichiers PDF à la fois</small>
            </div>
            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="?page=admin-dashboard&section=services" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if (($_GET['action'] ?? '') === 'edit' && isset($_GET['id'])): ?>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Modifier le Service</h5>
        <?php
        try {
            $service_id = intval($_GET['id']);
            $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
            $stmt->execute([$service_id]);
            $service = $stmt->fetch();
            
            if ($service):
        ?>
        <form method="POST" action="actions/save-service.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
            <div class="mb-3">
                <label for="title" class="form-label">Titre *</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($service['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($service['description']); ?></textarea>
            </div>
            
            <!-- Fichiers existants -->
            <div class="mb-3">
                <label class="form-label">Fichiers PDF actuels</label>
                <div id="existing-files">
                    <?php
                    $files_stmt = $pdo->prepare("SELECT * FROM service_files WHERE service_id = ? ORDER BY uploaded_at DESC");
                    $files_stmt->execute([$service_id]);
                    $files = $files_stmt->fetchAll();
                    
                    if (!empty($files)):
                        foreach ($files as $file):
                    ?>
                    <div class="alert alert-info d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-file-pdf"></i> <?php echo htmlspecialchars($file['file_name']); ?></span>
                        <a href="actions/delete-service-file.php?id=<?php echo $file['id']; ?>&service_id=<?php echo $service_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce fichier ?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                    <?php endforeach; else: ?>
                    <p class="text-muted">Aucun fichier PDF</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Ajouter de nouveaux fichiers -->
            <div class="mb-3">
                <label for="files" class="form-label">Ajouter des fichiers PDF</label>
                <input type="file" class="form-control" id="files" name="files[]" accept=".pdf" multiple>
                <small class="text-muted d-block mt-2">Vous pouvez ajouter plusieurs fichiers PDF à la fois</small>
            </div>
            
            <button type="submit" class="btn btn-success">Mettre à jour</button>
            <a href="?page=admin-dashboard&section=services" class="btn btn-secondary">Annuler</a>
        </form>
        <?php else: ?>
        <div class="alert alert-warning">Service non trouvé</div>
        <?php endif; } catch (Exception $e) { echo '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>'; } ?>
    </div>
</div>
<?php endif; ?>

<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Fichiers PDF</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        try {
            $services = $pdo->query("SELECT s.*, COUNT(sf.id) as file_count FROM services s LEFT JOIN service_files sf ON s.id = sf.service_id GROUP BY s.id ORDER BY s.created_at DESC")->fetchAll();
            if (!empty($services)):
                foreach ($services as $service):
            ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($service['title']); ?></strong></td>
                <td><?php echo substr(htmlspecialchars($service['description']), 0, 100) . (strlen($service['description']) > 100 ? '...' : ''); ?></td>
                <td><span class="badge bg-info"><?php echo $service['file_count']; ?> fichier(s)</span></td>
                <td>
                    <a href="?page=admin-dashboard&section=services&action=edit&id=<?php echo $service['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                    <a href="actions/delete-service.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="4" class="text-center text-muted">Aucun service</td></tr>
            <?php endif; } catch (Exception $e) { echo '<tr><td colspan="4" class="alert alert-danger mb-0">' . htmlspecialchars($e->getMessage()) . '</td></tr>'; } ?>
        </tbody>
    </table>
