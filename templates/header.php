<?php
if (!function_exists('__')) {
    require_once __DIR__ . '/../config/languages.php';
}

$settings = getSiteSettings();
$page_title = $_GET['page'] ?? 'accueil';
$current_lang = $_SESSION['lang'] ?? 'fr';
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Meta Description SEO -->
    <meta name="description" content="<?php echo htmlspecialchars($settings['meta_description'] ?? $settings['site_description'] ?? ''); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($settings['site_keywords'] ?? ''); ?>">
    <meta name="author" content="<?php echo htmlspecialchars($settings['site_name'] ?? 'Bantu Consulting'); ?>">
    <meta name="language" content="French">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    
    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($settings['meta_title'] ?? $settings['site_name']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($settings['meta_description'] ?? ''); ?>">
    <meta property="og:locale" content="fr_FR">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($settings['meta_title'] ?? ''); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($settings['meta_description'] ?? ''); ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
    
    <!-- Favicon -->
    <?php if (!empty($settings['site_favicon'])): ?>
    <link rel="icon" type="image/png" href="uploads/<?php echo htmlspecialchars($settings['site_favicon']); ?>">
    <link rel="apple-touch-icon" href="uploads/<?php echo htmlspecialchars($settings['site_favicon']); ?>">
    <?php endif; ?>
    
    <!-- DNS Prefetch -->
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Title -->
    <title><?php echo htmlspecialchars($settings['meta_title'] ?? $settings['site_name'] . ' | Conseil & Stratégie'); ?></title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Lightbox for Gallery -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/style.css">
    
    <style>
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --accent: #f97316;
            --accent-light: #fed7aa;
            --success: #16a34a;
            --danger: #dc2626;
            --dark: #0f172a;
            --light: #f8fafc;
            --gray: #64748b;
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            color: #0f172a;
        }
        
        /* Navbar Moderne */
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 1.2rem 0;
            box-shadow: 0 10px 30px rgba(30, 64, 175, 0.2);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid var(--accent);
        }
        
        .navbar-brand {
            font-size: 1.6rem;
            font-weight: 700;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .navbar-brand img {
            height: 45px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            margin: 0 8px;
            position: relative;
            transition: var(--transition);
        }
        
        .nav-link:hover {
            color: var(--accent) !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .dropdown-menu {
            background: white;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
            margin-top: 10px;
        }
        
        .dropdown-item {
            color: var(--dark);
            transition: var(--transition);
        }
        
        .dropdown-item:hover {
            background-color: var(--accent-light);
            color: var(--primary);
        }
        
        /* Boutons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            padding: 12px 24px;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(30, 64, 175, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.35);
        }
        
        .btn-secondary {
            background: var(--gray);
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
        }
        
        .btn-secondary:hover {
            background: var(--dark);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="?page=accueil">
            <?php if (!empty($settings['site_logo'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($settings['site_logo']); ?>" alt="Logo">
            <?php else: ?>
                <i class="fas fa-rocket"></i>
            <?php endif; ?>
            <span><?php echo htmlspecialchars($settings['site_name'] ?? 'Bantu'); ?></span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="?page=accueil">
                        <i class="fas fa-home"></i> <?php echo __('nav.home'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=projets">
                        <i class="fas fa-briefcase"></i> <?php echo __('nav.projects'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=equipes">
                        <i class="fas fa-users"></i> <?php echo __('nav.teams'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=services">
                        <i class="fas fa-cogs"></i> <?php echo __('nav.services'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=apropos">
                        <i class="fas fa-info-circle"></i> <?php echo __('nav.about'); ?>
                    </a>
                </li>
                
                <!-- Sélecteur de langue -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-globe"></i> 
                        <?php echo $current_lang === 'fr' ? 'Français' : 'English'; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item <?php echo $current_lang === 'fr' ? 'active' : ''; ?>" href="?lang=fr&page=<?php echo $_GET['page'] ?? 'accueil'; ?>">🇫🇷 Français</a></li>
                        <li><a class="dropdown-item <?php echo $current_lang === 'en' ? 'active' : ''; ?>" href="?lang=en&page=<?php echo $_GET['page'] ?? 'accueil'; ?>">🇬🇧 English</a></li>
                    </ul>
                </li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-shield"></i> <?php echo __('nav.admin'); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="?page=admin-dashboard">
                            <i class="fas fa-tachometer-alt"></i> <?php echo __('admin.dashboard'); ?>
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="actions/logout.php">
                            <i class="fas fa-sign-out-alt"></i> <?php echo __('nav.logout'); ?>
                        </a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="?page=admin-login">
                        <i class="fas fa-lock"></i> <?php echo __('nav.admin'); ?>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main>
