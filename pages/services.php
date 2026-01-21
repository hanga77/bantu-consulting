<?php
include 'templates/header.php';

$services = $pdo->query("SELECT * FROM services ORDER BY created_at DESC")->fetchAll();
?>

<div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="display-4 fw-bold mb-2"><?php echo __('services.title'); ?></h1>
        <p class="text-muted fs-6"><?php echo __('services.subtitle'); ?></p>
    </div>
    
    <?php if (!empty($services)): ?>
    <div class="row g-4">
        <?php foreach ($services as $index => $service): ?>
        <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo ($index % 2) * 100; ?>">
            <a href="?page=service-detail&id=<?php echo $service['id']; ?>" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm card-hover">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-briefcase fa-3x" style="color: var(--primary);"></i>
                        </div>
                        <h3 class="card-title fw-bold"><?php echo htmlspecialchars($service['title']); ?></h3>
                        <p class="card-text text-muted"><?php echo htmlspecialchars(substr($service['description'], 0, 120)) . '...'; ?></p>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <span class="text-primary fw-bold">
                            <?php echo __('services.learn_more'); ?> <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="row mt-5 pt-5 border-top">
        <div class="col-md-12">
            <div class="text-center" data-aos="zoom-in">
                <h2 class="display-5 fw-bold mb-4"><?php echo __('services.need'); ?></h2>
                <p class="lead mb-4 text-muted"><?php echo __('services.contact_us'); ?></p>
                <a href="?page=accueil#contact" class="btn btn-primary btn-lg"><i class="fas fa-envelope"></i> <?php echo __('home.contact'); ?></a>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-info text-center py-5">
        <i class="fas fa-info-circle"></i> Aucun service disponible
    </div>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>
