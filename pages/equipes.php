<?php
include 'templates/header.php';

$departments = $pdo->query("SELECT * FROM departments ORDER BY FIELD(name, 'Pôle LBC/FT', 'Pôle DCA/DID/DIDH', 'Département RH', 'Département GCTD')")->fetchAll();

// Charger tous les membres en une seule requête — élimine le N+1
$all_teams = $pdo->query("SELECT * FROM teams ORDER BY importance DESC, name")->fetchAll();
$teams_by_dept = [];
foreach ($all_teams as $t) {
    $teams_by_dept[(int)$t['department_id']][] = $t;
}
?>

<div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="display-4 fw-bold mb-2"><?php echo __('teams.title'); ?></h1>
        <p class="text-muted fs-6"><?php echo __('teams.subtitle'); ?></p>
    </div>
    
    <?php if (!empty($departments)): ?>
        <?php foreach ($departments as $dept_index => $dept): ?>
        <section class="mb-5 pb-5 border-bottom" data-aos="fade-up" data-aos-delay="<?php echo $dept_index * 100; ?>">
            <!-- En-tête du département - CLIQUABLE -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <a href="?page=departement-detail&id=<?php echo $dept['id']; ?>" class="text-decoration-none text-dark">
                        <div class="d-flex align-items-start mb-3 p-3 rounded" style="transition: all 0.3s; cursor: pointer;" 
                             onmouseover="this.style.backgroundColor='#f8fafc'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'"
                             onmouseout="this.style.backgroundColor='transparent'; this.style.boxShadow='none'">
                            <div style="width: 6px; height: 60px; background: linear-gradient(135deg, #1e40af, #f97316); border-radius: 4px; margin-right: 20px; margin-top: 5px;"></div>
                            <div class="flex-grow-1">
                                <h2 class="mb-2 fw-bold">
                                    <?php 
                                    $icon = strpos($dept['name'], 'Pôle') === 0 ? '🎯' : '📋';
                                    echo $icon . ' ' . htmlspecialchars($dept['name']); 
                                    ?>
                                </h2>
                                <p class="text-muted mb-0 lead"><?php echo htmlspecialchars($dept['description']); ?></p>
                                <small class="text-primary fw-bold mt-2 d-inline-block"><?php echo __('teams.see_department'); ?> →</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            
            <?php $members = $teams_by_dept[(int)$dept['id']] ?? []; ?>
            
            <?php if (!empty($members)): ?>
                <!-- RESPONSABLE EN ÉVIDENCE -->
                <div class="row mb-5">
                    <div class="col-md-12">
                        <h4 class="mb-4"><i class="fas fa-crown" style="color: #f97316;"></i> <?php echo __('teams.responsible'); ?></h4>
                    </div>
                    <div class="col-lg-6">
                        <div class="card h-100 card-hover border-primary border-3 shadow-lg overflow-hidden" 
                             role="button" 
                             data-bs-toggle="modal" 
                             data-bs-target="#memberModal<?php echo $members[0]['id']; ?>"
                             style="cursor: pointer;">
                            <div class="row g-0">
                                <div class="col-md-5 position-relative overflow-hidden">
                                    <?php if (!empty($members[0]['image'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($members[0]['image']); ?>"
                                         class="img-fluid w-100"
                                         alt="<?php echo htmlspecialchars($members[0]['name']); ?>"
                                         loading="lazy"
                                         style="object-fit: cover; min-height: 300px; transition: transform 0.3s;">
                                    <?php else: ?>
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center w-100" style="min-height: 300px;">
                                        <i class="fas fa-user fa-5x"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                                         style="background: rgba(30, 64, 175, 0.85); opacity: 0; transition: opacity 0.3s;">
                                        <div class="text-center text-white">
                                            <i class="fas fa-search-plus fa-3x mb-2"></i>
                                            <p class="mb-0 fw-bold"><?php echo __('teams.close'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body d-flex flex-column justify-content-center h-100">
                                        <h5 class="card-title fw-bold mb-2"><?php echo htmlspecialchars($members[0]['name']); ?></h5>
                                        <p class="card-text mb-3">
                                            <strong class="d-block text-primary mb-2" style="font-size: 1.1em;"><?php echo htmlspecialchars($members[0]['position']); ?></strong>
                                            <span class="badge bg-warning text-dark"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($members[0]['importance']); ?></span>
                                        </p>
                                        <small class="text-muted"><?php echo htmlspecialchars(substr($members[0]['role'], 0, 150)); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AUTRES MEMBRES -->
                <?php if (count($members) > 1): ?>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-4"><i class="fas fa-users"></i> <?php echo __('teams.team'); ?></h4>
                    </div>
                    <?php foreach (array_slice($members, 1) as $member_index => $member): ?>
                    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="<?php echo ($member_index + 1) * 50; ?>">
                        <div class="card h-100 card-hover border-0 shadow-sm overflow-hidden" 
                             role="button" 
                             data-bs-toggle="modal" 
                             data-bs-target="#memberModal<?php echo $member['id']; ?>"
                             style="cursor: pointer;">
                            <div class="position-relative overflow-hidden">
                                <?php if (!empty($member['image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($member['image']); ?>"
                                     class="card-img-top w-100"
                                     alt="<?php echo htmlspecialchars($member['name']); ?>"
                                     loading="lazy"
                                     style="height: 300px; object-fit: cover; transition: transform 0.3s;">
                                <?php else: ?>
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center w-100" style="height: 300px;">
                                    <i class="fas fa-user fa-4x"></i>
                                </div>
                                <?php endif; ?>
                                <!-- Overlay hover -->
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                                     style="background: rgba(30, 64, 175, 0.85); opacity: 0; transition: opacity 0.3s;">
                                    <div class="text-center text-white">
                                        <i class="fas fa-search-plus fa-3x mb-2"></i>
                                        <p class="mb-0 fw-bold"><?php echo __('teams.close'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($member['name']); ?></h5>
                                <p class="card-text mb-2">
                                    <strong class="d-block text-primary"><?php echo htmlspecialchars($member['position']); ?></strong>
                                </p>
                                <span class="badge bg-info"><i class="fas fa-star"></i> <?php echo htmlspecialchars($member['importance']); ?></span>
                                
                                <!-- Réseaux Sociaux -->
                                <?php if (!empty($member['linkedin']) || !empty($member['twitter']) || !empty($member['facebook']) || !empty($member['instagram']) || !empty($member['website'])): ?>
                                <div class="mt-3 pt-3 border-top">
                                    <?php if (!empty($member['linkedin'])): ?>
                                    <a href="<?php echo htmlspecialchars($member['linkedin']); ?>" target="_blank" class="btn btn-sm btn-outline-info me-2" title="LinkedIn">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (!empty($member['twitter'])): ?>
                                    <a href="<?php echo htmlspecialchars($member['twitter']); ?>" target="_blank" class="btn btn-sm btn-outline-info me-2" title="Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (!empty($member['facebook'])): ?>
                                    <a href="<?php echo htmlspecialchars($member['facebook']); ?>" target="_blank" class="btn btn-sm btn-outline-primary me-2" title="Facebook">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (!empty($member['instagram'])): ?>
                                    <a href="<?php echo htmlspecialchars($member['instagram']); ?>" target="_blank" class="btn btn-sm btn-outline-danger me-2" title="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (!empty($member['website'])): ?>
                                    <a href="<?php echo htmlspecialchars($member['website']); ?>" target="_blank" class="btn btn-sm btn-outline-success me-2" title="Site Web">
                                        <i class="fas fa-globe"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            <?php else: ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle"></i> <?php echo __('teams.no_members'); ?>
                <a href="?page=admin-dashboard&section=teams" class="alert-link ms-2"><?php echo __('teams.add_members'); ?></a>
            </div>
            <?php endif; ?>
        </section>
        <?php endforeach; ?>
    <?php else: ?>
    <div class="alert alert-warning text-center py-5">
        <i class="fas fa-exclamation-triangle"></i> Aucun département disponible.
    </div>
    <?php endif; ?>
</div>

<!-- MODALES -->
<?php foreach ($departments as $dept): ?>
    <?php foreach ($teams_by_dept[(int)$dept['id']] ?? [] as $member): ?>
    <div class="modal fade" id="memberModal<?php echo $member['id']; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $member['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); border: none;">
                    <h5 class="modal-title text-white fw-bold" id="modalLabel<?php echo $member['id']; ?>"><?php echo htmlspecialchars($member['name']); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="<?php echo __('teams.close'); ?>"></button>
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
                            <div class="mb-4">
                                <small class="text-muted text-uppercase fw-bold d-block mb-1"><?php echo __('teams.position'); ?></small>
                                <h4 class="mb-0"><?php echo htmlspecialchars($member['position']); ?></h4>
                            </div>
                            
                            <div class="mb-4">
                                <small class="text-muted text-uppercase fw-bold d-block mb-1"><?php echo __('teams.department'); ?></small>
                                <p class="mb-0"><?php echo htmlspecialchars($dept['name']); ?></p>
                            </div>
                            
                            <div class="mb-4">
                                <small class="text-muted text-uppercase fw-bold d-block mb-1"><?php echo __('teams.responsibility'); ?></small>
                                <span class="badge bg-warning text-dark fs-6"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($member['importance']); ?></span>
                            </div>

                            <div class="mb-4">
                                <small class="text-muted text-uppercase fw-bold d-block mb-2"><?php echo __('teams.role'); ?></small>
                                <p class="text-justify"><?php echo nl2br(htmlspecialchars($member['role'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo __('teams.close'); ?></button>
                    <a href="?page=departement-detail&id=<?php echo $dept['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> <?php echo __('teams.see_department'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endforeach; ?>

<?php include 'templates/footer.php'; ?>
