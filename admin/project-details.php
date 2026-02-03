<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=admin-login');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: ?page=admin-dashboard&section=projects');
    exit;
}

$project_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch();

if (!$project) {
    echo '<div class="alert alert-danger">Projet non trouvé</div>';
    exit;
}

// Récupérer les images de galerie
$stmt = $pdo->prepare("SELECT * FROM project_images WHERE project_id = ? ORDER BY order_pos");
$stmt->execute([$project_id]);
$gallery_images = $stmt->fetchAll();

// Récupérer les membres du projet
$stmt = $pdo->prepare("SELECT * FROM project_members WHERE project_id = ? ORDER BY id");
$stmt->execute([$project_id]);
$members = $stmt->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2><i class="fas fa-briefcase"></i> Détails du Projet</h2>
        <h4 class="text-muted mt-2"><?php echo htmlspecialchars($project['title']); ?></h4>
    </div>
    <a href="?page=admin-dashboard&section=projects" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<!-- TABS POUR ORGANISER -->
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info">
            <i class="fas fa-info-circle"></i> Informations
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#gallery">
            <i class="fas fa-images"></i> Galerie
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#members">
            <i class="fas fa-users"></i> Équipe
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- TAB 1: INFORMATIONS GÉNÉRALES -->
    <div class="tab-pane fade show active" id="info" role="tabpanel">
        <div class="card border-0 shadow-lg mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Informations Générales</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="actions/update-project-details.php" enctype="multipart/form-data">
                    <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                    <input type="hidden" name="tab" value="info">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Titre</label>
                                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Client</label>
                                <input type="text" class="form-control" name="client" value="<?php echo htmlspecialchars($project['client'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Statut</label>
                                <select class="form-control" name="status">
                                    <option value="en cours" <?php echo $project['status'] === 'en cours' ? 'selected' : ''; ?>>En cours</option>
                                    <option value="terminé" <?php echo $project['status'] === 'terminé' ? 'selected' : ''; ?>>Terminé</option>
                                    <option value="en attente" <?php echo $project['status'] === 'en attente' ? 'selected' : ''; ?>>En attente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date de début</label>
                                <input type="date" class="form-control" name="start_date" value="<?php echo htmlspecialchars($project['start_date'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date de fin</label>
                                <input type="date" class="form-control" name="end_date" value="<?php echo htmlspecialchars($project['end_date'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description courte</label>
                        <input type="text" class="form-control" name="short_description" value="<?php echo htmlspecialchars($project['short_description'] ?? ''); ?>" placeholder="Résumé court du projet">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description complète</label>
                        <textarea class="form-control" name="description" rows="6" required><?php echo htmlspecialchars($project['description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Budget</label>
                        <input type="number" step="0.01" class="form-control" name="budget" value="<?php echo htmlspecialchars($project['budget'] ?? ''); ?>" placeholder="0.00">
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 2: GALERIE D'IMAGES -->
    <div class="tab-pane fade" id="gallery" role="tabpanel">
        <div class="card border-0 shadow-lg mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-images"></i> Galerie du Projet</h5>
            </div>
            <div class="card-body">
                <!-- Images existantes -->
                <?php if (!empty($gallery_images)): ?>
                <h6 class="mb-3 fw-bold">Images actuelles (<?php echo count($gallery_images); ?>)</h6>
                <div class="row g-3 mb-4">
                    <?php foreach ($gallery_images as $img): ?>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <img src="uploads/<?php echo htmlspecialchars($img['image']); ?>" class="card-img-top" alt="" style="height: 150px; object-fit: cover;">
                            <div class="card-body p-2">
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-sort"></i> Ordre: <strong><?php echo $img['order_pos']; ?></strong>
                                </small>
                                <a href="actions/delete-project-image.php?id=<?php echo $img['id']; ?>&return=details&project_id=<?php echo $project_id; ?>" 
                                   class="btn btn-sm btn-danger w-100" 
                                   onclick="return confirm('Supprimer cette image ?')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <hr>
                <?php endif; ?>

                <!-- Ajouter nouvelles images -->
                <h6 class="mb-3 fw-bold">Ajouter des images</h6>
                <form method="POST" action="actions/update-project-details.php" enctype="multipart/form-data">
                    <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                    <input type="hidden" name="tab" value="gallery">
                    
                    <div class="mb-3">
                        <label class="form-label">Sélectionner les images</label>
                        <input type="file" class="form-control" name="gallery_images[]" accept="image/*" multiple>
                        <small class="text-muted d-block mt-2">Vous pouvez sélectionner plusieurs images</small>
                    </div>

                    <!-- Aperçu -->
                    <div id="gallery-preview-details" class="row g-3 mb-3" style="display: none;">
                        <div class="col-12">
                            <small class="fw-bold">Aperçu (<span id="count-details">0</span> image(s)):</small>
                        </div>
                        <div id="gallery-container-details"></div>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload"></i> Ajouter à la galerie
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 3: ÉQUIPE DU PROJET -->
    <div class="tab-pane fade" id="members" role="tabpanel">
        <div class="card border-0 shadow-lg mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users"></i> Équipe du Projet</h5>
                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                    <i class="fas fa-plus"></i> Ajouter un membre
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($members)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Rôle</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members as $member): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($member['member_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($member['role']); ?></td>
                                <td>
                                    <a href="actions/delete-project-member.php?id=<?php echo $member['id']; ?>&project_id=<?php echo $project_id; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Supprimer ce membre ?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Aucun membre assigné à ce projet
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Ajouter un membre -->
<div class="modal fade" id="addMemberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> Ajouter un membre</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="actions/add-project-member.php">
                <div class="modal-body">
                    <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom du membre *</label>
                        <input type="text" class="form-control" name="member_name" required placeholder="Ex: Jean Dupont">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Rôle/Fonction *</label>
                        <input type="text" class="form-control" name="role" required placeholder="Ex: Chef de projet, Développeur...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Aperçu galerie pour détails
document.querySelector('input[name="gallery_images[]"]').addEventListener('change', function(e) {
    const files = e.target.files;
    const container = document.getElementById('gallery-container-details');
    const countSpan = document.getElementById('count-details');
    
    container.innerHTML = '';
    countSpan.textContent = files.length;
    
    if (files.length > 0) {
        document.getElementById('gallery-preview-details').style.display = 'grid';
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(event) {
                const col = document.createElement('div');
                col.className = 'col-md-4 col-sm-6';
                col.innerHTML = `
                    <div class="card border-0 shadow-sm">
                        <img src="${event.target.result}" style="height: 120px; object-fit: cover;">
                        <small class="d-block p-2 text-muted text-truncate">${file.name}</small>
                    </div>
                `;
                container.appendChild(col);
            };
            reader.readAsDataURL(file);
        }
    } else {
        document.getElementById('gallery-preview-details').style.display = 'none';
    }
});
</script>
