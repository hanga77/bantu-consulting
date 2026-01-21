<?php
$settings = getSiteSettings();
?>
</main>

<!-- Scroll to Top Button -->
<button id="scrollToTop" type="button" title="Retour au haut">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Footer -->
<footer class="mt-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-3">
                <h5><i class="fas fa-building text-accent"></i> <?php echo htmlspecialchars($settings['site_name'] ?? 'Bantu'); ?></h5>
                <p class="text-muted">
                    <?php echo htmlspecialchars(substr($settings['site_description'] ?? '', 0, 100)); ?>...
                </p>
                <div class="mt-3">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="col-md-3">
                <h5><i class="fas fa-link text-accent"></i> Liens Rapides</h5>
                <ul class="list-unstyled">
                    <li><a href="?page=accueil">Accueil</a></li>
                    <li><a href="?page=projets">Nos Projets</a></li>
                    <li><a href="?page=services">Services</a></li>
                    <li><a href="?page=equipes">Équipes</a></li>
                    <li><a href="?page=apropos">À Propos</a></li>
                </ul>
            </div>
            
            <div class="col-md-3">
                <h5><i class="fas fa-cog text-accent"></i> Services</h5>
                <ul class="list-unstyled">
                    <li><a href="?page=services">Conseil Stratégique</a></li>
                    <li><a href="?page=services">Transformation Digitale</a></li>
                    <li><a href="?page=services">Optimisation Logistique</a></li>
                    <li><a href="?page=services">Cybersécurité</a></li>
                </ul>
            </div>
            
            <div class="col-md-3">
                <h5><i class="fas fa-phone text-accent"></i> Contact</h5>
                <p>
                    <strong>Email:</strong><br>
                    <a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
                        <?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>
                    </a>
                </p>
                <p>
                    <strong>Téléphone:</strong><br>
                    <?php echo htmlspecialchars($settings['phone'] ?? ''); ?>
                </p>
                <p>
                    <strong>Adresse:</strong><br>
                    <?php echo htmlspecialchars($settings['address'] ?? ''); ?>
                </p>
            </div>
        </div>
        
        <hr class="bg-secondary my-4">
        
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">
                    <?php echo htmlspecialchars($settings['footer_text'] ?? ''); ?>
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="text-muted">
                    Développé avec <i class="fas fa-heart text-danger"></i> par Bantu Consulting
                </small>
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
