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
                        <p class="text-light mb-3">Accompagner les entreprises dans leur transformation et croissance.</p>
                        <p class="mb-2"><strong><i class="fas fa-check-circle"></i> Vision</strong></p>
                        <p class="text-light mb-0">Être le partenaire de confiance pour la réussite de vos projets.</p>
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
        <div class="row g-4">
            <?php foreach ($teams as $index => $member): ?>
            <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo ($index % 2) * 100; ?>">
                <div class="card h-100 border-0 shadow-sm card-hover overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-4 position-relative">
                            <?php if (!empty($member['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($member['image']); ?>" 
                                 class="img-fluid h-100" alt="<?php echo htmlspecialchars($member['name']); ?>" 
                                 style="object-fit: cover; min-height: 280px;">
                            <?php else: ?>
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center h-100" style="min-height: 280px;">
                                <i class="fas fa-user fa-4x"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body d-flex flex-column justify-content-center h-100">
                                <h5 class="card-title fw-bold mb-2"><?php echo htmlspecialchars($member['name']); ?></h5>
                                <p class="card-text mb-3">
                                    <strong class="d-block text-primary mb-1"><?php echo htmlspecialchars($member['position']); ?></strong>
                                    <span class="badge bg-info me-2"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($member['importance']); ?></span>
                                    <?php if ($member['experience'] > 0): ?>
                                    <span class="badge bg-success"><i class="fas fa-clock"></i> <?php echo $member['experience']; ?> ans d'exp</span>
                                    <?php endif; ?>
                                </p>
                                <p class="text-muted small mb-0"><?php echo htmlspecialchars(substr($member['role'], 0, 120)); ?></p>
                            </div>
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

        <div class="row g-4">
            <?php foreach ($teams_no_dept as $index => $member): ?>
            <div class="col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3) * 100; ?>">
                <div class="card h-100 border-0 shadow-sm card-hover overflow-hidden">
                    <div class="position-relative overflow-hidden">
                        <?php if (!empty($member['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($member['image']); ?>" 
                             class="card-img-top w-100" alt="<?php echo htmlspecialchars($member['name']); ?>" 
                             style="height: 250px; object-fit: cover; transition: transform 0.3s;">
                        <?php else: ?>
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center w-100" style="height: 250px;">
                            <i class="fas fa-user fa-4x"></i>
                        </div>
                        <?php endif; ?>
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                             style="background: rgba(30, 64, 175, 0.85); opacity: 0; transition: opacity 0.3s;">
                            <div class="text-center text-white">
                                <i class="fas fa-search-plus fa-3x mb-2"></i>
                                <p class="mb-0 fw-bold">Voir profil</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title fw-bold mb-2"><?php echo htmlspecialchars($member['name']); ?></h5>
                        <p class="card-text mb-3">
                            <strong class="d-block text-primary mb-1"><?php echo htmlspecialchars($member['position']); ?></strong>
                            <?php if (!empty($member['importance'])): ?>
                            <span class="badge bg-warning text-dark"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($member['importance']); ?></span>
                            <?php endif; ?>
                        </p>
                        <p class="text-muted small mb-0"><?php echo htmlspecialchars(substr($member['role'] ?? '', 0, 100)); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php include 'templates/footer.php'; ?>