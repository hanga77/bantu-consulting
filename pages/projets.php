<?php
include 'templates/header.php';

$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
?>

<div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="display-4 fw-bold mb-2">Nos Projets</h1>
        <p class="text-muted fs-6">Découvrez les projets que nous avons réalisés pour nos clients</p>
    </div>
    
    <?php if (!empty($projects)): ?>
    <!-- Vue Galerie -->
    <div class="mb-4 text-end">
        <button class="btn btn-outline-primary btn-sm" onclick="toggleView('gallery')">
            <i class="fas fa-th"></i> Galerie
        </button>
        <button class="btn btn-outline-primary btn-sm" onclick="toggleView('list')">
            <i class="fas fa-list"></i> Liste
        </button>
    </div>

    <!-- GALERIE -->
    <div id="gallery-view" class="row g-4">
        <?php foreach ($projects as $index => $project): ?>
        <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3) * 100; ?>">
            <a href="#" data-bs-toggle="modal" data-bs-target="#projectModal<?php echo $project['id']; ?>" class="text-decoration-none">
                <div class="card h-100 shadow-sm card-hover border-0 overflow-hidden">
                    <div class="position-relative gallery-item">
                        <?php if (!empty($project['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($project['image']); ?>" class="card-img-top gallery-image" alt="<?php echo htmlspecialchars($project['title']); ?>" style="height: 250px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="gallery-overlay">
                            <div class="overlay-content">
                                <i class="fas fa-search-plus fa-2x text-white"></i>
                            </div>
                        </div>
                        <span class="badge position-absolute top-2 end-2 bg-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($project['status']); ?></span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($project['title']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars(substr($project['short_description'], 0, 80)); ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted"><i class="far fa-calendar"></i> <?php echo date('m/Y', strtotime($project['created_at'])); ?></small>
                            <span class="text-primary fw-bold">Voir <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- LISTE (cachée par défaut) -->
    <div id="list-view" class="d-none">
        <div class="row">
            <?php foreach ($projects as $project): ?>
            <div class="col-md-12 mb-3">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="row g-0">
                        <div class="col-md-3">
                            <?php if (!empty($project['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($project['image']); ?>" class="img-fluid h-100" alt="" style="object-fit: cover; min-height: 200px;">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($project['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($project['short_description']); ?></p>
                                <a href="?page=projet-detail&id=<?php echo $project['id']; ?>" class="btn btn-sm btn-primary">Voir détails</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modales pour galerie -->
    <?php foreach ($projects as $project): ?>
    <div class="modal fade" id="projectModal<?php echo $project['id']; ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($project['title']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($project['image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($project['image']); ?>" class="img-fluid mb-3" alt="">
                    <?php endif; ?>
                    <p><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
                    <p><strong>Statut:</strong> <?php echo htmlspecialchars($project['status']); ?></p>
                </div>
                <div class="modal-footer">
                    <a href="?page=projet-detail&id=<?php echo $project['id']; ?>" class="btn btn-primary">Voir le projet complet</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <?php else: ?>
    <div class="alert alert-info text-center py-5">
        <h5>Aucun projet disponible</h5>
    </div>
    <?php endif; ?>
</div>

<!-- Styles Galerie -->
<style>
.gallery-item {
    overflow: hidden;
    cursor: pointer;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(30, 64, 175, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-image {
    transition: transform 0.3s ease;
}

.gallery-item:hover .gallery-image {
    transform: scale(1.05);
}
</style>

<script>
function toggleView(view) {
    document.getElementById('gallery-view').classList.toggle('d-none', view === 'list');
    document.getElementById('list-view').classList.toggle('d-none', view === 'gallery');
}
</script>

<?php include 'templates/footer.php'; ?>
