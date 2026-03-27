<?php
$settings = getSiteSettings();
$services = getFooterServices();
?>
</main>

<!-- Scroll to Top Button -->
<button id="scrollToTop" type="button" title="Retour au haut" aria-label="Retour en haut de la page">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Newsletter Section -->
<section class="py-5" style="background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); color: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6" data-aos="fade-right">
                <h2 class="mb-3"><i class="fas fa-envelope"></i> Restez Informé</h2>
                <p class="lead mb-0">Abonnez-vous à notre newsletter pour recevoir les dernières actualités et offres exclusives.</p>
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <form id="newsletter-form" class="d-flex gap-2">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                    <input type="text" class="form-control" name="name" placeholder="Votre nom (optionnel)">
                    <input type="email" class="form-control" name="email" placeholder="Votre email" required>
                    <button type="submit" class="btn btn-warning fw-bold">S'abonner</button>
                </form>
                <small class="text-light d-block mt-2" id="newsletter-message"></small>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-light py-5">
    <div class="container">
        <div class="row g-4 mb-4">
            <!-- About -->
            <div class="col-md-3">
                <h5 class="mb-3"><i class="fas fa-building"></i> Bantu Consulting</h5>
                <p class="text-light-50"></p>
            </div>
            
            <!-- Quick Links -->
            <div class="col-md-3">
                <h5 class="mb-3"><i class="fas fa-link"></i> Liens Rapides</h5>
                <ul class="list-unstyled">
                    <li><a href="?page=accueil" class="text-light-50 text-decoration-none">Accueil</a></li>
                    <li><a href="?page=services" class="text-light-50 text-decoration-none">Services</a></li>
                    <li><a href="?page=projets" class="text-light-50 text-decoration-none">Projets</a></li>
                    <li><a href="?page=equipes" class="text-light-50 text-decoration-none">Équipes</a></li>
                    <li><a href="?page=apropos" class="text-light-50 text-decoration-none">À Propos</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-shield"></i> <?php echo __('nav.admin'); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="?page=admin-dashboard">
                            <i class="fas fa-tachometer-alt"></i> <?php echo __('admin.dashboard'); ?>
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="actions/logout.php">
                            <i class="fas fa-sign-out-alt"></i> <?php echo __('nav.logout'); ?>
                        </a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="?page=admin-login">
                        <i class="fas fa-lock"></i> <?php echo __('nav.admin'); ?>
                    </a>
                </li>
                <?php endif; ?>
                </ul>
            </div>
            
            <!-- Services -->
            <div class="col-md-3">
                <h5 class="mb-3"><i class="fas fa-cogs"></i> Services</h5>
                <ul class="list-unstyled">
                    <?php foreach ($services as $service): ?>
                    <li><a href="?page=service-detail&id=<?php echo $service['id']; ?>" class="text-light-50 text-decoration-none"><?php echo htmlspecialchars($service['title']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Contact -->
            <div class="col-md-3">
                <h5 class="mb-3"><i class="fas fa-phone"></i> Contact</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>" class="text-light-50 text-decoration-none">
                            <?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>
                        </a>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone"></i>
                        <a href="tel:<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>" class="text-light-50 text-decoration-none">
                            <?php echo htmlspecialchars($settings['phone'] ?? ''); ?>
                        </a>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="text-light-50"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></span>
                    </li>
                </ul>
            </div>
            
            <!-- Webmail & Social -->
            <div class="col-md-3">
                <h5 class="mb-3"><i class="fas fa-envelope-open"></i> Webmail</h5>
                <p class="mb-3">
                    <a href="https://mail.bantu-consulting.com" class="btn btn-sm btn-outline-light" target="_blank">
                        <i class="fas fa-mail-bulk"></i> Accéder au Webmail
                    </a>
                </p>
                
                <h5 class="mb-3"><i class="fas fa-share-alt"></i> Suivez-nous</h5>
                <div class="d-flex gap-2 flex-wrap">
                    <?php if (!empty($settings['facebook_url'])): ?>
                    <a href="<?php echo htmlspecialchars($settings['facebook_url']); ?>" class="btn btn-sm btn-outline-light" target="_blank" title="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['twitter_url'])): ?>
                    <a href="<?php echo htmlspecialchars($settings['twitter_url']); ?>" class="btn btn-sm btn-outline-light" target="_blank" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['linkedin_url'])): ?>
                    <a href="<?php echo htmlspecialchars($settings['linkedin_url']); ?>" class="btn btn-sm btn-outline-light" target="_blank" title="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['instagram_url'])): ?>
                    <a href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" class="btn btn-sm btn-outline-light" target="_blank" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <hr class="bg-light-50">
        
        <!-- Copyright -->
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-light-50 mb-0"><?php echo htmlspecialchars($settings['footer_text'] ?? '© 2024 Bantu Consulting'); ?></p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="text-light-50 mb-0">
                    Développé avec <i class="fas fa-heart text-danger"></i> par 
                    <a href="#" class="text-light text-decoration-none">Hang7</a>
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"
        integrity="sha384-jN3Qe7xtdnQxdyZWXD9gn2Z4oRyiFuU03xQATriSicCq2SKDikN9UEJMCx+9nsJM"
        crossorigin="anonymous"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"
        integrity="sha384-wziAfh6b/qT+3LrqebF9WeK4+J5sehS6FA10J1t3a866kJ/fvU5UwofWnQyzLtwu"
        crossorigin="anonymous"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true
    });

    // Newsletter Form
    document.getElementById('newsletter-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const messageDiv = document.getElementById('newsletter-message');
        const submitBtn = this.querySelector('button');
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Inscription en cours...';
        
        try {
            const response = await fetch('actions/subscribe-newsletter.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                messageDiv.textContent = '✅ ' + data.message;
                messageDiv.style.color = '#90EE90';
                this.reset();
                setTimeout(() => {
                    messageDiv.textContent = '';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'S\'abonner';
                }, 3000);
            } else {
                messageDiv.textContent = '❌ ' + data.message;
                messageDiv.style.color = '#FF6B6B';
                submitBtn.disabled = false;
                submitBtn.textContent = 'S\'abonner';
            }
        } catch (error) {
            messageDiv.textContent = '❌ Erreur de connexion';
            messageDiv.style.color = '#FF6B6B';
            submitBtn.disabled = false;
            submitBtn.textContent = 'S\'abonner';
        }
    });
</script>
</body>
</html>
