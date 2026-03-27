<div class="d-flex align-items-center justify-content-between mb-4">
    <h2><i class="fas fa-users"></i> Gestion des Équipes</h2>
    <a href="?page=admin-dashboard&section=teams&action=add" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Ajouter un Membre
    </a>
</div>

<!-- Mode Liste (par défaut) -->
<?php if (($_GET['action'] ?? '') !== 'add' && ($_GET['action'] ?? '') !== 'edit'): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><i class="fas fa-users"></i> Liste des Membres de l'Équipe</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Nom</th>
                    <th>Poste</th>
                    <th>Département/Pôle</th>
                    <th style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $teams = $pdo->query("SELECT t.*, d.name as dept_name FROM teams t LEFT JOIN departments d ON t.department_id = d.id ORDER BY t.importance DESC, t.name")->fetchAll();
                    if (!empty($teams)):
                        foreach ($teams as $team):
                ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($team['name']); ?></strong>
                    </td>
                    <td><?php echo htmlspecialchars($team['position']); ?></td>
                    <td>
                        <?php if (!empty($team['department_id'])): ?>
                            <span class="badge bg-info"><i class="fas fa-folder"></i> <?php echo htmlspecialchars($team['dept_name']); ?></span>
                        <?php else: ?>
                            <span class="badge bg-secondary"><i class="fas fa-star"></i> Support & Transversale</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?page=admin-dashboard&section=teams&action=edit&id=<?php echo $team['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> 
                        </a>
                        <a href="actions/delete-team.php?id=<?php echo $team['id']; ?>" onclick="return confirm('Êtes-vous sûr?');" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> 
                        </a>
                    </td>
                </tr>
                <?php 
                        endforeach;
                    else:
                        echo '<tr><td colspan="4" class="text-center text-muted py-4"><i class="fas fa-inbox"></i> Aucun membre ajouté</td></tr>';
                    endif;
                } catch (Exception $e) {
                    echo '<tr><td colspan="4" class="text-center text-danger">Erreur: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- FORMULAIRE AJOUT -->
<?php if (($_GET['action'] ?? '') === 'add'): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-gradient text-white">
        <h5 class="mb-0"><i class="fas fa-user-plus"></i> Ajouter un Nouveau Membre</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="actions/save-team.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nom complet *</label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name" required placeholder="Ex: Jean Kamba Diba">
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label fw-bold">Poste/Titre *</label>
                        <input type="text" class="form-control form-control-lg" id="position" name="position" required placeholder="Ex: Directeur du Pôle">
                    </div>
                    <div class="mb-3">
                        <label for="importance" class="form-label fw-bold">Importance/Responsabilité</label>
                        <select class="form-control form-control-lg" id="importance" name="importance">
                            <option value="">-- Sélectionner --</option>
                            <option value="Responsable">Responsable</option>
                            <option value="Manager">Manager</option>
                            <option value="Consultant">Consultant</option>
                            <option value="Spécialiste">Spécialiste</option>
                            <option value="Coordinateur">Coordinateur</option>
                        </select>
                        <small class="text-muted">Détermine l'ordre d'affichage</small>
                    </div>
                    <div class="mb-3">
                        <label for="experience" class="form-label fw-bold">Années d'expérience</label>
                        <input type="number" class="form-control form-control-lg" id="experience" name="experience" value="0" min="0" max="50">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="department_id" class="form-label fw-bold">Département/Pôle <span class="badge bg-warning text-dark">OPTIONNEL</span></label>
                        <select class="form-control form-control-lg" id="department_id" name="department_id">
                            <option value="" selected>-- AUCUN (Pas de département) --</option>
                            <?php
                            try {
                                $depts = $pdo->query("SELECT id, name FROM departments ORDER BY name")->fetchAll();
                                foreach ($depts as $dept):
                            ?>
                            <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                            <?php endforeach; } catch (Exception $e) {} ?>
                        </select>
                        <small class="text-muted d-block mt-2">
                            <strong>✓ AVEC département</strong> : Affichage dans la page "Équipes" sous le département<br>
                            <strong>✓ SANS département</strong> : Affichage dans "À Propos" → Section "Équipe Support & Transversale"
                        </small>
                    </div>

                    <div class="alert alert-success mb-3" role="alert">
                        <i class="fas fa-star"></i> <strong>Pour un rôle support</strong><br>
                        Laissez le champ "Département/Pôle" vide
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Photo *</label>
                        <input type="file" class="form-control form-control-lg" id="image" name="image" accept="image/*" required>
                        <small class="text-muted d-block mt-2">Format: JPG, PNG | Taille: max 2MB | Idéal: 300x350px</small>
                        
                        <!-- Aperçu d'image -->
                        <div id="image-preview-add" class="mt-3" style="display: none;">
                            <label class="form-label fw-bold">Aperçu:</label>
                            <div style="max-width: 180px; border-radius: 50%; overflow: hidden; border: 3px solid #1e40af; margin: 0 auto;">
                                <img id="preview-img-add" src="" alt="Aperçu" style="width: 100%; height: 100%; display: block; object-fit: cover;">
                            </div>
                            <small class="text-muted d-block mt-2 text-center" id="image-info-add"></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label fw-bold">Description/Rôle</label>
                <textarea class="form-control" id="role" name="role" rows="5" placeholder="Décrivez les responsabilités..."></textarea>
            </div>

            <!-- Réseaux Sociaux -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-share-alt"></i> Réseaux Sociaux (Optionnel)</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="linkedin" class="form-label"><i class="fab fa-linkedin text-info"></i> LinkedIn</label>
                            <input type="url" class="form-control" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/...">
                        </div>
                        <div class="col-md-6">
                            <label for="twitter" class="form-label"><i class="fab fa-twitter text-info"></i> Twitter</label>
                            <input type="url" class="form-control" id="twitter" name="twitter" placeholder="https://twitter.com/...">
                        </div>
                        <div class="col-md-6">
                            <label for="facebook" class="form-label"><i class="fab fa-facebook text-primary"></i> Facebook</label>
                            <input type="url" class="form-control" id="facebook" name="facebook" placeholder="https://facebook.com/...">
                        </div>
                        <div class="col-md-6">
                            <label for="instagram" class="form-label"><i class="fab fa-instagram text-danger"></i> Instagram</label>
                            <input type="url" class="form-control" id="instagram" name="instagram" placeholder="https://instagram.com/...">
                        </div>
                        <div class="col-md-6">
                            <label for="website" class="form-label"><i class="fas fa-globe text-success"></i> Site Web</label>
                            <input type="url" class="form-control" id="website" name="website" placeholder="https://monsite.com">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="?page=admin-dashboard&section=teams" class="btn btn-secondary btn-lg">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Aperçu d'image pour formulaire ajout
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = document.getElementById('preview-img-add');
            img.src = event.target.result;
            
            const image = new Image();
            image.onload = function() {
                const info = document.getElementById('image-info-add');
                info.innerHTML = `${image.width}x${image.height}px | ${(file.size / 1024).toFixed(0)}KB`;
            };
            image.src = event.target.result;
            
            document.getElementById('image-preview-add').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('image-preview-add').style.display = 'none';
    }
});
</script>
<?php endif; ?>

<!-- FORMULAIRE ÉDITION -->
<?php if (($_GET['action'] ?? '') === 'edit' && isset($_GET['id'])): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-gradient text-white">
        <h5 class="mb-0"><i class="fas fa-edit"></i> Modifier un Membre</h5>
    </div>
    <div class="card-body">
        <?php
        try {
            $team_id = intval($_GET['id']);
            $stmt = $pdo->prepare("SELECT * FROM teams WHERE id = ?");
            $stmt->execute([$team_id]);
            $team = $stmt->fetch();
            
            if (!$team) {
                echo '<div class="alert alert-warning">Membre non trouvé</div>';
            } else {
        ?>
        <form method="POST" action="actions/save-team.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $team['id']; ?>">
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name_edit" class="form-label fw-bold">Nom complet *</label>
                        <input type="text" class="form-control form-control-lg" id="name_edit" name="name" value="<?php echo htmlspecialchars($team['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="position_edit" class="form-label fw-bold">Poste/Titre *</label>
                        <input type="text" class="form-control form-control-lg" id="position_edit" name="position" value="<?php echo htmlspecialchars($team['position']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="importance_edit" class="form-label fw-bold">Importance/Responsabilité</label>
                        <select class="form-control form-control-lg" id="importance_edit" name="importance">
                            <option value="">-- Sélectionner --</option>
                            <option value="Responsable" <?php echo $team['importance'] === 'Responsable' ? 'selected' : ''; ?>>Responsable</option>
                            <option value="Manager" <?php echo $team['importance'] === 'Manager' ? 'selected' : ''; ?>>Manager</option>
                            <option value="Consultant" <?php echo $team['importance'] === 'Consultant' ? 'selected' : ''; ?>>Consultant</option>
                            <option value="Spécialiste" <?php echo $team['importance'] === 'Spécialiste' ? 'selected' : ''; ?>>Spécialiste</option>
                            <option value="Coordinateur" <?php echo $team['importance'] === 'Coordinateur' ? 'selected' : ''; ?>>Coordinateur</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="experience_edit" class="form-label fw-bold">Années d'expérience</label>
                        <input type="number" class="form-control form-control-lg" id="experience_edit" name="experience" value="<?php echo htmlspecialchars($team['experience'] ?? 0); ?>" min="0" max="50">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="department_id_edit" class="form-label fw-bold">Département/Pôle <span class="badge bg-warning text-dark">OPTIONNEL</span></label>
                        <select class="form-control form-control-lg" id="department_id_edit" name="department_id">
                            <option value="">-- AUCUN (Pas de département) --</option>
                            <?php
                            try {
                                $depts = $pdo->query("SELECT id, name FROM departments ORDER BY name")->fetchAll();
                                foreach ($depts as $dept):
                            ?>
                            <option value="<?php echo $dept['id']; ?>" <?php echo $team['department_id'] == $dept['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($dept['name']); ?></option>
                            <?php endforeach; } catch (Exception $e) {} ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image_edit" class="form-label fw-bold">Photo</label>
                        <input type="file" class="form-control form-control-lg" id="image_edit" name="image" accept="image/*">
                        <small class="text-muted d-block mt-2">Laisser vide pour garder l'image actuelle</small>
                        
                        <!-- Image actuelle -->
                        <?php if ($team['image']): ?>
                        <div class="mt-3">
                            <label class="form-label fw-bold">Image actuelle:</label>
                            <div style="max-width: 180px; border-radius: 50%; overflow: hidden; border: 3px solid #1e40af; margin: 0 auto;">
                                <img src="uploads/<?php echo htmlspecialchars($team['image']); ?>" alt="Photo" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Aperçu nouvelle image -->
                        <div id="image-preview-edit" class="mt-3" style="display: none;">
                            <label class="form-label fw-bold">Nouvelle image:</label>
                            <div style="max-width: 180px; border-radius: 50%; overflow: hidden; border: 3px solid #28a745; margin: 0 auto;">
                                <img id="preview-img-edit" src="" alt="Aperçu" style="width: 100%; height: 100%; display: block; object-fit: cover;">
                            </div>
                            <small class="text-muted d-block mt-2 text-center" id="image-info-edit"></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="role_edit" class="form-label fw-bold">Description/Rôle</label>
                <textarea class="form-control" id="role_edit" name="role" rows="5"><?php echo htmlspecialchars($team['role'] ?? ''); ?></textarea>
            </div>

            <!-- Réseaux Sociaux -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-share-alt"></i> Réseaux Sociaux (Optionnel)</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="linkedin_edit" class="form-label"><i class="fab fa-linkedin text-info"></i> LinkedIn</label>
                            <input type="url" class="form-control" id="linkedin_edit" name="linkedin" placeholder="https://linkedin.com/in/..." value="<?php echo htmlspecialchars($team['linkedin'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="twitter_edit" class="form-label"><i class="fab fa-twitter text-info"></i> Twitter</label>
                            <input type="url" class="form-control" id="twitter_edit" name="twitter" placeholder="https://twitter.com/..." value="<?php echo htmlspecialchars($team['twitter'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="facebook_edit" class="form-label"><i class="fab fa-facebook text-primary"></i> Facebook</label>
                            <input type="url" class="form-control" id="facebook_edit" name="facebook" placeholder="https://facebook.com/..." value="<?php echo htmlspecialchars($team['facebook'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="instagram_edit" class="form-label"><i class="fab fa-instagram text-danger"></i> Instagram</label>
                            <input type="url" class="form-control" id="instagram_edit" name="instagram" placeholder="https://instagram.com/..." value="<?php echo htmlspecialchars($team['instagram'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="website_edit" class="form-label"><i class="fas fa-globe text-success"></i> Site Web</label>
                            <input type="url" class="form-control" id="website_edit" name="website" placeholder="https://monsite.com" value="<?php echo htmlspecialchars($team['website'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="?page=admin-dashboard&section=teams" class="btn btn-secondary btn-lg">
                    Annuler
                </a>
            </div>
        </form>

        <script>
        // Aperçu d'image pour formulaire édition
        document.getElementById('image_edit').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.getElementById('preview-img-edit');
                    img.src = event.target.result;
                    
                    const image = new Image();
                    image.onload = function() {
                        const info = document.getElementById('image-info-edit');
                        info.innerHTML = `${image.width}x${image.height}px | ${(file.size / 1024).toFixed(0)}KB`;
                    };
                    image.src = event.target.result;
                    
                    document.getElementById('image-preview-edit').style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('image-preview-edit').style.display = 'none';
            }
        });
        </script>
        <?php } } catch (Exception $e) { echo '<div class="alert alert-danger">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>'; } ?>
    </div>
</div>
<?php endif; ?>
