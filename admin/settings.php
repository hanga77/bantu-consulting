<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=admin-login');
    exit;
}

// Récupérer les paramètres
$settings = getSiteSettings();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h2><i class="fas fa-cog"></i> Paramètres du Site</h2>
</div>

<!-- TABS NAVIGATION -->
<ul class="nav nav-tabs mb-4 border-bottom" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
            <i class="fas fa-cog"></i> Général
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
            <i class="fas fa-phone"></i> Contact
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
            <i class="fas fa-search"></i> SEO
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="video-tab" data-bs-toggle="tab" data-bs-target="#video" type="button" role="tab">
            <i class="fas fa-video"></i> Vidéo Présentation
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button" role="tab">
            <i class="fas fa-map-marker-alt"></i> Localisation
        </button>
    </li>
</ul>

<!-- TABS CONTENT -->
<div class="tab-content">
    
    <!-- TAB 1: GÉNÉRAL -->
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-cog"></i> Paramètres Généraux</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="actions/save-settings.php">
                    <input type="hidden" name="section" value="general">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nom du site</label>
                            <input type="text" class="form-control form-control-lg" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Description</label>
                            <input type="text" class="form-control form-control-lg" name="site_description" value="<?php echo htmlspecialchars($settings['site_description'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Texte Footer</label>
                        <input type="text" class="form-control" name="footer_text" value="<?php echo htmlspecialchars($settings['footer_text'] ?? ''); ?>">
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 2: CONTACT -->
    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-phone"></i> Informations Contact</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="actions/save-settings.php">
                    <input type="hidden" name="section" value="contact">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Principal</label>
                            <input type="email" class="form-control form-control-lg" name="contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Secondaire</label>
                            <input type="email" class="form-control form-control-lg" name="contact_email2" value="<?php echo htmlspecialchars($settings['contact_email2'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Téléphone</label>
                            <input type="tel" class="form-control form-control-lg" name="phone" value="<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Adresse</label>
                            <input type="text" class="form-control form-control-lg" name="address" value="<?php echo htmlspecialchars($settings['address'] ?? ''); ?>">
                        </div>
                    </div>

                    <!-- Réseaux Sociaux -->
                    <hr>
                    <h6 class="fw-bold mb-3"><i class="fas fa-share-alt"></i> Réseaux Sociaux</h6>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="fab fa-facebook text-primary"></i> Facebook</label>
                            <input type="url" class="form-control" name="facebook_url" value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>" placeholder="https://facebook.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fab fa-twitter text-info"></i> Twitter</label>
                            <input type="url" class="form-control" name="twitter_url" value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>" placeholder="https://twitter.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fab fa-linkedin text-primary"></i> LinkedIn</label>
                            <input type="url" class="form-control" name="linkedin_url" value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? ''); ?>" placeholder="https://linkedin.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fab fa-instagram text-danger"></i> Instagram</label>
                            <input type="url" class="form-control" name="instagram_url" value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>" placeholder="https://instagram.com/...">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 3: SEO -->
    <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-search"></i> SEO et Meta Tags</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="actions/save-settings.php">
                    <input type="hidden" name="section" value="seo">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Title (max 60 caractères)</label>
                        <input type="text" class="form-control form-control-lg" name="meta_title" value="<?php echo htmlspecialchars($settings['meta_title'] ?? ''); ?>" maxlength="60" placeholder="Max 60 caractères">
                        <small class="text-muted d-block">Affiché dans les résultats Google</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Description (max 160 caractères)</label>
                        <textarea class="form-control" name="meta_description" rows="3" maxlength="160" placeholder="Max 160 caractères"><?php echo htmlspecialchars($settings['meta_description'] ?? ''); ?></textarea>
                        <small class="text-muted d-block">Affiché sous le titre dans Google</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mots-clés</label>
                        <input type="text" class="form-control" name="site_keywords" value="<?php echo htmlspecialchars($settings['site_keywords'] ?? ''); ?>" placeholder="Séparés par des virgules">
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 4: VIDÉO -->
    <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-tab">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-film"></i> Vidéo de Présentation</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Vidéo actuelle -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3"><i class="fas fa-check-circle text-success"></i> Vidéo actuelle</h6>
                            <?php if (!empty($settings['presentation_video'])): ?>
                            <div class="mb-3">
                                <video width="100%" height="auto" controls style="max-height: 400px; border-radius: 8px; border: 2px solid #1e40af;">
                                    <source src="<?php echo htmlspecialchars($settings['presentation_video']); ?>" type="video/mp4">
                                    Votre navigateur ne supporte pas les vidéos HTML5.
                                </video>
                            </div>
                            <p class="text-muted small">
                                <i class="fas fa-info-circle"></i> 
                                Dimensions: <strong>1920x1080px</strong> | Format: <strong>16:9</strong>
                            </p>
                            <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Aucune vidéo uploadée
                            </div>
                            <?php endif; ?>
                        </div>

                        <hr>

                        <!-- Formulaire upload -->
                        <h6 class="fw-bold mb-3"><i class="fas fa-upload"></i> Télécharger une nouvelle vidéo</h6>
                        <form method="POST" action="actions/save-settings-video.php" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="presentation_video" class="form-label fw-bold">Fichier vidéo *</label>
                                <input type="file" class="form-control form-control-lg" id="presentation_video" name="presentation_video" accept="video/mp4,video/webm" required>
                                <small class="text-muted d-block mt-2">
                                    Format: MP4 ou WebM | Taille max: 200 MB<br>
                                    Dimensions recommandées: 1920x1080px (16:9)
                                </small>
                            </div>

                            <!-- Aperçu vidéo -->
                            <div id="video-preview-settings" style="display: none;" class="mb-3">
                                <label class="form-label fw-bold">Aperçu:</label>
                                <video id="preview-video-settings" width="100%" height="auto" controls style="max-height: 300px; border-radius: 8px; border: 2px solid #1e40af;"></video>
                                <small class="text-muted d-block mt-2" id="video-info-settings"></small>
                            </div>

                            <div class="alert alert-warning">
                                <strong><i class="fas fa-lightbulb"></i> Conseils:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Format recommandé: <strong>MP4 (H.264)</strong></li>
                                    <li>Résolution: <strong>1920x1080px (16:9)</strong></li>
                                    <li>La vidéo s'affichera en arrière-plan sur la page d'accueil</li>
                                </ul>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </form>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="fas fa-cogs"></i> Paramètres vidéo</h6>
                                
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <strong>Aspect ratio:</strong> 16:9<br>
                                        <strong>Résolution:</strong> 1920 x 1080<br>
                                        <strong>FPS:</strong> 30 fps<br>
                                        <strong>Codec vidéo:</strong> H.264<br>
                                        <strong>Codec audio:</strong> AAC 128k
                                    </small>
                                </div>

                                <hr>

                                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle"></i> Info</h6>
                                <small class="text-muted">
                                    La vidéo sera automatiquement optimisée pour le web et les appareils mobiles.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 5: LOCALISATION -->
    <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Localisation GPS</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="actions/save-settings.php">
                    <input type="hidden" name="section" value="location">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Latitude</label>
                            <input type="text" class="form-control form-control-lg" name="latitude" value="<?php echo htmlspecialchars($settings['latitude'] ?? '4.0511'); ?>" placeholder="4.0511">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Longitude</label>
                            <input type="text" class="form-control form-control-lg" name="longitude" value="<?php echo htmlspecialchars($settings['longitude'] ?? '9.7679'); ?>" placeholder="9.7679">
                        </div>
                    </div>

                    <!-- Carte Leaflet -->
                    <div id="map" style="height: 400px; border-radius: 8px; border: 2px solid #1e40af; margin-bottom: 15px;"></div>

                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Comment obtenir les coordonnées:</strong><br>
                        1. Allez sur <a href="https://www.google.com/maps" target="_blank">Google Maps</a><br>
                        2. Cherchez votre adresse<br>
                        3. Clic-droit → Les coordonnées s'affichent en haut
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- SCRIPTS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<script>
// Aperçu vidéo pour settings
document.getElementById('presentation_video').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Valider la taille (200MB)
        const maxSize = 200 * 1024 * 1024;
        if (file.size > maxSize) {
                    Toast.error('Fichier trop volumineux. Maximum: 200MB. Votre fichier: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
            document.getElementById('video-preview-settings').style.display = 'none';
            return;
        }
        
        // Valider le type
        const allowedTypes = ['video/mp4', 'video/webm'];
        if (!allowedTypes.includes(file.type)) {
            Toast.error('Format non autorisé. Utilisez MP4 ou WebM. Type détecté: ' + file.type);
            e.target.value = '';
            document.getElementById('video-preview-settings').style.display = 'none';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(event) {
            const videoElement = document.getElementById('preview-video-settings');
            videoElement.src = event.target.result;
            
            const info = document.getElementById('video-info-settings');
            info.innerHTML = `📹 ${file.name} | Taille: ${(file.size / 1024 / 1024).toFixed(2)}MB`;
            
            document.getElementById('video-preview-settings').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('video-preview-settings').style.display = 'none';
    }
});

// Carte Leaflet
document.addEventListener('DOMContentLoaded', function() {
    const lat = parseFloat(document.querySelector('input[name="latitude"]').value) || 4.0511;
    const lon = parseFloat(document.querySelector('input[name="longitude"]').value) || 9.7679;
    
    const map = L.map('map').setView([lat, lon], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    let marker = L.marker([lat, lon]).addTo(map).bindPopup('Ma localisation').openPopup();
    
    map.on('click', function(e) {
        const newLat = e.latlng.lat.toFixed(4);
        const newLon = e.latlng.lng.toFixed(4);
        
        document.querySelector('input[name="latitude"]').value = newLat;
        document.querySelector('input[name="longitude"]').value = newLon;
        
        map.removeLayer(marker);
        marker = L.marker([newLat, newLon]).addTo(map).bindPopup(`${newLat}, ${newLon}`).openPopup();
        map.setView([newLat, newLon], 13);
    });
});
</script>
