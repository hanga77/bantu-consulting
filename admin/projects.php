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
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
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
                        <label for="image" class="form-label fw-bold">Image principale du projet</label>
                        <input type="file" class="form-control form-control-lg" id="image" name="image" accept="image/*">
                        <small class="text-muted d-block mt-2">Format: JPG, PNG | Taille max: 5MB</small>
                        
                        <!-- Aperçu image principale -->
                        <div id="image-preview-main" class="mt-3" style="display: none;">
                            <label class="form-label fw-bold">Aperçu:</label>
                            <div style="max-width: 300px; border-radius: 8px; border: 2px solid #1e40af; overflow: hidden;">
                                <img id="preview-img-main" src="" alt="Aperçu" style="width: 100%; height: auto; display: block;">
                            </div>
                            <small class="text-muted d-block mt-2" id="image-info-main"></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GALERIE D'IMAGES -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-images"></i> Galerie d'Images (Optionnel)</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Ajouter plusieurs images pour la galerie du projet</p>
                    <div class="mb-3">
                        <label for="gallery_images" class="form-label">Télécharger les images de la galerie</label>
                        <input type="file" class="form-control form-control-lg" id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                        <small class="text-muted d-block mt-2">Vous pouvez sélectionner plusieurs images à la fois (Ctrl+Clic)</small>
                    </div>

                    <!-- Aperçu galerie -->
                    <div id="gallery-preview" class="row g-3" style="display: none; margin-top: 15px;">
                        <div class="col-12">
                            <label class="form-label fw-bold"><i class="fas fa-eye"></i> Aperçu des images (<span id="image-count">0</span> sélectionnées):</label>
                        </div>
                        <div id="gallery-images-container"></div>
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

<script>
// Aperçu image principale - AJOUT
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = document.getElementById('preview-img-main');
            img.src = event.target.result;
            
            const image = new Image();
            image.onload = function() {
                const info = document.getElementById('image-info-main');
                info.innerHTML = `<i class="fas fa-info-circle"></i> Dimensions: ${image.width}x${image.height}px | Taille: ${(file.size / 1024 / 1024).toFixed(2)}MB`;
            };
            image.src = event.target.result;
            
            document.getElementById('image-preview-main').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('image-preview-main').style.display = 'none';
    }
});

// Aperçu galerie images - AMÉLIORÉ
document.getElementById('gallery_images').addEventListener('change', function(e) {
    const files = e.target.files;
    const container = document.getElementById('gallery-images-container');
    const countSpan = document.getElementById('image-count');
    container.innerHTML = '';
    countSpan.textContent = files.length;
    
    if (files.length > 0) {
        document.getElementById('gallery-preview').style.display = 'grid';
        
        let loadedCount = 0;
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(event) {
                loadedCount++;
                
                const col = document.createElement('div');
                col.className = 'col-md-4 col-sm-6';
                col.style.animation = 'fadeIn 0.3s ease-in';
                
                const img = new Image();
                img.onload = function() {
                    col.innerHTML = `
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 8px; overflow: hidden;">
                            <div style="position: relative; overflow: hidden; background: #f0f0f0;">
                                <img src="${event.target.result}" alt="Galerie ${i+1}" 
                                     style="width: 100%; height: 180px; object-fit: cover; display: block; transition: transform 0.3s;">
                                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; 
                                           background: rgba(40, 167, 69, 0.05); opacity: 0; transition: opacity 0.3s;"
                                     onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                    <div style="position: absolute; bottom: 10px; left: 10px; color: #28a745; font-weight: bold; font-size: 12px;">
                                        ${img.width}x${img.height}px
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-2">
                                <small class="text-muted d-block text-truncate"><i class="fas fa-file-image"></i> ${file.name}</small>
                                <small class="text-muted d-block"><i class="fas fa-database"></i> ${(file.size / 1024).toFixed(0)}KB</small>
                                <small class="text-success d-block mt-1"><i class="fas fa-check-circle"></i> Image ${loadedCount} / ${files.length}</small>
                            </div>
                        </div>
                    `;
                };
                img.src = event.target.result;
                
                container.appendChild(col);
            };
            reader.readAsDataURL(file);
        }
    } else {
        document.getElementById('gallery-preview').style.display = 'none';
        countSpan.textContent = '0';
    }
});

// Animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    #gallery-images-container .col-md-4:hover img {
        transform: scale(1.05);
    }
`;
document.head.appendChild(style);
</script>
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
                // Récupérer les images de galerie
                $stmt_images = $pdo->prepare("SELECT * FROM project_images WHERE project_id = ? ORDER BY order_pos");
                $stmt_images->execute([$proj_id]);
                $gallery_images = $stmt_images->fetchAll();
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
                        <label for="image" class="form-label fw-bold">Image principale du projet</label>
                        <input type="file" class="form-control form-control-lg" id="image-edit" name="image" accept="image/*">
                        <small class="text-muted d-block mt-2">Laisser vide pour garder l'image actuelle</small>
                        
                        <!-- Image actuelle -->
                        <?php if ($project['image']): ?>
                        <div class="mt-3">
                            <label class="form-label fw-bold">Image actuelle:</label>
                            <div style="max-width: 300px; border-radius: 8px; border: 2px solid #1e40af; overflow: hidden;">
                                <img src="uploads/<?php echo htmlspecialchars($project['image']); ?>" alt="Image" style="width: 100%; height: auto; display: block;">
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Aperçu nouvelle image -->
                        <div id="image-preview-edit-main" class="mt-3" style="display: none;">
                            <label class="form-label fw-bold">Nouvelle image:</label>
                            <div style="max-width: 300px; border-radius: 8px; border: 2px solid #28a745; overflow: hidden;">
                                <img id="preview-img-edit-main" src="" alt="Aperçu" style="width: 100%; height: auto; display: block;">
                            </div>
                            <small class="text-muted d-block mt-2" id="image-info-edit-main"></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GALERIE D'IMAGES -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-images"></i> Galerie d'Images</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Ajouter des images à la galerie ou modifier l'ordre existant</p>

                    <!-- Images existantes -->
                    <?php if (!empty($gallery_images)): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Images actuelles:</label>
                        <div class="row g-2">
                            <?php foreach ($gallery_images as $img): ?>
                            <div class="col-md-3 position-relative">
                                <div style="border-radius: 8px; overflow: hidden; border: 2px solid #dee2e6;">
                                    <img src="uploads/<?php echo htmlspecialchars($img['image']); ?>" alt="Galerie" style="width: 100%; height: 150px; object-fit: cover; display: block;">
                                </div>
                                <a href="actions/delete-project-image.php?id=<?php echo $img['id']; ?>" class="btn btn-sm btn-danger mt-2 w-100" onclick="return confirm('Supprimer cette image ?')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <hr>
                    <?php endif; ?>

                    <!-- Ajouter nouvelles images -->
                    <div class="mb-3">
                        <label for="gallery_images_edit" class="form-label">Ajouter des images à la galerie</label>
                        <input type="file" class="form-control form-control-lg" id="gallery_images_edit" name="gallery_images[]" accept="image/*" multiple>
                        <small class="text-muted d-block mt-2">Vous pouvez sélectionner plusieurs images à la fois</small>
                    </div>

                    <!-- Aperçu nouvelles images -->
                    <div id="gallery-preview-edit" class="row g-3" style="display: none; margin-top: 15px;">
                        <div class="col-12">
                            <label class="form-label fw-bold"><i class="fas fa-eye"></i> Aperçu des nouvelles images (<?php echo '<span id="image-count-edit">0</span>'; ?> sélectionnées):</label>
                        </div>
                        <div id="gallery-images-container-edit"></div>
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

        <script>
        // Aperçu image principale édition
        document.getElementById('image-edit').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.getElementById('preview-img-edit-main');
                    img.src = event.target.result;
                    
                    const image = new Image();
                    image.onload = function() {
                        const info = document.getElementById('image-info-edit-main');
                        info.innerHTML = `<i class="fas fa-info-circle"></i> Dimensions: ${image.width}x${image.height}px | Taille: ${(file.size / 1024 / 1024).toFixed(2)}MB`;
                    };
                    image.src = event.target.result;
                    
                    document.getElementById('image-preview-edit-main').style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('image-preview-edit-main').style.display = 'none';
            }
        });

        // Aperçu galerie images édition
        document.getElementById('gallery_images_edit').addEventListener('change', function(e) {
            const files = e.target.files;
            const container = document.getElementById('gallery-images-container-edit');
            const countSpan = document.getElementById('image-count-edit');
            container.innerHTML = '';
            countSpan.textContent = files.length;
            
            if (files.length > 0) {
                document.getElementById('gallery-preview-edit').style.display = 'grid';
                
                let loadedCount = 0;
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        loadedCount++;
                        
                        const col = document.createElement('div');
                        col.className = 'col-md-4 col-sm-6';
                        col.style.animation = 'fadeIn 0.3s ease-in';
                        
                        const img = new Image();
                        img.onload = function() {
                            col.innerHTML = `
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 8px; overflow: hidden;">
                                    <div style="position: relative; overflow: hidden; background: #f0f0f0;">
                                        <img src="${event.target.result}" alt="Galerie ${i+1}" 
                                             style="width: 100%; height: 180px; object-fit: cover; display: block; transition: transform 0.3s;">
                                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; 
                                                   background: rgba(40, 167, 69, 0.05); opacity: 0; transition: opacity 0.3s;"
                                             onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                            <div style="position: absolute; bottom: 10px; left: 10px; color: #28a745; font-weight: bold; font-size: 12px;">
                                                ${img.width}x${img.height}px
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <small class="text-muted d-block text-truncate"><i class="fas fa-file-image"></i> ${file.name}</small>
                                        <small class="text-muted d-block"><i class="fas fa-database"></i> ${(file.size / 1024).toFixed(0)}KB</small>
                                        <small class="text-success d-block mt-1"><i class="fas fa-check-circle"></i> Image ${loadedCount} / ${files.length}</small>
                                    </div>
                                </div>
                            `;
                        };
                        img.src = event.target.result;
                        
                        container.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                }
            } else {
                document.getElementById('gallery-preview-edit').style.display = 'none';
                countSpan.textContent = '0';
            }
        });

        // Animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            #gallery-images-container-edit .col-md-4:hover img {
                transform: scale(1.05);
            }
        `;
        document.head.appendChild(style);
        </script>
        <?php }} catch (Exception $e) { echo '<div class="alert alert-danger">Erreur: ' . safeErrorMessage($e) . '</div>'; } ?>
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
                        <a href="?page=admin-dashboard&section=project-details&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-info" title="Voir les détails">
                            <i class="fas fa-eye"></i> Détails
                        </a>
                        <a href="?page=admin-dashboard&section=projects&action=edit&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="actions/delete-project.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center text-muted py-3">Aucun projet</td></tr>
                <?php endif; } catch (Exception $e) { echo '<tr><td colspan="6" class="alert alert-danger mb-0">' . safeErrorMessage($e) . '</td></tr>'; } ?>
            </tbody>
        </table>
    </div>
</div>