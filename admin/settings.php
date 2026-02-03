<?php
// Récupérer les paramètres actuels
$settings_stmt = $pdo->query("SELECT * FROM site_settings LIMIT 1");
$settings = $settings_stmt->fetch();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h2><i class="fas fa-cog"></i> Paramètres du Site</h2>
</div>

<!-- Tabs pour organiser les paramètres -->
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button">
            <i class="fas fa-globe"></i> Général
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button">
            <i class="fas fa-search"></i> SEO
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button">
            <i class="fas fa-phone"></i> Contact
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button">
            <i class="fas fa-map-marker-alt"></i> Localisation
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- TAB GÉNÉRAL -->
    <div class="tab-pane fade show active" id="general" role="tabpanel">
        <div class="card border-0 shadow-lg mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations Générales</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="actions/save-settings.php" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_name" class="form-label fw-bold">Nom du Site</label>
                                <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_description" class="form-label fw-bold">Description</label>
                                <textarea class="form-control" id="site_description" name="site_description" rows="3"><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_logo" class="form-label fw-bold">Logo</label>
                                <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/*">
                                <small class="text-muted d-block mt-2">Format: PNG, JPG | Max: 2MB</small>
                                
                                <!-- Aperçu Logo -->
                                <?php if (!empty($settings['site_logo'])): ?>
                                <div class="mt-3">
                                    <label class="form-label small">Logo actuel:</label><br>
                                    <img src="uploads/<?php echo htmlspecialchars($settings['site_logo']); ?>" alt="Logo" style="max-height: 60px;">
                                </div>
                                <?php endif; ?>
                                
                                <div id="logo-preview" style="display: none; margin-top: 10px;">
                                    <label class="form-label small">Nouvel logo:</label><br>
                                    <img id="logo-img" src="" alt="Aperçu" style="max-height: 60px;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_favicon" class="form-label fw-bold">Favicon</label>
                                <input type="file" class="form-control" id="site_favicon" name="site_favicon" accept="image/*">
                                <small class="text-muted d-block mt-2">Format: PNG, JPG | Max: 1MB | Idéal: 32x32px</small>
                                
                                <!-- Aperçu Favicon -->
                                <?php if (!empty($settings['site_favicon'])): ?>
                                <div class="mt-3">
                                    <label class="form-label small">Favicon actuel:</label><br>
                                    <img src="uploads/<?php echo htmlspecialchars($settings['site_favicon']); ?>" alt="Favicon" style="max-height: 32px;">
                                </div>
                                <?php endif; ?>
                                
                                <div id="favicon-preview" style="display: none; margin-top: 10px;">
                                    <label class="form-label small">Nouveau favicon:</label><br>
                                    <img id="favicon-img" src="" alt="Aperçu" style="max-height: 32px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="site_keywords" class="form-label fw-bold">Mots-clés</label>
                        <input type="text" class="form-control" id="site_keywords" name="site_keywords" value="<?php echo htmlspecialchars($settings['site_keywords'] ?? ''); ?>" placeholder="consultation, conseil, stratégie...">
                    </div>

                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB SEO -->
    <div class="tab-pane fade" id="seo" role="tabpanel">
        <div class="card border-0 shadow-lg mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-search"></i> Optimisation SEO</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="actions/save-settings.php">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label fw-bold">Titre Meta</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title" value="<?php echo htmlspecialchars($settings['meta_title'] ?? ''); ?>" maxlength="60">
                        <small class="text-muted d-block">Max 60 caractères (affiché dans Google)</small>
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label fw-bold">Description Meta</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="3" maxlength="160"><?php echo htmlspecialchars($settings['meta_description'] ?? ''); ?></textarea>
                        <small class="text-muted d-block">Max 160 caractères (affiché dans Google)</small>
                    </div>

                    <div class="mb-3">
                        <label for="footer_text" class="form-label fw-bold">Texte Footer</label>
                        <textarea class="form-control" id="footer_text" name="footer_text" rows="3"><?php echo htmlspecialchars($settings['footer_text'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB CONTACT -->
    <div class="tab-pane fade" id="contact" role="tabpanel">
        <div class="card border-0 shadow-lg mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-phone"></i> Informations de Contact</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="actions/save-settings.php">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_email" class="form-label fw-bold">Email Principal</label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_email2" class="form-label fw-bold">Email Secondaire</label>
                                <input type="email" class="form-control" id="contact_email2" name="contact_email2" value="<?php echo htmlspecialchars($settings['contact_email2'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-bold">Téléphone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Réseaux Sociaux -->
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="fas fa-share-alt"></i> Réseaux Sociaux</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="facebook_url" class="form-label"><i class="fab fa-facebook text-primary"></i> Facebook</label>
                                    <input type="url" class="form-control" id="facebook_url" name="facebook_url" value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>" placeholder="https://facebook.com/...">
                                </div>
                                <div class="col-md-6">
                                    <label for="twitter_url" class="form-label"><i class="fab fa-twitter text-info"></i> Twitter</label>
                                    <input type="url" class="form-control" id="twitter_url" name="twitter_url" value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>" placeholder="https://twitter.com/...">
                                </div>
                                <div class="col-md-6">
                                    <label for="linkedin_url" class="form-label"><i class="fab fa-linkedin text-primary"></i> LinkedIn</label>
                                    <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? ''); ?>" placeholder="https://linkedin.com/...">
                                </div>
                                <div class="col-md-6">
                                    <label for="instagram_url" class="form-label"><i class="fab fa-instagram text-danger"></i> Instagram</label>
                                    <input type="url" class="form-control" id="instagram_url" name="instagram_url" value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>" placeholder="https://instagram.com/...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB LOCALISATION -->
    <div class="tab-pane fade" id="location" role="tabpanel">
        <div class="card border-0 shadow-lg mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Localisation & Coordonnées GPS</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="actions/save-settings.php">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label fw-bold">Adresse Complète</label>
                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Ex: Kinshasa, République Démocratique du Congo"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label fw-bold">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Ex: 4.0511" value="<?php echo htmlspecialchars($settings['latitude'] ?? '4.0511'); ?>">
                                <small class="text-muted">Format décimal: -90 à +90</small>
                            </div>
                            <div class="mb-3">
                                <label for="longitude" class="form-label fw-bold">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Ex: 9.7679" value="<?php echo htmlspecialchars($settings['longitude'] ?? '9.7679'); ?>">
                                <small class="text-muted">Format décimal: -180 à +180</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Comment trouver les coordonnées GPS ?</strong><br>
                        <small>
                            1. Allez sur <a href="https://www.google.com/maps" target="_blank">Google Maps</a><br>
                            2. Cherchez votre adresse<br>
                            3. Clic droit sur le marqueur → Copier les coordonnées<br>
                            4. Format: Latitude,Longitude (ex: 4.0511, 9.7679)
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Aperçu de la Carte</label>
                        <div id="map-preview" style="height: 300px; border-radius: 8px; border: 2px solid #dee2e6;"></div>
                    </div>

                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour aperçus -->
<script>
// Aperçu Logo
document.getElementById('site_logo')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('logo-img').src = event.target.result;
            document.getElementById('logo-preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Aperçu Favicon
document.getElementById('site_favicon')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('favicon-img').src = event.target.result;
            document.getElementById('favicon-preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Aperçu Carte
document.addEventListener('DOMContentLoaded', function() {
    const latInput = document.getElementById('latitude');
    const lonInput = document.getElementById('longitude');
    
    function updateMap() {
        const lat = parseFloat(latInput?.value) || 4.0511;
        const lon = parseFloat(lonInput?.value) || 9.7679;
        
        if (window.mapPreview) {
            window.mapPreview.remove();
        }
        
        // Charger Leaflet
        if (typeof L === 'undefined') {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css';
            document.head.appendChild(link);
            
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js';
            script.onload = function() {
                createMap(lat, lon);
            };
            document.head.appendChild(script);
        } else {
            createMap(lat, lon);
        }
    }
    
    function createMap(lat, lon) {
        window.mapPreview = L.map('map-preview').setView([lat, lon], 14);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(window.mapPreview);
        
        L.marker([lat, lon]).addTo(window.mapPreview).bindPopup('Votre localisation').openPopup();
        
        setTimeout(() => window.mapPreview.invalidateSize(), 100);
    }
    
    updateMap();
    latInput?.addEventListener('change', updateMap);
    lonInput?.addEventListener('change', updateMap);
});
</script>
