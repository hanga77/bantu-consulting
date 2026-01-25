<?php
include 'templates/header.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch();

if (!$service) {
    echo '<div class="container py-5"><div class="alert alert-warning text-center"><h5>' . __('services.not_found') . '</h5></div></div>';
    include 'templates/footer.php';
    exit;
}

// Récupérer les fichiers PDF du service
$files_stmt = $pdo->prepare("SELECT * FROM service_files WHERE service_id = ? ORDER BY id DESC");
$files_stmt->execute([$id]);
$service_files = $files_stmt->fetchAll();

// Récupérer les autres services pour la navigation
$all_services = $pdo->query("SELECT id, title FROM services ORDER BY id")->fetchAll();
$current_index = array_search($id, array_column($all_services, 'id'));
$prev_service = $current_index > 0 ? $all_services[$current_index - 1] : null;
$next_service = $current_index < count($all_services) - 1 ? $all_services[$current_index + 1] : null;
?>

<div class="container py-5">
    <a href="?page=services" class="btn btn-secondary mb-4"><i class="fas fa-arrow-left"></i> <?php echo __('projects.back'); ?></a>
    
    <div class="row" data-aos="fade-up">
        <div class="col-lg-8">
            <!-- En-tête du service -->
            <div class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <div style="width: 8px; height: 50px; background: linear-gradient(135deg, #1e40af, #f97316); border-radius: 4px; margin-right: 20px;"></div>
                    <div>
                        <h1 class="display-4 fw-bold mb-0"><?php echo htmlspecialchars($service['title']); ?></h1>
                    </div>
                </div>
            </div>

            <!-- Description détaillée -->
            <div class="card border-0 shadow-lg mb-5">
                <div class="card-body p-5">
                    <h3 class="mb-4"><?php echo __('projects.description'); ?></h3>
                    <p class="lead"><?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
                </div>
            </div>

            <!-- Avantages du service -->
            <div class="mb-5">
                <h3 class="mb-4 fw-bold">
                    <i class="fas fa-check-circle" style="color: #16a34a;"></i> <?php echo __('service_detail.benefits'); ?>
                </h3>
                <div class="row g-3">
                    <?php
                    $benefits = [
                        ['title' => $service['benefit1_title'] ?? __('service_detail.expertise'), 'desc' => $service['benefit1_desc'] ?? 'Notre équipe possède une expertise reconnue dans ce domaine avec plusieurs années d\'expérience.', 'icon' => 'lightbulb text-warning'],
                        ['title' => $service['benefit2_title'] ?? __('service_detail.innovation'), 'desc' => $service['benefit2_desc'] ?? 'Nous utilisons les dernières technologies et méthodologies pour garantir le succès de votre projet.', 'icon' => 'rocket text-info'],
                        ['title' => $service['benefit3_title'] ?? __('service_detail.support'), 'desc' => $service['benefit3_desc'] ?? 'Un accompagnement complet de votre projet du début jusqu\'à la réussite complète.', 'icon' => 'handshake text-success'],
                        ['title' => $service['benefit4_title'] ?? __('service_detail.results'), 'desc' => $service['benefit4_desc'] ?? 'Des résultats mesurables et un ROI clair pour votre investissement.', 'icon' => 'chart-line text-primary']
                    ];
                    foreach ($benefits as $benefit):
                    ?>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-<?php echo $benefit['icon']; ?>"></i> <?php echo htmlspecialchars($benefit['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($benefit['desc']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Fichiers PDF -->
            <?php if (!empty($service_files)): ?>
            <div class="card border-0 shadow-lg mb-5 bg-light">
                <div class="card-header bg-white border-bottom">
                    <h3 class="mb-0 fw-bold"><i class="fas fa-file-pdf text-danger"></i> Documents & Ressources</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach ($service_files as $file): ?>
                        <div class="col-md-12">
                            <a href="uploads/services/<?php echo htmlspecialchars($file['file_path']); ?>" target="_blank" class="list-group-item list-group-item-action p-3 border rounded d-flex justify-content-between align-items-center hover-link">
                                <div>
                                    <i class="fas fa-file-pdf text-danger"></i> 
                                    <strong><?php echo htmlspecialchars($file['file_name']); ?></strong>
                                    <br>
                                    <?php if (!empty($service['updated_at'])): ?>
    <p class="text-muted">
        Mis à jour le <?= date('d/m/Y', strtotime($service['updated_at'])) ?>
    </p>
<?php endif; ?>

                                </div>
                                <i class="fas fa-download text-primary"></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="text-muted mt-3 mb-0"><small><i class="fas fa-info-circle"></i> Cliquez sur un document pour le télécharger</small></p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Informations de Contact -->
            <?php if (!empty($service['contact_email']) || !empty($service['contact_phone']) || !empty($service['website'])): ?>
            <div class="card border-0 shadow-lg mb-5 bg-info text-white">
                <div class="card-header bg-darker">
                    <h3 class="mb-0 fw-bold"><i class="fas fa-phone-alt"></i> Nous Contacter pour ce Service</h3>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <?php if (!empty($service['contact_email'])): ?>
                        <div class="col-md-6">
                            <a href="mailto:<?php echo htmlspecialchars($service['contact_email']); ?>" class="btn btn-light btn-lg w-100">
                                <i class="fas fa-envelope"></i> 
                                <?php echo htmlspecialchars($service['contact_email']); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($service['contact_phone'])): ?>
                        <div class="col-md-6">
                            <a href="tel:<?php echo htmlspecialchars($service['contact_phone']); ?>" class="btn btn-light btn-lg w-100">
                                <i class="fas fa-phone"></i> 
                                <?php echo htmlspecialchars($service['contact_phone']); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($service['website'])): ?>
                        <div class="col-md-12">
                            <a href="<?php echo htmlspecialchars($service['website']); ?>" target="_blank" class="btn btn-light btn-lg w-100">
                                <i class="fas fa-globe"></i> 
                                En savoir plus
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Processus -->
            <div class="mb-5">
                <h3 class="mb-4 fw-bold">
                    <i class="fas fa-cogs"></i> <?php echo __('service_detail.process'); ?>
                </h3>
                <div class="row g-3">
                    <?php
                    $processes = [
                        ['num' => 1, 'title' => $service['process1_title'] ?? __('service_detail.step1'), 'desc' => $service['process1_desc'] ?? 'Nous commençons par une analyse approfondie de vos besoins et objectifs.'],
                        ['num' => 2, 'title' => $service['process2_title'] ?? __('service_detail.step2'), 'desc' => $service['process2_desc'] ?? 'Nous élaborons une stratégie personnalisée adaptée à votre contexte.'],
                        ['num' => 3, 'title' => $service['process3_title'] ?? __('service_detail.step3'), 'desc' => $service['process3_desc'] ?? 'Mise en œuvre du plan d\'action avec suivi régulier des progrès.'],
                        ['num' => 4, 'title' => $service['process4_title'] ?? __('service_detail.step4'), 'desc' => $service['process4_desc'] ?? 'Évaluation des résultats et optimisation continue pour votre succès.']
                    ];
                    foreach ($processes as $process):
                    ?>
                    <div class="col-md-6">
                        <div class="card border-start border-primary border-5 shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $process['num']; ?>. <?php echo htmlspecialchars($process['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($process['desc']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Navigation entre services -->
            <div class="row g-3 mt-4 pt-4 border-top">
                <div class="col-md-6">
                    <?php if ($prev_service): ?>
                    <a href="?page=service-detail&id=<?php echo $prev_service['id']; ?>" class="btn btn-outline-primary w-100">
                        <i class="fas fa-arrow-left"></i> <?php echo htmlspecialchars($prev_service['title']); ?>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 text-end">
                    <?php if ($next_service): ?>
                    <a href="?page=service-detail&id=<?php echo $next_service['id']; ?>" class="btn btn-outline-primary w-100">
                        <?php echo htmlspecialchars($next_service['title']); ?> <i class="fas fa-arrow-right"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4" data-aos="fade-left">
            <!-- Card Info -->
            <div class="card border-0 shadow-lg mb-4 bg-gradient text-white">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-lightbulb"></i> <?php echo __('service_detail.quick_facts'); ?></h5>
                    <ul class="list-unstyled">
                        <?php
                        $facts = [
                            $service['fact1'] ?? __('service_detail.fact1'),
                            $service['fact2'] ?? __('service_detail.fact2'),
                            $service['fact3'] ?? __('service_detail.fact3'),
                            $service['fact4'] ?? __('service_detail.fact4')
                        ];
                        foreach ($facts as $fact):
                            if (!empty($fact)):
                        ?>
                        <li class="mb-2"><i class="fas fa-check"></i> <?php echo htmlspecialchars($fact); ?></li>
                        <?php endif; endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Autres services -->
            <h5 class="mb-4 fw-bold"><?php echo __('service_detail.other_services'); ?></h5>
            <div class="list-group">
                <?php foreach ($all_services as $srv): ?>
                    <?php if ($srv['id'] != $id): ?>
                    <a href="?page=service-detail&id=<?php echo $srv['id']; ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-arrow-right text-primary"></i> <?php echo htmlspecialchars($srv['title']); ?>
                    </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- CTA -->
            <div class="card border-0 shadow-lg mt-4 bg-primary text-white">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo __('service_detail.interested'); ?></h5>
                    <p class="card-text small mb-3"><?php echo __('service_detail.contact_text'); ?></p>
                    <a href="?page=accueil#contact" class="btn btn-light btn-sm w-100">
                        <i class="fas fa-envelope"></i> <?php echo __('home.contact'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
