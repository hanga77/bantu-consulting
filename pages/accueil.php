<?php
include 'templates/header.php';

$carousel = $pdo->query("SELECT * FROM carousel ORDER BY order_pos ASC")->fetchAll();
$about = $pdo->query("SELECT motto FROM about LIMIT 1")->fetch();
$services = $pdo->query("SELECT * FROM services LIMIT 6")->fetchAll();
$settings = getSiteSettings();
?>

<!-- CARROUSEL HERO -->
<div class="container-fluid p-0">
    <div id="carouselHero" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php if (!empty($carousel)): ?>
                <?php foreach ($carousel as $index => $item): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" style="height: 600px;">
                    <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>"
                         class="d-block w-100 h-100"
                         alt="<?php echo htmlspecialchars($item['title']); ?>"
                         <?php echo $index > 0 ? 'loading="lazy"' : 'fetchpriority="high"'; ?>
                         style="object-fit: cover;">
                    <div class="carousel-caption d-md-block">
                        <h1 class="display-3 fw-bold" data-aos="fade-up"><?php echo htmlspecialchars($item['title']); ?></h1>
                        <p class="lead mt-3" data-aos="fade-up" data-aos-delay="100"><?php echo htmlspecialchars($item['description']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="carousel-item active" style="height: 600px; background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); display: flex; align-items: center; justify-content: center; color: white;">
                    <div class="text-center">
                        <h1 class="display-3 fw-bold mb-4"><?php echo __('home.title'); ?></h1>
                        <p class="lead fs-5 mb-4"><?php echo __('home.subtitle'); ?></p>
                        <a href="#contact" class="btn btn-light btn-lg"><?php echo __('home.contact_btn'); ?></a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- DEVISE -->
<section class="py-5 bg-light border-top">
    <div class="container text-center">
        <div data-aos="zoom-in">
            <h2 class="mb-4 fw-bold"><?php echo __('home.our_motto'); ?></h2>
            <p class="lead fs-5 text-muted" style="font-style: italic; font-size: 1.3em;">
                <i class="fas fa-quote-left"></i> <?php echo htmlspecialchars($about['motto'] ?? 'Votre succès est notre mission'); ?> <i class="fas fa-quote-right"></i>
            </p>
        </div>
    </div>
</section>

<!-- VIDÉO DE PRÉSENTATION - VERSION FINALE CORRIGÉE -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-2"><?php echo __('home.presentation'); ?></h2>
            <p class="text-muted fs-6"><?php echo __('home.contact_text'); ?></p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10" data-aos="zoom-in">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="ratio ratio-16x9">
                        <?php 
                        $video_url = $settings['presentation_video'] ?? '';
                        
                        // Vérifier si c'est une vidéo YouTube
                        if (!empty($video_url) && (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false)): 
                        ?>
                            <!-- YouTube Video -->
                            <iframe 
                                src="<?php echo htmlspecialchars($video_url); ?>" 
                                title="Présentation Bantu Consulting" 
                                allowfullscreen="" 
                                loading="lazy" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share">
                            </iframe>
                        <?php elseif (!empty($video_url)): ?>
                            <!-- Local Video -->
                            <video controls style="width: 100%; height: 100%; object-fit: cover;" controlsList="nodownload">
                                <!-- Le chemin est déjà correct depuis la racine : uploads/videos/xxx.mp4 -->
                                <source src="<?php echo htmlspecialchars($video_url); ?>" type="video/mp4">
                                <source src="<?php echo htmlspecialchars($video_url); ?>" type="video/webm">
                                Votre navigateur ne supporte pas les vidéos HTML5.
                            </video>
                        <?php else: ?>
                            <!-- Placeholder si aucune vidéo -->
                            <div class="d-flex align-items-center justify-content-center bg-light h-100">
                                <div class="text-center text-muted p-5">
                                    <i class="fas fa-video fa-5x mb-3" style="color: #ccc;"></i>
                                    <h4>Aucune vidéo de présentation</h4>
                                    <p>Ajoutez une vidéo dans les paramètres du site</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-2"><?php echo __('home.our_services'); ?></h2>
            <p class="text-muted fs-6"><?php echo __('services.subtitle'); ?></p>
        </div>
        
        <?php if (!empty($services)): ?>
        <div class="row g-4">
            <?php foreach ($services as $index => $service): ?>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                <div class="card h-100 border-0 shadow-sm card-hover">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-star fa-3x" style="color: var(--primary);"></i>
                        </div>
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($service['title']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars(substr($service['description'], 0, 80)) . '...'; ?></p>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <a href="?page=services" class="btn btn-sm btn-outline-primary w-100"><?php echo __('services.learn_more'); ?></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="?page=services" class="btn btn-primary btn-lg"><i class="fas fa-arrow-right"></i> <?php echo __('home.see_all_services'); ?></a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- SECTION CARTE - LOCALISATION -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                <div class="mb-4">
                    <h2 class="display-5 fw-bold mb-3">📍 Où nous trouver ?</h2>
                    <p class="lead text-muted">Visitez-nous à notre siège social à : </p>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="fas fa-map-marker-alt text-danger"></i> Adresse</h5>
                        <p class="mb-0"><?php echo htmlspecialchars($settings['address'] ?? 'Kinshasa, RDC'); ?></p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="fas fa-phone text-primary"></i> Téléphone</h5>
                        <p class="mb-0">
                            <a href="tel:<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($settings['phone'] ?? ''); ?>
                            </a>
                        </p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="fas fa-envelope text-success"></i> Email</h5>
                        <p class="mb-0">
                            <a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <!-- Carte Leaflet -->
                <div id="map" style="height: 450px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); overflow: hidden;"></div>
            </div>
        </div>
    </div>
</section>

<!-- Scripts Leaflet -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Coordonnées de la structure
    const latitude = parseFloat('<?php echo $settings["latitude"] ?? "4.0511"; ?>');
    const longitude = parseFloat('<?php echo $settings["longitude"] ?? "9.7679"; ?>');
    const address = '<?php echo htmlspecialchars($settings["address"] ?? "Kinshasa"); ?>';
    
    // Créer la carte
    const map = L.map('map').setView([latitude, longitude], 14);
    
    // Ajouter la couche de tuiles OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Ajouter un marqueur
    const marker = L.marker([latitude, longitude], {
        icon: L.icon({
            iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map);
    
    // Ajouter une popup au marqueur
    marker.bindPopup(`
        <div style="font-family: Poppins;">
            <strong>Bantu Consulting</strong><br>
            ${address}<br>
            <a href="https://www.google.com/maps?q=${latitude},${longitude}" target="_blank" class="btn btn-sm btn-primary mt-2">
                Voir sur Google Maps
            </a>
        </div>
    `).openPopup();
    
    // Redimensionner la carte au chargement
    setTimeout(function() {
        map.invalidateSize();
    }, 100);
});
</script>

<!-- APPEL À L'ACTION -->
<section class="py-5 bg-gradient">
    <div class="container text-center" data-aos="zoom-in">
        <h2 class="display-5 fw-bold mb-4 text-white"><?php echo __('home.ready'); ?></h2>
        <p class="lead mb-4 text-white"><?php echo __('home.ready_text'); ?></p>
        <a href="#contact" class="btn btn-light btn-lg"><i class="fas fa-envelope"></i> <?php echo __('home.contact'); ?></a>
    </div>
</section>

<!-- FORMULAIRE DE CONTACT -->
<section class="py-5 bg-light" id="contact">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-2"><?php echo __('home.contact'); ?></h2>
            <p class="text-muted fs-6"><?php echo __('home.contact_text'); ?></p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-md-6" data-aos="fade-up">
                <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <form method="POST" action="actions/send-contact.php" id="contactForm">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold"><?php echo __('home.fullname'); ?></label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold"><?php echo __('home.email'); ?></label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label fw-bold"><?php echo __('home.message'); ?></label>
                                <textarea class="form-control form-control-lg" id="message" name="message" rows="5" required placeholder="<?php echo __('home.describe'); ?>"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-paper-plane"></i> <?php echo __('home.send'); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'templates/footer.php'; ?>
