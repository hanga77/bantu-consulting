<?php
include 'templates/header.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch();

if (!$project) {
    echo '<div class="container py-5"><div class="alert alert-warning text-center"><h5>Projet non trouvé</h5></div></div>';
    include 'templates/footer.php';
    exit;
}

// Récupérer les images du projet
$stmt = $pdo->prepare("SELECT * FROM project_images WHERE project_id = ? ORDER BY order_pos");
$stmt->execute([$id]);
$images = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM project_members WHERE project_id = ?");
$stmt->execute([$id]);
$members = $stmt->fetchAll();
?>

<div class="container py-5">
    <nav aria-label="Fil d'ariane" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?page=accueil">Accueil</a></li>
            <li class="breadcrumb-item"><a href="?page=projets">Projets</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($project['title']); ?></li>
        </ol>
    </nav>
    <a href="?page=projets" class="btn btn-secondary mb-4"><i class="fas fa-arrow-left"></i> Retour aux projets</a>
    
    <div class="row" data-aos="fade-up">
        <div class="col-md-8">
            <h1 class="display-4 fw-bold mb-3"><?php echo htmlspecialchars($project['title']); ?></h1>
            
            <!-- Image principale -->
            <?php if (!empty($project['image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($project['image']); ?>"
                 class="img-fluid rounded mb-4 shadow-lg"
                 alt="<?php echo htmlspecialchars($project['title']); ?>"
                 fetchpriority="high"
                 style="max-height: 500px; object-fit: cover; width: 100%;">
            <?php endif; ?>
            
            <h3 class="mt-4 mb-3">Description</h3>
            <p class="lead"><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>

            <!-- Galerie d'images -->
            <?php if (!empty($images)): ?>
            <h3 class="mt-5 mb-4">Galerie du projet</h3>
            <div class="row g-3">
                <?php foreach ($images as $index => $image): ?>
                <div class="col-md-4">
                    <a href="uploads/<?php echo htmlspecialchars($image['image']); ?>" data-lightbox="project-gallery" data-title="<?php echo htmlspecialchars($image['title'] ?? ''); ?>">
                        <div class="card card-hover border-0 shadow-sm overflow-hidden">
                            <img src="uploads/<?php echo htmlspecialchars($image['image']); ?>"
                                 class="card-img-top"
                                 alt="<?php echo htmlspecialchars($image['title'] ?? $project['title']); ?>"
                                 loading="lazy"
                                 style="height: 200px; object-fit: cover;">
                            <?php if (!empty($image['title'])): ?>
                            <div class="card-body">
                                <h6 class="card-title"><?php echo htmlspecialchars($image['title']); ?></h6>
                            </div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-4" data-aos="fade-left">
            <!-- Informations -->
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        <strong>Statut :</strong><br>
                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($project['status']); ?></span>
                    </p>
                    <?php if (!empty($project['start_date'])): ?>
                    <p class="mb-3">
                        <strong><i class="far fa-calendar"></i> Début :</strong><br>
                        <?php echo date('d/m/Y', strtotime($project['start_date'])); ?>
                    </p>
                    <?php endif; ?>
                    <?php if (!empty($project['end_date'])): ?>
                    <p class="mb-0">
                        <strong><i class="far fa-calendar"></i> Fin :</strong><br>
                        <?php echo date('d/m/Y', strtotime($project['end_date'])); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Équipe -->
            <?php if (!empty($members)): ?>
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Équipe de réalisation</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($members as $member): ?>
                        <div class="list-group-item px-0 py-3 border-bottom">
                            <h6 class="mb-1"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($member['member_name']); ?></h6>
                            <p class="small text-muted mb-0"><?php echo htmlspecialchars($member['role']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
