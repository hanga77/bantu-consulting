<?php
include 'templates/header.php';

$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
?>

<div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="display-4 fw-bold mb-3">Nos Projets</h1>
        <p class="text-muted fs-5">Découvrez nos réalisations et expertises</p>
    </div>

    <?php if (!empty($projects)): ?>
    <div class="row g-4">
        <?php foreach ($projects as $index => $project): ?>
        <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3) * 100; ?>">
            <div class="card h-100 border-0 shadow-lg card-hover">
                <?php if (!empty($project['image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($project['image']); ?>" class="card-img-top" alt="" style="height: 250px; object-fit: cover;">
                <?php endif; ?>
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold mb-3"><?php echo htmlspecialchars($project['title']); ?></h5>
                    
                    <?php if (!empty($project['short_description'])): ?>
                    <p class="card-text text-muted mb-3"><?php echo htmlspecialchars($project['short_description']); ?></p>
                    <?php endif; ?>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-<?php echo $project['status'] === 'terminé' ? 'success' : ($project['status'] === 'en cours' ? 'primary' : 'warning'); ?>">
                                <?php echo ucfirst($project['status']); ?>
                            </span>
                            <?php if (!empty($project['start_date'])): ?>
                            <small class="text-muted">
                                <i class="far fa-calendar"></i> <?php echo date('m/Y', strtotime($project['start_date'])); ?>
                            </small>
                            <?php endif; ?>
                        </div>
                        
                        <a href="?page=projet-detail&id=<?php echo $project['id']; ?>" class="btn btn-primary w-100">
                            <i class="fas fa-eye"></i> Voir le projet
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
        <h3 class="text-muted">Aucun projet disponible</h3>
        <p class="text-muted">Nos projets seront bientôt publiés ici.</p>
    </div>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>
