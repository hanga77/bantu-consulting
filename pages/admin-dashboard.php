<?php
include 'templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=admin-login');
    exit;
}

// Timeout de session (30 min)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: ?page=admin-login&timeout=1');
    exit;
}
$_SESSION['last_activity'] = time();

$section = $_GET['section'] ?? 'dashboard';
$section = preg_replace('/[^a-z0-9_-]/', '', $section);
?>

<div class="container-fluid py-4">
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if ($section === 'dashboard'): ?>
    <!-- Dashboard Accueil -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Bienvenue dans le Panneau d'Administration</h2>
            <p class="text-muted">Gérez votre site Bantu Consulting</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Logo & Favicon -->
        <div class="col-md-3">
            <a href="?page=admin-dashboard&section=settings" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-image fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Logo & Favicon</h5>
                        <p class="card-text text-muted">Changer votre logo et favicon</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Projets -->
        <div class="col-md-3">
            <a href="?page=admin-dashboard&section=projects" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-briefcase fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Projets</h5>
                        <p class="card-text text-muted">Gérer vos projets</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Équipes -->
        <div class="col-md-3">
            <a href="?page=admin-dashboard&section=teams" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Équipes</h5>
                        <p class="card-text text-muted">Gérer les membres</p>
                    </div>
                </div>
            </a>
        </div>
        
         <!-- experts -->
        <div class="col-md-3">
            <a href="?page=admin-dashboard&section=experts" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-user-tie fa-3x text-dark mb-3"></i>
                        <h5 class="card-title">Experts</h5>
                        <p class="card-text text-muted">Gérer les experts</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Départements -->
        <div class="col-md-3">
            <a href="?page=admin-dashboard&section=departments" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-sitemap fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Départements</h5>
                        <p class="card-text text-muted">Gérer les pôles</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Services -->
        <div class="col-md-3">
            <a href="?page=admin-dashboard&section=services" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-cogs fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Services</h5>
                        <p class="card-text text-muted">Gérer les services</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Carrousel -->
        <div class="col-md-3">
            <a href="?page=admin-dashboard&section=carousel" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-images fa-3x text-danger mb-3"></i>
                        <h5 class="card-title">Carrousel</h5>
                        <p class="card-text text-muted">Gérer les images</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- À Propos -->
        <div class="col-md-3">
            <a href="?page=admin-dashboard&section=about" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-info-circle fa-3x text-secondary mb-3"></i>
                        <h5 class="card-title">À Propos</h5>
                        <p class="card-text text-muted">Modifier la description</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Contacts -->
        <div class="col-md-3">
            <a href="?page=admin-dashboard&section=contacts" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-envelope fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Contacts</h5>
                        <p class="card-text text-muted">Voir les messages</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Paramètres -->
        <div class="col-md-3">
            <a href="?page=admin-dashboard&section=settings" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-sliders-h fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Paramètres</h5>
                        <p class="card-text text-muted">Configuration du site</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-3">
            <div class="list-group sticky-top">
                <a href="?page=admin-dashboard" class="list-group-item list-group-item-action <?php echo $section === 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Accueil
                </a>
                <a href="?page=admin-dashboard&section=projects" class="list-group-item list-group-item-action <?php echo $section === 'projects' ? 'active' : ''; ?>">
                    <i class="fas fa-briefcase"></i> Projets
                </a>
                <a href="?page=admin-dashboard&section=experts" class="list-group-item ...">
                    <i class="fas fa-user-tie"></i> Experts
                </a>
                <a href="?page=admin-dashboard&section=teams" class="list-group-item list-group-item-action <?php echo $section === 'teams' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Équipes
                </a>
                <a href="?page=admin-dashboard&section=departments" class="list-group-item list-group-item-action <?php echo $section === 'departments' ? 'active' : ''; ?>">
                    <i class="fas fa-sitemap"></i> Départements
                </a>
                <a href="?page=admin-dashboard&section=services" class="list-group-item list-group-item-action <?php echo $section === 'services' ? 'active' : ''; ?>">
                    <i class="fas fa-cogs"></i> Services
                </a>
                <a href="?page=admin-dashboard&section=carousel" class="list-group-item list-group-item-action <?php echo $section === 'carousel' ? 'active' : ''; ?>">
                    <i class="fas fa-images"></i> Carrousel
                </a>
                <a href="?page=admin-dashboard&section=about" class="list-group-item list-group-item-action <?php echo $section === 'about' ? 'active' : ''; ?>">
                    <i class="fas fa-file-alt"></i> À Propos
                </a>
                <a href="?page=admin-dashboard&section=contacts" class="list-group-item list-group-item-action <?php echo $section === 'contacts' ? 'active' : ''; ?>">
                    <i class="fas fa-envelope"></i> Contacts
                </a>
                <a href="?page=admin-dashboard&section=settings" class="list-group-item list-group-item-action <?php echo $section === 'settings' ? 'active' : ''; ?>">
                    <i class="fas fa-sliders-h"></i> Paramètres
                </a>
                <a href="?page=admin-dashboard&section=users" class="list-group-item list-group-item-action <?php echo $section === 'users' ? 'active' : ''; ?>">
                    <i class="fas fa-user"></i> Utilisateurs
                </a>
                <a href="actions/logout.php" class="list-group-item list-group-item-action text-danger">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <?php
            if ($section !== 'dashboard') {
                $file = "admin/{$section}.php";
                if (file_exists($file)) {
                    include $file;
                } else {
                    echo '<div class="alert alert-warning">Section non trouvée</div>';
                }
            }
            ?>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
