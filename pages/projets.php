<?php
include 'templates/header.php';

$per_page = 12;
$page_num = max(1, intval($_GET['p'] ?? 1));
$offset   = ($page_num - 1) * $per_page;

$total       = (int) $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$total_pages = (int) ceil($total / $per_page);
$page_num    = min($page_num, max(1, $total_pages));

$stmt = $pdo->prepare("SELECT * FROM projects ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $per_page, PDO::PARAM_INT);
$stmt->bindValue(2, $offset,   PDO::PARAM_INT);
$stmt->execute();
$projects = $stmt->fetchAll();
?>

<div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="display-4 fw-bold mb-3">Nos Projets</h1>
        <p class="text-muted fs-5">Découvrez nos réalisations et expertises</p>
        <?php if ($total > 0): ?>
        <p class="text-muted small"><?php echo $total; ?> projet<?php echo $total > 1 ? 's' : ''; ?> au total</p>
        <?php endif; ?>
    </div>

    <?php if (!empty($projects)): ?>
    <div class="row g-4">
        <?php foreach ($projects as $index => $project): ?>
        <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3) * 100; ?>">
            <div class="card h-100 border-0 shadow-lg card-hover">
                <?php if (!empty($project['image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($project['image']); ?>"
                     class="card-img-top"
                     alt="<?php echo htmlspecialchars($project['title']); ?>"
                     loading="lazy"
                     style="height: 250px; object-fit: cover;">
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
                                <i class="far fa-calendar" aria-hidden="true"></i>
                                <?php echo date('m/Y', strtotime($project['start_date'])); ?>
                            </small>
                            <?php endif; ?>
                        </div>

                        <a href="?page=projet-detail&id=<?php echo $project['id']; ?>" class="btn btn-primary w-100">
                            <i class="fas fa-eye" aria-hidden="true"></i> Voir le projet
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1): ?>
    <nav aria-label="Pagination projets" class="mt-5">
        <ul class="pagination justify-content-center flex-wrap gap-1">
            <li class="page-item <?php echo $page_num <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=projets&p=<?php echo $page_num - 1; ?>" aria-label="Page précédente">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                </a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo $i === $page_num ? 'active' : ''; ?>">
                <a class="page-link" href="?page=projets&p=<?php echo $i; ?>"
                   <?php echo $i === $page_num ? 'aria-current="page"' : ''; ?>>
                    <?php echo $i; ?>
                </a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?php echo $page_num >= $total_pages ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=projets&p=<?php echo $page_num + 1; ?>" aria-label="Page suivante">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                </a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>

    <?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-briefcase fa-3x text-muted mb-3" aria-hidden="true"></i>
        <h3 class="text-muted">Aucun projet disponible</h3>
        <p class="text-muted">Nos projets seront bientôt publiés ici.</p>
    </div>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>
