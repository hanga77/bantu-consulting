<h2><i class="fas fa-cogs"></i> Gestion des Services</h2>
<a href="?page=admin-dashboard&section=services&action=add" class="btn btn-primary mb-3">
    <i class="fas fa-plus-circle"></i> Ajouter un Service
</a>

<!-- Mode Liste (par défaut) -->
<?php if (($_GET['action'] ?? '') !== 'add' && ($_GET['action'] ?? '') !== 'edit'): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><i class="fas fa-list"></i> Liste des Services</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Contact</th>
                    <th style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $services = $pdo->query("SELECT * FROM services ORDER BY created_at DESC")->fetchAll();
                    if (!empty($services)):
                        foreach ($services as $service):
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($service['title']); ?></strong></td>
                    <td><?php echo substr(htmlspecialchars($service['description']), 0, 80) . (strlen($service['description']) > 80 ? '...' : ''); ?></td>
                    <td>
                        <?php if (!empty($service['contact_email'])): ?>
                            <small><?php echo htmlspecialchars($service['contact_email']); ?></small>
                        <?php else: ?>
                            <small class="text-muted">-</small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?page=admin-dashboard&section=services&action=edit&id=<?php echo $service['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> 
                        </a>
                        <a href="actions/delete-service.php?id=<?php echo $service['id']; ?>" onclick="return confirm('Êtes-vous sûr?');" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> 
                        </a>
                    </td>
                </tr>
                <?php 
                        endforeach;
                    else:
                        echo '<tr><td colspan="4" class="text-center text-muted py-4"><i class="fas fa-inbox"></i> Aucun service ajouté</td></tr>';
                    endif;
                } catch (Exception $e) {
                    echo '<tr><td colspan="4" class="text-center text-danger">Erreur: ' . safeErrorMessage($e) . '</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Formulaire Ajouter -->
<?php if (($_GET['action'] ?? '') === 'add'): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-gradient text-white">
        <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Ajouter un Nouveau Service</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="actions/save-service.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <!-- Info Basique -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informations Basiques</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Titre du Service *</label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" required placeholder="Ex: Consulting Stratégique">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description Principale *</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required placeholder="Description détaillée du service..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-phone-alt"></i> Informations de Contact</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="contact_email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="contact@example.com">
                        </div>
                        <div class="col-md-6">
                            <label for="contact_phone" class="form-label"><i class="fas fa-phone"></i> Téléphone</label>
                            <input type="tel" class="form-control" id="contact_phone" name="contact_phone" placeholder="+33 1 23 45 67 89">
                        </div>
                        <div class="col-md-12">
                            <label for="website" class="form-label"><i class="fas fa-globe"></i> Site Web</label>
                            <input type="url" class="form-control" id="website" name="website" placeholder="https://...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avantages -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-star"></i> 4 Avantages du Service</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Avantage 1 - Titre</label>
                            <input type="text" class="form-control" name="benefit1_title" placeholder="Ex: Expertise Reconnue">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 1 - Description</label>
                            <input type="text" class="form-control" name="benefit1_desc" placeholder="Décrivez cet avantage...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 2 - Titre</label>
                            <input type="text" class="form-control" name="benefit2_title" placeholder="Ex: Innovation Technologique">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 2 - Description</label>
                            <input type="text" class="form-control" name="benefit2_desc" placeholder="Décrivez cet avantage...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 3 - Titre</label>
                            <input type="text" class="form-control" name="benefit3_title" placeholder="Ex: Support Complet">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 3 - Description</label>
                            <input type="text" class="form-control" name="benefit3_desc" placeholder="Décrivez cet avantage...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 4 - Titre</label>
                            <input type="text" class="form-control" name="benefit4_title" placeholder="Ex: Résultats Mesurables">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 4 - Description</label>
                            <input type="text" class="form-control" name="benefit4_desc" placeholder="Décrivez cet avantage...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Processus -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-cogs"></i> 4 Étapes du Processus</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Étape 1 - Titre</label>
                            <input type="text" class="form-control" name="process1_title" placeholder="Ex: Analyse des Besoins">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 1 - Description</label>
                            <input type="text" class="form-control" name="process1_desc" placeholder="Décrivez cette étape...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 2 - Titre</label>
                            <input type="text" class="form-control" name="process2_title" placeholder="Ex: Élaboration de la Stratégie">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 2 - Description</label>
                            <input type="text" class="form-control" name="process2_desc" placeholder="Décrivez cette étape...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 3 - Titre</label>
                            <input type="text" class="form-control" name="process3_title" placeholder="Ex: Mise en Œuvre">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 3 - Description</label>
                            <input type="text" class="form-control" name="process3_desc" placeholder="Décrivez cette étape...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 4 - Titre</label>
                            <input type="text" class="form-control" name="process4_title" placeholder="Ex: Suivi & Optimisation">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 4 - Description</label>
                            <input type="text" class="form-control" name="process4_desc" placeholder="Décrivez cette étape...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Faits Rapides -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-lightning-bolt"></i> 4 Faits Rapides</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Courtes affirmations clés sur votre service (une ligne chacune)</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fait 1</label>
                            <input type="text" class="form-control" name="fact1" placeholder="Ex: Équipe d'experts certifiés">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fait 2</label>
                            <input type="text" class="form-control" name="fact2" placeholder="Ex: Résultats garantis en 30 jours">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fait 3</label>
                            <input type="text" class="form-control" name="fact3" placeholder="Ex: Support client 24/7">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fait 4</label>
                            <input type="text" class="form-control" name="fact4" placeholder="Ex: 500+ clients satisfaits">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fichiers et Liens -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-link"></i> Fichiers PDF et Liens</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="files" class="form-label">Fichiers PDF (optionnel)</label>
                        <input type="file" class="form-control" id="files" name="files[]" accept=".pdf" multiple>
                        <small class="text-muted d-block mt-2">Téléchargez plusieurs fichiers PDF à la fois</small>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Enregistrer le Service
                </button>
                <a href="?page=admin-dashboard&section=services" class="btn btn-secondary btn-lg">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Formulaire Modifier -->
<?php if (($_GET['action'] ?? '') === 'edit' && isset($_GET['id'])): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-gradient text-white">
        <h5 class="mb-0"><i class="fas fa-edit"></i> Modifier le Service</h5>
    </div>
    <div class="card-body">
        <?php
        try {
            $service_id = intval($_GET['id']);
            $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
            $stmt->execute([$service_id]);
            $service = $stmt->fetch();
            
            if (!$service) {
                echo '<div class="alert alert-warning">Service non trouvé</div>';
            } else {
        ?>
        <form method="POST" action="actions/save-service.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
            
            <!-- Info Basique -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informations Basiques</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Titre du Service *</label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" value="<?php echo htmlspecialchars($service['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description Principale *</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($service['description']); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-phone-alt"></i> Informations de Contact</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="contact_email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($service['contact_email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="contact_phone" class="form-label"><i class="fas fa-phone"></i> Téléphone</label>
                            <input type="tel" class="form-control" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($service['contact_phone'] ?? ''); ?>">
                        </div>
                        <div class="col-md-12">
                            <label for="website" class="form-label"><i class="fas fa-globe"></i> Site Web</label>
                            <input type="url" class="form-control" id="website" name="website" value="<?php echo htmlspecialchars($service['website'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avantages -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-star"></i> 4 Avantages du Service</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Avantage 1 - Titre</label>
                            <input type="text" class="form-control" name="benefit1_title" value="<?php echo htmlspecialchars($service['benefit1_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 1 - Description</label>
                            <input type="text" class="form-control" name="benefit1_desc" value="<?php echo htmlspecialchars($service['benefit1_desc'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 2 - Titre</label>
                            <input type="text" class="form-control" name="benefit2_title" value="<?php echo htmlspecialchars($service['benefit2_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 2 - Description</label>
                            <input type="text" class="form-control" name="benefit2_desc" value="<?php echo htmlspecialchars($service['benefit2_desc'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 3 - Titre</label>
                            <input type="text" class="form-control" name="benefit3_title" value="<?php echo htmlspecialchars($service['benefit3_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 3 - Description</label>
                            <input type="text" class="form-control" name="benefit3_desc" value="<?php echo htmlspecialchars($service['benefit3_desc'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 4 - Titre</label>
                            <input type="text" class="form-control" name="benefit4_title" value="<?php echo htmlspecialchars($service['benefit4_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avantage 4 - Description</label>
                            <input type="text" class="form-control" name="benefit4_desc" value="<?php echo htmlspecialchars($service['benefit4_desc'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Processus -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-cogs"></i> 4 Étapes du Processus</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Étape 1 - Titre</label>
                            <input type="text" class="form-control" name="process1_title" value="<?php echo htmlspecialchars($service['process1_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 1 - Description</label>
                            <input type="text" class="form-control" name="process1_desc" value="<?php echo htmlspecialchars($service['process1_desc'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 2 - Titre</label>
                            <input type="text" class="form-control" name="process2_title" value="<?php echo htmlspecialchars($service['process2_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 2 - Description</label>
                            <input type="text" class="form-control" name="process2_desc" value="<?php echo htmlspecialchars($service['process2_desc'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 3 - Titre</label>
                            <input type="text" class="form-control" name="process3_title" value="<?php echo htmlspecialchars($service['process3_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 3 - Description</label>
                            <input type="text" class="form-control" name="process3_desc" value="<?php echo htmlspecialchars($service['process3_desc'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 4 - Titre</label>
                            <input type="text" class="form-control" name="process4_title" value="<?php echo htmlspecialchars($service['process4_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Étape 4 - Description</label>
                            <input type="text" class="form-control" name="process4_desc" value="<?php echo htmlspecialchars($service['process4_desc'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Faits Rapides -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-lightning-bolt"></i> 4 Faits Rapides</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Courtes affirmations clés sur votre service (une ligne chacune)</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fait 1</label>
                            <input type="text" class="form-control" name="fact1" value="<?php echo htmlspecialchars($service['fact1'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fait 2</label>
                            <input type="text" class="form-control" name="fact2" value="<?php echo htmlspecialchars($service['fact2'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fait 3</label>
                            <input type="text" class="form-control" name="fact3" value="<?php echo htmlspecialchars($service['fact3'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fait 4</label>
                            <input type="text" class="form-control" name="fact4" value="<?php echo htmlspecialchars($service['fact4'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fichiers existants -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-file"></i> Fichiers et Ressources Actuels</h6>
                </div>
                <div class="card-body">
                    <?php
                    $files_stmt = $pdo->prepare("SELECT * FROM service_files WHERE service_id = ? ORDER BY id DESC");
                    $files_stmt->execute([$service_id]);
                    $files = $files_stmt->fetchAll();
                    
                    if (!empty($files)):
                        foreach ($files as $file):
                    ?>
                    <div class="alert alert-info d-flex justify-content-between align-items-center mb-2">
                        <span>
                            <?php if ($file['file_type'] === 'pdf'): ?>
                                <i class="fas fa-file-pdf text-danger"></i> PDF: <?php echo htmlspecialchars($file['file_name']); ?>
                            <?php else: ?>
                                <i class="fas fa-link text-info"></i> Lien: <?php echo htmlspecialchars($file['file_name']); ?>
                            <?php endif; ?>
                        </span>
                        <a href="actions/delete-service-file.php?id=<?php echo $file['id']; ?>&service_id=<?php echo $service_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                    <?php endforeach; else: ?>
                    <p class="text-muted">Aucun fichier</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Ajouter fichiers -->
            <div class="card border-0 bg-light mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-link"></i> Ajouter des Fichiers et Liens</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="files" class="form-label">Fichiers PDF</label>
                        <input type="file" class="form-control" id="files" name="files[]" accept=".pdf" multiple>
                        <small class="text-muted d-block mt-2">Téléchargez plusieurs fichiers PDF</small>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Mettre à Jour
                </button>
                <a href="?page=admin-dashboard&section=services" class="btn btn-secondary btn-lg">
                    Annuler
                </a>
            </div>
        </form>
        <?php }} catch (Exception $e) { echo '<div class="alert alert-danger">Erreur: ' . safeErrorMessage($e) . '</div>'; } ?>
    </div>
</div>
<?php endif; ?>
