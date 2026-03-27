<?php
include 'templates/header.php';

$about = $pdo->query("SELECT * FROM about LIMIT 1")->fetch();

// Récupérer tous les membres avec département
$teams = $pdo->query("SELECT * FROM teams WHERE department_id IS NOT NULL AND department_id > 0 ORDER BY importance DESC, name")->fetchAll();

// Récupérer les membres sans département (secrétaire, accueil, etc.)
$teams_no_dept = $pdo->query("SELECT * FROM teams WHERE department_id IS NULL OR department_id = 0 ORDER BY importance DESC, name")->fetchAll();
?>

<!-- PRÉSENTATION -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6" data-aos="fade-right">
                <h1 class="display-4 fw-bold mb-4">À Propos de Bantu Consulting</h1>
                <h3 class="mb-3">Qui sommes-nous ?</h3>
                <p class="lead">
                    <?php echo nl2br(htmlspecialchars($about['description'] ?? '')); ?>
                </p>
                <h3 class="mt-5 mb-3">Notre Devise</h3>
                <p class="fs-5 text-muted fst-italic">
                    <i class="fas fa-quote-left"></i> <strong>"<?php echo htmlspecialchars($about['motto'] ?? ''); ?>"</strong> <i class="fas fa-quote-right"></i>
                </p>
                <div class="mt-4">
                    <a href="?page=equipes" class="btn btn-primary btn-lg"><i class="fas fa-users"></i> Voir nos équipes</a>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5" style="background: linear-gradient(135deg, var(--primary) 0%, #1e3a8a 100%); color: white;">
                        <h5 class="card-title mb-4"><i class="fas fa-building"></i> Bantu Consulting</h5>
                        <p class="card-text mb-3">Leader du conseil en stratégie et transformation digitale.</p>
                        <hr class="bg-light">
                        <p class="mb-2"><strong><i class="fas fa-check-circle"></i> Mission</strong></p>
                        <p class="text-light mb-3"><?php echo htmlspecialchars($about['mission'] ?? 'Accompagner les entreprises dans leur transformation et croissance.'); ?></p>
                        <p class="mb-2"><strong><i class="fas fa-check-circle"></i> Vision</strong></p>
                        <p class="text-light mb-0"><?php echo htmlspecialchars($about['vision'] ?? 'Être le partenaire de confiance pour la réussite de vos projets.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ÉQUIPE COMPLÈTE -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-4 fw-bold mb-2">Notre Équipe</h2>
            <p class="text-muted fs-6">Rencontrez les experts qui composent Bantu Consulting</p>
        </div>
        
        <?php if (!empty($teams)): ?>
        <div class="row g-4 justify-content-center">
            <?php foreach ($teams as $index => $member): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo ($index % 4) * 100; ?>">
                <div class="card h-100 border-0 shadow-sm card-hover team-card-circular">
                    <div class="team-info-overlay d-flex justify-content-center mb-3">
                        <?php if (!empty($member['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($member['image']); ?>" 
                             class="team-avatar" alt="<?php echo htmlspecialchars($member['name']); ?>">
                        <?php else: ?>
                        <div class="team-avatar bg-secondary text-white d-flex align-items-center justify-content-center">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-1"><?php echo htmlspecialchars($member['name']); ?></h5>
                        <p class="text-primary fw-bold mb-2"><?php echo htmlspecialchars($member['position']); ?></p>
                        <div class="mb-2">
                            <?php if (!empty($member['importance'])): ?>
                            <span class="badge bg-info me-2"><i class="fas fa-star"></i> <?php echo htmlspecialchars($member['importance']); ?></span>
                            <?php endif; ?>
                            <?php if ($member['experience'] > 0): ?>
                            <span class="badge bg-success"><i class="fas fa-clock"></i> <?php echo $member['experience']; ?> ans</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted small mb-3"><?php echo htmlspecialchars(substr($member['role'], 0, 100)); ?></p>
                        <!-- Réseaux Sociaux -->
                        <div class="social-links pt-2 border-top">
                            <?php if (!empty($member['linkedin'])): ?>
                            <a href="<?php echo htmlspecialchars($member['linkedin']); ?>" target="_blank" class="me-2" title="LinkedIn">
                                <i class="fab fa-linkedin text-info"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($member['twitter'])): ?>
                            <a href="<?php echo htmlspecialchars($member['twitter']); ?>" target="_blank" class="me-2" title="Twitter">
                                <i class="fab fa-twitter text-primary"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($member['facebook'])): ?>
                            <a href="<?php echo htmlspecialchars($member['facebook']); ?>" target="_blank" class="me-2" title="Facebook">
                                <i class="fab fa-facebook text-primary"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($member['instagram'])): ?>
                            <a href="<?php echo htmlspecialchars($member['instagram']); ?>" target="_blank" class="me-2" title="Instagram">
                                <i class="fab fa-instagram text-danger"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($member['website'])): ?>
                            <a href="<?php echo htmlspecialchars($member['website']); ?>" target="_blank" title="Site Web">
                                <i class="fas fa-globe text-success"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- MEMBRES SANS DÉPARTEMENT (Fonctions Support/Transversales) -->
<?php if (!empty($teams_no_dept)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-3">Équipe Support & Transversale</h2>
            <p class="text-muted fs-6">Nos collaborateurs en fonctions transversales</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($teams_no_dept as $index => $member): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo ($index % 4) * 100; ?>">
                <div class="card h-100 border-0 shadow-sm card-hover team-card-circular">
                    <div class="team-info-overlay d-flex justify-content-center mb-3">
                        <?php if (!empty($member['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($member['image']); ?>" 
                             class="team-avatar" alt="<?php echo htmlspecialchars($member['name']); ?>">
                        <?php else: ?>
                        <div class="team-avatar bg-secondary text-white d-flex align-items-center justify-content-center">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-1"><?php echo htmlspecialchars($member['name']); ?></h5>
                        <p class="text-primary fw-bold mb-2"><?php echo htmlspecialchars($member['position']); ?></p>
                        <div class="mb-2">
                            <?php if (!empty($member['importance'])): ?>
                            <span class="badge bg-warning text-dark"><i class="fas fa-star"></i> <?php echo htmlspecialchars($member['importance']); ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted small mb-3"><?php echo htmlspecialchars(substr($member['role'] ?? '', 0, 100)); ?></p>
                        <!-- Réseaux Sociaux -->
                        <div class="social-links pt-2 border-top">
                            <?php if (!empty($member['linkedin'])): ?>
                            <a href="<?php echo htmlspecialchars($member['linkedin']); ?>" target="_blank" class="me-2" title="LinkedIn">
                                <i class="fab fa-linkedin text-info"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($member['twitter'])): ?>
                            <a href="<?php echo htmlspecialchars($member['twitter']); ?>" target="_blank" class="me-2" title="Twitter">
                                <i class="fab fa-twitter text-primary"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($member['facebook'])): ?>
                            <a href="<?php echo htmlspecialchars($member['facebook']); ?>" target="_blank" class="me-2" title="Facebook">
                                <i class="fab fa-facebook text-primary"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($member['instagram'])): ?>
                            <a href="<?php echo htmlspecialchars($member['instagram']); ?>" target="_blank" class="me-2" title="Instagram">
                                <i class="fab fa-instagram text-danger"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($member['website'])): ?>
                            <a href="<?php echo htmlspecialchars($member['website']); ?>" target="_blank" title="Site Web">
                                <i class="fas fa-globe text-success"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php include 'templates/footer.php'; ?>