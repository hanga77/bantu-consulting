<?php
include 'templates/header.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM departments WHERE id = ?");
$stmt->execute([$id]);
$department = $stmt->fetch();

if (!$department) {
    echo '<div class="container py-5"><div class="alert alert-warning text-center"><h5>Département non trouvé</h5></div></div>';
    include 'templates/footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM teams WHERE department_id = ? ORDER BY importance DESC, name");
$stmt->execute([$id]);
$members = $stmt->fetchAll();
?>

<div class="container py-5">
    <nav aria-label="Fil d'ariane" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?page=accueil">Accueil</a></li>
            <li class="breadcrumb-item"><a href="?page=equipes">Équipes</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($department['name']); ?></li>
        </ol>
    </nav>
    <a href="?page=equipes" class="btn btn-secondary mb-4"><i class="fas fa-arrow-left"></i> Retour aux équipes</a>
    
    <!-- En-tête -->
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-3">
                <div style="width: 8px; height: 50px; background: linear-gradient(135deg, #1e40af, #f97316); border-radius: 4px; margin-right: 15px;"></div>
                <div>
                    <h1 class="display-4 fw-bold mb-0"><?php echo htmlspecialchars($department['name']); ?></h1>
                </div>
            </div>
            <p class="lead text-muted"><?php echo nl2br(htmlspecialchars($department['description'])); ?></p>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-lg bg-gradient text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> Informations</h5>
                    <p class="mb-2"><strong>Type :</strong> <?php echo htmlspecialchars($department['department_type'] ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong>Nombre de membres :</strong> <span class="badge bg-warning text-dark"><?php echo count($members); ?></span></p>
                    <p class="mb-0"><strong>Créé :</strong> <?php echo date('d/m/Y', strtotime($department['created_at'])); ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($members)): ?>
    <h2 class="mb-4 mt-5">Équipe du département</h2>
    <div class="row g-4">
        <?php foreach ($members as $index => $member): ?>
        <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo ($index % 2) * 100; ?>">
            <a href="#" data-bs-toggle="modal" data-bs-target="#memberDetailModal<?php echo $member['id']; ?>" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm card-hover overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-4 position-relative">
                            <?php if (!empty($member['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($member['image']); ?>"
                                 class="img-fluid h-100 rounded-start"
                                 alt="<?php echo htmlspecialchars($member['name']); ?>"
                                 loading="lazy"
                                 style="object-fit: cover; min-height: 250px; cursor: pointer;">
                            <?php else: ?>
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center h-100 rounded-start" style="min-height: 250px;">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                            <?php endif; ?>
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center rounded-start" 
                                 style="background: rgba(30, 64, 175, 0.8); opacity: 0; transition: opacity 0.3s;">
                                <i class="fas fa-search-plus fa-2x text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body d-flex flex-column justify-content-center h-100">
                                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($member['name']); ?></h5>
                                <p class="card-text mb-2">
                                    <strong class="d-block text-primary"><?php echo htmlspecialchars($member['position']); ?></strong>
                                    <small class="text-muted d-block mt-1"><?php echo htmlspecialchars(substr($member['role'], 0, 80)); ?></small>
                                </p>
                                <span class="badge bg-warning text-dark w-fit"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($member['importance']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Modal détail membre -->
        <div class="modal fade" id="memberDetailModal<?php echo $member['id']; ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-gradient border-0" style="background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);">
                        <h5 class="modal-title text-white fw-bold"><?php echo htmlspecialchars($member['name']); ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-5 mb-3 mb-md-0">
                                <?php if (!empty($member['image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($member['image']); ?>"
                                     class="img-fluid rounded"
                                     alt="<?php echo htmlspecialchars($member['name']); ?>"
                                     loading="lazy"
                                     style="max-height: 400px; object-fit: cover; width: 100%;">
                                <?php endif; ?>
                            </div>
                            <div class="col-md-7">
                                <h6 class="text-muted small mb-1">POSTE</h6>
                                <h4 class="mb-4"><?php echo htmlspecialchars($member['position']); ?></h4>
                                
                                <h6 class="text-muted small mb-1">DÉPARTEMENT</h6>
                                <p class="mb-4"><?php echo htmlspecialchars($department['name']); ?></p>
                                
                                <h6 class="text-muted small mb-1">RESPONSABILITÉ</h6>
                                <p class="mb-4">
                                    <span class="badge bg-warning text-dark"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($member['importance']); ?></span>
                                </p>

                                <h6 class="text-muted small mb-1">RÔLE & MISSIONS</h6>
                                <p class="text-justify"><?php echo nl2br(htmlspecialchars($member['role'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info text-center py-5"><i class="fas fa-info-circle"></i> Aucun membre dans ce département.</div>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>
