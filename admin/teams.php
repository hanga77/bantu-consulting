<div class="d-flex align-items-center justify-content-between mb-4">
    <h2><i class="fas fa-users"></i> Gestion des Équipes</h2>
    <a href="?page=admin-dashboard&section=teams&action=add" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Ajouter un Membre
    </a>
</div>

<?php if (($_GET['action'] ?? '') === 'add'): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-gradient text-white">
        <h5 class="mb-0"><i class="fas fa-user-plus"></i> Ajouter un Nouveau Membre</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="actions/save-team.php" enctype="multipart/form-data">
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
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="department_id" class="form-label fw-bold">Département/Pôle *</label>
                        <select class="form-control form-control-lg" id="department_id" name="department_id" required>
                            <option value="">-- Sélectionner un département --</option>
                            <?php
                            try {
                                $depts = $pdo->query("SELECT id, name FROM departments ORDER BY name")->fetchAll();
                                foreach ($depts as $dept):
                            ?>
                            <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                            <?php endforeach; } catch (Exception $e) {} ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Photo *</label>
                        <input type="file" class="form-control form-control-lg" id="image" name="image" accept="image/*" required>
                        <small class="text-muted d-block mt-2">Format: JPG, PNG | Taille: max 2MB | Idéal: 300x350px</small>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label fw-bold">Description/Rôle</label>
                <textarea class="form-control" id="role" name="role" rows="5" placeholder="Décrivez les responsabilités, expériences et fonctions principales..."></textarea>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-lightbulb"></i> 
                <strong>Conseil :</strong> Le premier membre ajouté à un département s'affichera comme "Responsable". Les suivants s'afficheront dans la section "Équipe".
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
<?php endif; ?>

<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>Nom</th>
            <th>Poste</th>
            <th>Département</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $teams = $pdo->query("SELECT t.*, d.name as dept_name FROM teams t LEFT JOIN departments d ON t.department_id = d.id ORDER BY t.name")->fetchAll();
        if (!empty($teams)):
            foreach ($teams as $team):
        ?>
        <tr>
            <td><?php echo htmlspecialchars($team['name']); ?></td>
            <td><?php echo htmlspecialchars($team['position']); ?></td>
            <td><?php echo htmlspecialchars($team['dept_name'] ?? 'N/A'); ?></td>
            <td>
                <a href="actions/delete-team.php?id=<?php echo $team['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="4" class="text-center text-muted">Aucun membre</td></tr>
        <?php endif; ?>
    </tbody>
</table>
