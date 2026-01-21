<h2>⚙️ Paramètres du Site</h2>

<?php
$settings = getSiteSettings();
?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <!-- Onglets Navigation -->
        <ul class="nav nav-tabs border-bottom-0 mb-4" id="settingsTabs" role="tablist" style="background-color: #f8fafc; padding: 10px; border-radius: 8px 8px 0 0;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="branding-tab" data-bs-toggle="tab" data-bs-target="#branding" type="button" role="tab">
                    <i class="fas fa-image"></i> Branding
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                    <i class="fas fa-cog"></i> Général
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="video-tab" data-bs-toggle="tab" data-bs-target="#video" type="button" role="tab">
                    <i class="fas fa-video"></i> Vidéo
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
                    <i class="fas fa-search"></i> SEO
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer" type="button" role="tab">
                    <i class="fas fa-shoe-prints"></i> Footer
                </button>
            </li>
        </ul>

        <form method="POST" action="actions/save-settings.php" enctype="multipart/form-data">
            <div class="tab-content" id="settingsTabContent">
                
                <!-- TAB 1: BRANDING -->
                <div class="tab-pane fade show active" id="branding" role="tabpanel">
                    <h5 class="mb-4">Branding et Identité Visuelle</h5>
                    
                    <div class="row">
                        <!-- Logo -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-2 border-info">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-image"></i> Logo du Site</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="site_logo" class="form-label fw-bold">Télécharger le logo</label>
                                        <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/*">
                                        <small class="text-muted d-block mt-2">
                                            Format: PNG ou JPG | Taille: 200x50px
                                        </small>
                                    </div>
                                    
                                    <?php if (!empty($settings['site_logo'])): ?>
                                    <div class="alert alert-info">
                                        <h6>Logo actuel:</h6>
                                        <div class="p-3 bg-white rounded" style="border: 1px dashed #0c5460;">
                                            <img src="uploads/<?php echo htmlspecialchars($settings['site_logo']); ?>" alt="Logo" style="max-height: 60px;">
                                        </div>
                                        <a href="actions/delete-logo.php" class="btn btn-sm btn-danger mt-2" onclick="return confirm('Supprimer ?')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </a>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-warning">Aucun logo uploadé</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Favicon -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-2 border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="fas fa-star"></i> Favicon</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="site_favicon" class="form-label fw-bold">Télécharger le favicon</label>
                                        <input type="file" class="form-control" id="site_favicon" name="site_favicon" accept="image/*">
                                        <small class="text-muted d-block mt-2">
                                            Format: PNG, ICO | Taille: 32x32px
                                        </small>
                                    </div>
                                    
                                    <?php if (!empty($settings['site_favicon'])): ?>
                                    <div class="alert alert-info">
                                        <h6>Favicon actuel:</h6>
                                        <div class="p-3 bg-white rounded" style="border: 1px dashed #0c5460;">
                                            <img src="uploads/<?php echo htmlspecialchars($settings['site_favicon']); ?>" alt="Favicon" style="width: 32px;">
                                        </div>
                                        <a href="actions/delete-favicon.php" class="btn btn-sm btn-danger mt-2" onclick="return confirm('Supprimer ?')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </a>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-warning">Aucun favicon uploadé</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="site_name" class="form-label fw-bold">Nom du Site</label>
                        <input type="text" class="form-control form-control-lg" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="site_description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="site_description" name="site_description" rows="3"><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                    </div>
                </div>

                <!-- TAB 2: GÉNÉRAL -->
                <div class="tab-pane fade" id="general" role="tabpanel">
                    <h5 class="mb-4">Informations Générales</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_email" class="form-label fw-bold"><i class="fas fa-envelope"></i> Email Principal</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact_email2" class="form-label fw-bold"><i class="fas fa-envelope"></i> Email Secondaire</label>
                            <input type="email" class="form-control" id="contact_email2" name="contact_email2" value="<?php echo htmlspecialchars($settings['contact_email2'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label fw-bold"><i class="fas fa-phone"></i> Téléphone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label fw-bold"><i class="fas fa-map-marker-alt"></i> Adresse</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($settings['address'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- TAB 3: VIDÉO -->
                <div class="tab-pane fade" id="video" role="tabpanel">
                    <h5 class="mb-4">Vidéo de Présentation</h5>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb"></i> 
                        Deux options : URL YouTube ou fichier vidéo local
                    </div>

                    <div class="mb-3">
                        <label for="presentation_video" class="form-label fw-bold"><i class="fas fa-video"></i> URL YouTube</label>
                        <input type="text" class="form-control form-control-lg" id="presentation_video" name="presentation_video" 
                               value="<?php echo htmlspecialchars($settings['presentation_video'] ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ'); ?>"
                               placeholder="https://www.youtube.com/embed/...">
                        <small class="text-muted d-block mt-2">
                            Exemple: https://www.youtube.com/embed/dQw4w9WgXcQ
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="video_file" class="form-label fw-bold"><i class="fas fa-file-video"></i> OU Uploader une vidéo locale</label>
                        <input type="file" class="form-control" id="video_file" name="video_file" accept="video/mp4,video/webm,video/ogg">
                        <small class="text-muted d-block mt-2">
                            Format: MP4, WebM ou OGG (max 50MB)<br>
                            <strong>Si vous uploadez une vidéo, elle remplacera l'URL YouTube</strong>
                        </small>
                    </div>

                    <?php if (!empty($settings['presentation_video']) && (strpos($settings['presentation_video'], 'youtube') === false && strpos($settings['presentation_video'], 'youtu.be') === false)): ?>
                    <div class="alert alert-success">
                        <h6>Vidéo locale actuelle:</h6>
                        <a href="<?php echo htmlspecialchars($settings['presentation_video']); ?>" target="_blank" class="text-break">
                            <?php echo htmlspecialchars($settings['presentation_video']); ?>
                        </a>
                    </div>
                    <?php endif; ?>

                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">💡 Conseils</h6>
                            <ul class="small mb-0">
                                <li><strong>YouTube:</strong> Gratuit, hébergé sur les serveurs Google (recommandé)</li>
                                <li><strong>Vidéo locale:</strong> Stockée sur votre serveur (meilleur contrôle)</li>
                                <li>Les vidéos locales doivent être compressées pour de meilleures performances</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- TAB 4: SEO -->
                <div class="tab-pane fade" id="seo" role="tabpanel">
                    <h5 class="mb-4">Optimisation SEO</h5>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb"></i> 
                        Ces informations apparaîtront dans Google et les réseaux sociaux
                    </div>

                    <div class="mb-3">
                        <label for="meta_title" class="form-label fw-bold">Titre (Meta Title)</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title" 
                               value="<?php echo htmlspecialchars($settings['meta_title'] ?? ''); ?>" maxlength="60">
                        <small class="text-muted">Max 60 caractères (<span id="title-count">0</span>/60)</small>
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label fw-bold">Description (Meta Description)</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="3" maxlength="160"><?php echo htmlspecialchars($settings['meta_description'] ?? ''); ?></textarea>
                        <small class="text-muted">Max 160 caractères (<span id="desc-count">0</span>/160)</small>
                    </div>

                    <div class="mb-3">
                        <label for="site_keywords" class="form-label fw-bold">Mots-clés</label>
                        <textarea class="form-control" id="site_keywords" name="site_keywords" rows="3"><?php echo htmlspecialchars($settings['site_keywords'] ?? ''); ?></textarea>
                        <small class="text-muted">Séparés par des virgules</small>
                    </div>
                </div>

                <!-- TAB 5: FOOTER -->
                <div class="tab-pane fade" id="footer" role="tabpanel">
                    <h5 class="mb-4">Pied de Page</h5>
                    
                    <div class="mb-3">
                        <label for="footer_text" class="form-label fw-bold">Texte du Footer</label>
                        <textarea class="form-control" id="footer_text" name="footer_text" rows="2"><?php echo htmlspecialchars($settings['footer_text'] ?? ''); ?></textarea>
                        <small class="text-muted">Ex: © 2024 Bantu Consulting. Tous droits réservés.</small>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Enregistrer les paramètres
            </button>
        </form>
    </div>
</div>

<script>
// Compteurs de caractères
document.getElementById('meta_title').addEventListener('input', function() {
    document.getElementById('title-count').textContent = this.value.length;
});

document.getElementById('meta_description').addEventListener('input', function() {
    document.getElementById('desc-count').textContent = this.value.length;
});

// Initialiser
document.getElementById('title-count').textContent = document.getElementById('meta_title').value.length;
document.getElementById('desc-count').textContent = document.getElementById('meta_description').value.length;
</script>
