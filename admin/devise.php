<h2>Gestion de la Devise de l'Entreprise</h2>

<?php
$about = $pdo->query("SELECT * FROM about LIMIT 1")->fetch();

if (!$about) {
    $pdo->exec("INSERT INTO about (motto, description) VALUES ('', '')");
    $about = $pdo->query("SELECT * FROM about LIMIT 1")->fetch();
}
?>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-quote-left"></i> Devise de l'Entreprise</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="actions/save-devise.php">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                    <div class="mb-4">
                        <label for="motto" class="form-label fw-bold">Devise (Slogan)</label>
                        <input type="text" class="form-control form-control-lg" id="motto" name="motto" value="<?php echo htmlspecialchars($about['motto'] ?? ''); ?>" placeholder="Ex: Votre succès est notre mission" required>
                        <small class="text-muted">La devise affichée en évidence sur la page d'accueil</small>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Description de l'Entreprise</label>
                        <textarea class="form-control form-control-lg" id="description" name="description" rows="8" placeholder="Décrivez votre entreprise, ses valeurs et sa mission..."><?php echo htmlspecialchars($about['description'] ?? ''); ?></textarea>
                        <small class="text-muted">Cette description s'affiche sur les pages À Propos et en pied de page</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="fas fa-info-circle"></i> Conseils</label>
                        <ul class="text-muted small">
                            <li>La devise doit être courte et mémorable (max 100 caractères)</li>
                            <li>La description peut être plus longue et détaillée (max 1000 caractères)</li>
                            <li>Utilisez un langage clair et professionnel</li>
                            <li>Mettez en avant vos valeurs et votre expertise</li>
                        </ul>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                            <i class="fas fa-save"></i> Enregistrer la devise
                        </button>
                        <button type="reset" class="btn btn-secondary btn-lg">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Aperçu -->
        <div class="card shadow-lg">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-eye"></i> Aperçu</h5>
            </div>
            <div class="card-body">
                <h6 class="text-muted mb-3">Devise affichée :</h6>
                <div class="p-3 bg-light rounded mb-4 border-start border-4 border-primary">
                    <p class="lead text-center mb-0 fst-italic">
                        <i class="fas fa-quote-left text-primary"></i>
                        <strong><?php echo htmlspecialchars($about['motto'] ?? 'Votre slogan'); ?></strong>
                        <i class="fas fa-quote-right text-primary"></i>
                    </p>
                </div>

                <h6 class="text-muted mb-3">Description affichée :</h6>
                <div class="p-3 bg-light rounded border-start border-4 border-info">
                    <p class="small text-muted" style="max-height: 200px; overflow-y: auto;">
                        <?php echo htmlspecialchars(substr($about['description'], 0, 300)); ?>
                        <?php if (strlen($about['description']) > 300) echo '...'; ?>
                    </p>
                </div>

                <div class="mt-4 p-3 bg-warning bg-opacity-10 rounded">
                    <p class="small mb-0">
                        <i class="fas fa-lightbulb text-warning"></i> 
                        <strong>Astuce :</strong> Modifiez la devise pour refléter votre identité et vos valeurs d'entreprise.
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="card shadow-lg mt-3">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Informations</h5>
            </div>
            <div class="card-body">
                <p class="small mb-2">
                    <strong>Devise :</strong> <?php echo strlen($about['motto']); ?>/100 caractères
                </p>
                <div class="progress mb-3">
                    <div class="progress-bar" style="width: <?php echo min(100, (strlen($about['motto'])/100)*100); ?>%"></div>
                </div>

                <p class="small mb-2">
                    <strong>Description :</strong> <?php echo strlen($about['description']); ?>/1000 caractères
                </p>
                <div class="progress">
                    <div class="progress-bar" style="width: <?php echo min(100, (strlen($about['description'])/1000)*100); ?>%"></div>
                </div>

                <p class="small text-muted mt-3 mb-0">
                    Dernière modification : <?php echo date('d/m/Y à H:i', strtotime($about['updated_at'] ?? date('Y-m-d H:i:s'))); ?>
                </p>
            </div>
        </div>
    </div>
</div>
