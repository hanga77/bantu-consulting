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

<!-- Mode Liste (par défaut) -->
<?php if (($_GET['action'] ?? '') !== 'add' && ($_GET['action'] ?? '') !== 'edit'): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><i class="fas fa-user-tie"></i> Liste des Experts</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Nom</th>
                    <th>Spécialité</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $experts = $pdo->query("SELECT * FROM experts ORDER BY name ASC")->fetchAll();
                    if (!empty($experts)):
                        foreach ($experts as $expert):
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($expert['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($expert['specialty'] ?? '-'); ?></td>
                    <td>
                        <a href="mailto:<?php echo htmlspecialchars($expert['email']); ?>">
                            <?php echo htmlspecialchars($expert['email'] ?? '-'); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($expert['phone'] ?? '-'); ?></td>
                    <td>
                        <a href="?page=admin-dashboard&section=experts&action=edit&id=<?php echo $expert['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> 
                        </a>
                        <a href="actions/delete-expert.php?id=<?php echo $expert['id']; ?>" onclick="return confirm('Êtes-vous sûr?');" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> 
                        </a>
                    </td>
                </tr>
                <?php 
                        endforeach;
                    else:
                        echo '<tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-inbox"></i> Aucun expert ajouté</td></tr>';
                    endif;
                } catch (Exception $e) {
                    echo '<tr><td colspan="5" class="text-center text-danger">Erreur: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
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
        <h5 class="mb-0"><i class="fas fa-user-plus"></i> Ajouter un Expert</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="actions/save-expert.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nom complet *</label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name" required placeholder="Ex: Dr. Jean Dupont">
                    </div>
                    <div class="mb-3">
                        <label for="specialty" class="form-label fw-bold">Spécialité *</label>
                        <input type="text" class="form-control form-control-lg" id="specialty" name="specialty" required placeholder="Ex: Finance, RH, IT">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="expert@example.com">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-bold">Téléphone</label>
                        <input type="tel" class="form-control form-control-lg" id="phone" name="phone" placeholder="+237 XXX XXX XXX">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Photo</label>
                        <input type="file" class="form-control form-control-lg" id="image" name="image" accept="image/*">
                        <small class="text-muted d-block mt-2">Format: JPG, PNG | Taille: max 2MB | Idéal: 300x350px</small>
                        
                        <!-- Aperçu d'image -->
                        <div id="image-preview-add" class="mt-3" style="display: none;">
                            <label class="form-label fw-bold">Aperçu:</label>
                            <div style="max-width: 150px; border-radius: 8px; border: 2px solid #1e40af; overflow: hidden;">
                                <img id="preview-img-add" src="" alt="Aperçu" style="width: 100%; height: auto; display: block;">
                            </div>
                            <small class="text-muted d-block mt-2" id="image-info-add"></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description / Biographie</label>
                <textarea class="form-control" id="description" name="description" rows="5" placeholder="Décrivez l'expertise et l'expérience de cet expert..."></textarea>
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
        <h5 class="mb-0"><i class="fas fa-edit"></i> Modifier un Expert</h5>
    </div>
    <div class="card-body">
        <?php
        try {
            $expert_id = intval($_GET['id']);
            $stmt = $pdo->prepare("SELECT * FROM experts WHERE id = ?");
            $stmt->execute([$expert_id]);
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
                        <label for="name_edit" class="form-label fw-bold">Nom complet *</label>
                        <input type="text" class="form-control form-control-lg" id="name_edit" name="name" value="<?php echo htmlspecialchars($expert['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="specialty_edit" class="form-label fw-bold">Spécialité *</label>
                        <input type="text" class="form-control form-control-lg" id="specialty_edit" name="specialty" value="<?php echo htmlspecialchars($expert['specialty']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_edit" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control form-control-lg" id="email_edit" name="email" value="<?php echo htmlspecialchars($expert['email'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="phone_edit" class="form-label fw-bold">Téléphone</label>
                        <input type="tel" class="form-control form-control-lg" id="phone_edit" name="phone" value="<?php echo htmlspecialchars($expert['phone'] ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image_edit" class="form-label fw-bold">Photo</label>
                        <input type="file" class="form-control form-control-lg" id="image_edit" name="image" accept="image/*">
                        <small class="text-muted d-block mt-2">Laisser vide pour garder l'image actuelle</small>
                        
                        <!-- Image actuelle -->
                        <?php if ($expert['image']): ?>
                        <div class="mt-3">
                            <label class="form-label fw-bold">Image actuelle:</label>
                            <div style="max-width: 150px; border-radius: 8px; border: 2px solid #1e40af; overflow: hidden;">
                                <img src="uploads/<?php echo htmlspecialchars($expert['image']); ?>" alt="Photo" style="width: 100%; height: auto; display: block;">
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Aperçu nouvelle image -->
                        <div id="image-preview-edit" class="mt-3" style="display: none;">
                            <label class="form-label fw-bold">Nouvelle image:</label>
                            <div style="max-width: 150px; border-radius: 8px; border: 2px solid #28a745; overflow: hidden;">
                                <img id="preview-img-edit" src="" alt="Aperçu" style="width: 100%; height: auto; display: block;">
                            </div>
                            <small class="text-muted d-block mt-2" id="image-info-edit"></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description_edit" class="form-label fw-bold">Description / Biographie</label>
                <textarea class="form-control" id="description_edit" name="description" rows="5"><?php echo htmlspecialchars($expert['description'] ?? ''); ?></textarea>
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
