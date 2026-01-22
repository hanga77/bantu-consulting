<?php
$settings = getSiteSettings();
$services = $pdo->query("SELECT id, title FROM services ORDER BY created_at DESC LIMIT 4")->fetchAll();
?>
</main>

<!-- Scroll to Top Button -->
<button id="scrollToTop" type="button" title="Retour au haut">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Footer -->
<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-4">
                <h5><i class="fas fa-building text-warning"></i> <?php echo htmlspecialchars($settings['site_name'] ?? 'Bantu'); ?></h5>
                <p class="text-muted">
                    <?php echo htmlspecialchars(substr($settings['site_description'] ?? '', 0, 100)); ?>...
                </p>
                <div class="social-links">
                    <a href="<?php echo htmlspecialchars($settings['facebook_url'] ?? '#'); ?>" class="text-white me-3" title="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="<?php echo htmlspecialchars($settings['twitter_url'] ?? '#'); ?>" class="text-white me-3" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="<?php echo htmlspecialchars($settings['linkedin_url'] ?? '#'); ?>" class="text-white me-3" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    <a href="<?php echo htmlspecialchars($settings['instagram_url'] ?? '#'); ?>" class="text-white" title="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <h5><i class="fas fa-link text-warning"></i> Liens Rapides</h5>
                <ul class="list-unstyled">
                    <li><a href="?page=accueil" class="text-white-50 text-decoration-none">Accueil</a></li>
                    <li><a href="?page=projets" class="text-white-50 text-decoration-none">Nos Projets</a></li>
                    <li><a href="?page=services" class="text-white-50 text-decoration-none">Services</a></li>
                    <li><a href="?page=equipe" class="text-white-50 text-decoration-none">Équipes</a></li>
                    <li><a href="?page=about" class="text-white-50 text-decoration-none">À Propos</a></li>
                </ul>
            </div>
            
            <div class="col-md-3 mb-4">
                <h5><i class="fas fa-cogs text-warning"></i> Services</h5>
                <ul class="list-unstyled">
                    <?php foreach ($services as $service): ?>
                    <li><a href="?page=service-detail&id=<?php echo $service['id']; ?>" class="text-white-50 text-decoration-none"><?php echo htmlspecialchars($service['title']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="col-md-3 mb-4">
                <h5><i class="fas fa-phone text-warning"></i> Contact</h5>
                <p class="mb-2">
                    <strong>Email:</strong><br>
                    <a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>" class="text-white-50 text-decoration-none">
                        <?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>
                    </a>
                </p>
                <p class="mb-2">
                    <strong>Téléphone:</strong><br>
                    <span class="text-white-50"><?php echo htmlspecialchars($settings['phone'] ?? ''); ?></span>
                </p>
                <p>
                    <strong>Adresse:</strong><br>
                    <span class="text-white-50"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></span>
                </p>
            </div>
        </div>
        
        <hr class="bg-secondary">
        
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; 2024 Bantu Consulting. Tous droits réservés.</p>
            </div>
            <div class="col-md-6 text-end">
                <p class="mb-0">Développé avec <i class="fas fa-heart text-danger"></i> par Bantu Consulting</p>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script src="assets/script.js"></script>

</body>
</html>
