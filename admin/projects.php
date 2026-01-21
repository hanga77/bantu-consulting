<div class="d-flex align-items-center justify-content-between mb-4">
    <h2><i class="fas fa-briefcase"></i> Gestion des Projets</h2>
    <a href="?page=admin-dashboard&section=projects&action=add" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Ajouter un Projet
    </a>
</div>

<?php if (($_GET['action'] ?? '') === 'add'): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-gradient text-white">
        <h5 class="mb-0"><i class="fas fa-folder-plus"></i> Ajouter un Nouveau Projet</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="actions/save-project.php" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Titre du projet *</label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" required placeholder="Ex: Transformation Digitale Banque">
                    </div>
                    <div class="mb-3">
                        <label for="short_description" class="form-label fw-bold">Description courte</label>
                        <textarea class="form-control" id="short_description" name="short_description" rows="2" placeholder="Max 255 caractères"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Statut</label>
                        <select class="form-control form-control-lg" id="status" name="status">
                            <option value="">-- Sélectionner --</option>
                            <option value="Terminé">Terminé</option>
                            <option value="En cours">En cours</option>
                            <option value="Planifié">Planifié</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="start_date" class="form-label fw-bold">Date de début</label>
                        <input type="date" class="form-control form-control-lg" id="start_date" name="start_date">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label fw-bold">Date de fin</label>
                        <input type="date" class="form-control form-control-lg" id="end_date" name="end_date">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Image principale *</label>
                        <input type="file" class="form-control form-control-lg" id="image" name="image" accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG | Max 2MB</small>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description complète *</label>
                <textarea class="form-control" id="description" name="description" rows="5" required placeholder="Décrivez le projet en détail..."></textarea>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-lightbulb"></i> 
                <strong>Conseil :</strong> Vous pouvez ajouter d'autres images à la galerie du projet après sa création.
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Créer le projet
                </button>
                <a href="?page=admin-dashboard&section=projects" class="btn btn-secondary btn-lg">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Affichage des projets existants -->
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Titre</th>
                <th>Statut</th>
                <th>Date début</th>
                <th>Images</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
            if (!empty($projects)):
                foreach ($projects as $project):
                    $img_count = $pdo->prepare("SELECT COUNT(*) as count FROM project_images WHERE project_id = ?")->execute([$project['id']]);
                    $img_result = $pdo->prepare("SELECT COUNT(*) as count FROM project_images WHERE project_id = ?");
                    $img_result->execute([$project['id']]);
                    $img_data = $img_result->fetch();
            ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($project['title']); ?></strong></td>
                <td><span class="badge bg-success"><?php echo htmlspecialchars($project['status']); ?></span></td>
                <td><?php echo $project['start_date'] ? date('d/m/Y', strtotime($project['start_date'])) : '-'; ?></td>
                <td>
                    <a href="?page=admin-dashboard&section=projects&action=gallery&id=<?php echo $project['id']; ?>" class="btn btn-sm btn-info">
                        <i class="fas fa-images"></i> <?php echo $img_data['count']; ?> image(s)
                    </a>
                </td>
                <td>
                    <a href="?page=admin-dashboard&section=projects&action=gallery&id=<?php echo $project['id']; ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> Galerie
                    </a>
                    <a href="actions/delete-project.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">
                        <i class="fas fa-trash"></i> Supprimer
                    </a>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="5" class="text-center text-muted">Aucun projet</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Galerie des images du projet -->
<?php if (($_GET['action'] ?? '') === 'gallery' && isset($_GET['id'])): ?>
<?php 
$project_id = $_GET['id'];
$project = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$project->execute([$project_id]);
$proj = $project->fetch();

if ($proj):
    $images = $pdo->prepare("SELECT * FROM project_images WHERE project_id = ? ORDER BY order_pos");
    $images->execute([$project_id]);
    $imgs = $images->fetchAll();
?>

<div class="card border-0 shadow-lg mt-4">
    <div class="card-header bg-gradient text-white">
        <h5 class="mb-0"><i class="fas fa-images"></i> Galerie : <?php echo htmlspecialchars($proj['title']); ?></h5>
    </div>
    <div class="card-body">
        <!-- Ajouter une image -->
        <form method="POST" action="actions/save-project-image.php" enctype="multipart/form-data" class="mb-4 p-3 bg-light rounded">
            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image_file" class="form-label fw-bold">Ajouter une image</label>
                        <input type="file" class="form-control" id="image_file" name="image" accept="image/*" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image_title" class="form-label fw-bold">Titre de l'image</label>
                        <input type="text" class="form-control" id="image_title" name="title" placeholder="Titre optionnel">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="image_desc" class="form-label fw-bold">Description</label>
                <textarea class="form-control" id="image_desc" name="description" rows="2" placeholder="Description optionnelle"></textarea>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-plus"></i> Ajouter l'image
            </button>
        </form>

        <!-- Liste des images -->
        <div class="row mt-4">
            <?php if (!empty($imgs)): ?>
                <?php foreach ($imgs as $img): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="uploads/<?php echo htmlspecialchars($img['image']); ?>" class="card-img-top" alt="" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title"><?php echo htmlspecialchars($img['title'] ?? 'Sans titre'); ?></h6>
                            <p class="card-text small text-muted"><?php echo htmlspecialchars(substr($img['description'] ?? '', 0, 50)); ?></p>
                            <a href="actions/delete-project-image.php?id=<?php echo $img['id']; ?>&project_id=<?php echo $project_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <div class="col-md-12">
                <p class="text-muted text-center">Aucune image dans la galerie</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<a href="?page=admin-dashboard&section=projects" class="btn btn-secondary mt-3">
    Retour aux projets
</a>

<?php endif; ?>
<?php endif; // fin if action=gallery ?>