<?php
/**
 * Configuration des Langues - Système Hybride
 * 
 * Combine:
 * 1. Traductions locales (array $translations)
 * 2. API de traduction (TranslationAPI)
 * 
 * Priorité: Traductions locales > API
 */

// Initialiser la langue par défaut
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'fr';
}

// Récupérer la langue depuis l'URL ou la session
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'fr';
$lang = in_array($lang, ['fr', 'en', 'es', 'pt', 'zh', 'ar', 'sw']) ? $lang : 'fr';
$_SESSION['lang'] = $lang;

// Tableau des traductions locales (prioritaires)
$translations = [
    'fr' => [
        // Navigation
        'nav.home' => 'Accueil',
        'nav.projects' => 'Projets',
        'nav.teams' => 'Équipes',
        'nav.services' => 'Services',
        'nav.about' => 'À Propos',
        'nav.admin' => 'Admin',
        'nav.logout' => 'Déconnexion',
        
        // Accueil
        'home.title' => 'Bienvenue chez Bantu Consulting',
        'home.subtitle' => 'Solutions innovantes pour votre entreprise',
        'home.contact_btn' => 'Nous Contacter',
        'home.our_motto' => 'Notre Devise',
        'home.presentation' => 'Présentation de Bantu Consulting',
        'home.our_services' => 'Nos Services',
        'home.see_all_services' => 'Voir tous nos services',
        'home.ready' => 'Prêt à Transformer Votre Entreprise ?',
        'home.ready_text' => 'Découvrez comment Bantu Consulting peut vous aider à atteindre vos objectifs',
        'home.contact' => 'Nous Contacter',
        'home.contact_text' => 'Envoyez-nous un message et nous vous répondrons dans les 24 heures',
        'home.fullname' => 'Nom complet',
        'home.email' => 'Email',
        'home.message' => 'Message',
        'home.send' => 'Envoyer',
        'home.describe' => 'Décrivez votre demande...',
        
        // Projets
        'projects.title' => 'Nos Projets',
        'projects.subtitle' => 'Découvrez les projets que nous avons réalisés pour nos clients',
        'projects.gallery' => 'Galerie',
        'projects.list' => 'Liste',
        'projects.details' => 'Voir détails',
        'projects.back' => 'Retour aux projets',
        'projects.description' => 'Description',
        'projects.status' => 'Statut',
        'projects.start_date' => 'Début',
        'projects.end_date' => 'Fin',
        'projects.team' => 'Équipe de réalisation',
        'projects.no_projects' => 'Aucun projet disponible',
        
        // Équipes
        'teams.title' => 'Nos Équipes',
        'teams.subtitle' => 'Découvrez nos départements et leurs responsables',
        'teams.responsible' => 'Responsable',
        'teams.team' => 'Équipe',
        'teams.position' => 'Poste',
        'teams.department' => 'Département',
        'teams.responsibility' => 'Responsabilité',
        'teams.role' => 'Rôle & Missions',
        'teams.see_department' => 'En savoir plus sur le département',
        'teams.no_members' => 'Aucun membre dans ce département pour le moment.',
        'teams.add_members' => 'Ajouter des membres →',
        'teams.close' => 'Fermer',
        
        // Services
        'services.title' => 'Nos Services',
        'services.subtitle' => 'Solutions complètes pour accompagner votre entreprise',
        'services.learn_more' => 'En savoir plus',
        'services.need' => 'Besoin d\'un service spécifique ?',
        'services.contact_us' => 'Contactez-nous pour discuter de vos besoins',
        'service_detail.benefits' => 'Avantages du service',
        'service_detail.process' => 'Notre processus',
        'service_detail.step1' => 'Analyse des besoins',
        'service_detail.step2' => 'Définition de la stratégie',
        'service_detail.step3' => 'Mise en œuvre',
        'service_detail.step4' => 'Suivi et optimisation',
        'service_detail.quick_facts' => 'Informations clés',
        'service_detail.other_services' => 'Autres services',
        'service_detail.interested' => 'Intéressé par ce service ?',
        'service_detail.contact_text' => 'Contactez-nous pour en savoir plus',
        'service_detail.expertise' => 'Expertise reconnue',
        'service_detail.innovation' => 'Innovation continue',
        'service_detail.support' => 'Accompagnement complet',
        'service_detail.results' => 'Résultats mesurables',
        'services.back' => 'Retour aux services',
        'services.not_found' => 'Service introuvable',
        
        // À Propos
        'about.title' => 'À Propos de Bantu Consulting',
        'about.who' => 'Qui sommes-nous ?',
        'about.our_team' => 'Notre Équipe',
        'about.meet_experts' => 'Rencontrez les experts qui font fonctionner Bantu Consulting',
        
        // Admin
        'admin.login' => 'Connexion Admin',
        'admin.username' => 'Nom d\'utilisateur',
        'admin.password' => 'Mot de passe',
        'admin.connect' => 'Se Connecter',
        'admin.dashboard' => 'Tableau de bord',
        'admin.projects' => 'Projets',
        'admin.teams' => 'Équipes',
        'admin.services' => 'Services',
        'admin.carousel' => 'Carrousel',
        'admin.about' => 'À Propos',
        'admin.contacts' => 'Contacts',
        'admin.settings' => 'Paramètres',
        
        // Footer
        'footer.quick_links' => 'Liens Rapides',
        'footer.services_menu' => 'Services',
        'footer.contact' => 'Contact',
        'footer.email' => 'Email',
        'footer.phone' => 'Téléphone',
        'footer.address' => 'Adresse',
    ],
    'en' => [
        // Navigation
        'nav.home' => 'Home',
        'nav.projects' => 'Projects',
        'nav.teams' => 'Teams',
        'nav.services' => 'Services',
        'nav.about' => 'About',
        'nav.admin' => 'Admin',
        'nav.logout' => 'Logout',
        
        // Home
        'home.title' => 'Welcome to Bantu Consulting',
        'home.subtitle' => 'Innovative Solutions for Your Business',
        'home.contact_btn' => 'Contact Us',
        'home.our_motto' => 'Our Motto',
        'home.presentation' => 'Bantu Consulting Presentation',
        'home.our_services' => 'Our Services',
        'home.see_all_services' => 'See all services',
        'home.ready' => 'Ready to Transform Your Business?',
        'home.ready_text' => 'Discover how Bantu Consulting can help you achieve your goals',
        'home.contact' => 'Contact Us',
        'home.contact_text' => 'Send us a message and we will respond within 24 hours',
        'home.fullname' => 'Full Name',
        'home.email' => 'Email',
        'home.message' => 'Message',
        'home.send' => 'Send',
        'home.describe' => 'Describe your request...',
        
        // Projects
        'projects.title' => 'Our Projects',
        'projects.subtitle' => 'Discover the projects we have completed for our clients',
        'projects.gallery' => 'Gallery',
        'projects.list' => 'List',
        'projects.details' => 'See details',
        'projects.back' => 'Back to projects',
        'projects.description' => 'Description',
        'projects.status' => 'Status',
        'projects.start_date' => 'Start',
        'projects.end_date' => 'End',
        'projects.team' => 'Project Team',
        'projects.no_projects' => 'No projects available',
        
        // Teams
        'teams.title' => 'Our Teams',
        'teams.subtitle' => 'Discover our departments and their managers',
        'teams.responsible' => 'Manager',
        'teams.team' => 'Team',
        'teams.position' => 'Position',
        'teams.department' => 'Department',
        'teams.responsibility' => 'Responsibility',
        'teams.role' => 'Role & Responsibilities',
        'teams.see_department' => 'Learn more about the department',
        'teams.no_members' => 'No members in this department yet.',
        'teams.add_members' => 'Add members →',
        'teams.close' => 'Close',
        
        // Services
        'services.title' => 'Our Services',
        'services.subtitle' => 'Complete solutions to support your business',
        'services.learn_more' => 'Learn more',
        'services.need' => 'Need a specific service?',
        'services.contact_us' => 'Contact us to discuss your needs',
        'service_detail.benefits' => 'Service benefits',
        'service_detail.process' => 'Our process',
        'service_detail.step1' => 'Needs analysis',
        'service_detail.step2' => 'Strategy definition',
        'service_detail.step3' => 'Implementation',
        'service_detail.step4' => 'Monitoring and optimization',
        'service_detail.quick_facts' => 'Key information',
        'service_detail.other_services' => 'Other services',
        'service_detail.interested' => 'Interested in this service?',
        'service_detail.contact_text' => 'Contact us to learn more',
        'service_detail.expertise' => 'Recognized expertise',
        'service_detail.innovation' => 'Continuous innovation',
        'service_detail.support' => 'Full support',
        'service_detail.results' => 'Measurable results',
        'services.back' => 'Back to services',
        'services.not_found' => 'Service not found',
        
        // About
        'about.title' => 'About Bantu Consulting',
        'about.who' => 'Who are we?',
        'about.our_team' => 'Our Team',
        'about.meet_experts' => 'Meet the experts who make Bantu Consulting work',
        
        // Admin
        'admin.login' => 'Admin Login',
        'admin.username' => 'Username',
        'admin.password' => 'Password',
        'admin.connect' => 'Login',
        'admin.dashboard' => 'Dashboard',
        'admin.projects' => 'Projects',
        'admin.teams' => 'Teams',
        'admin.services' => 'Services',
        'admin.carousel' => 'Carousel',
        'admin.about' => 'About',
        'admin.contacts' => 'Contacts',
        'admin.settings' => 'Settings',
        
        // Footer
        'footer.quick_links' => 'Quick Links',
        'footer.services_menu' => 'Services',
        'footer.contact' => 'Contact',
        'footer.email' => 'Email',
        'footer.phone' => 'Phone',
        'footer.address' => 'Address',
    ]
];

/**
 * Fonction de traduction améliorée
 * Supporte:
 * 1. Clés définies dans $translations (priorité)
 * 2. API TranslationAPI pour contenu dynamique
 */
if (!function_exists('__')) {
function __($key, $target_lang = null) {
    global $translations, $lang, $translator;
    
    // Déterminer la langue cible
    if ($target_lang === null) {
        $target_lang = $lang;
    }
    
    // Vérifier si c'est une clé de traduction locale (ex: 'nav.home')
    if (strpos($key, '.') !== false || isset($translations[$target_lang][$key])) {
        // C'est une clé de traduction
        if (isset($translations[$target_lang][$key])) {
            return $translations[$target_lang][$key];
        }
        // Fallback vers français
        if (isset($translations['fr'][$key])) {
            return $translations['fr'][$key];
        }
        return $key;
    }
    
    // Sinon, utiliser l'API de traduction pour contenu dynamique
    if ($target_lang === 'fr') {
        return $key;
    }
    
    // Si TranslationAPI est disponible, l'utiliser
    if (isset($translator) && $translator instanceof TranslationAPI) {
        return $translator->translate($key, $target_lang, 'fr');
    }
    
    // Fallback: retourner le texte original
    return $key;
}
}

/**
 * Fonction pour obtenir la langue actuelle
 */
function getLang() {
    return $_SESSION['lang'] ?? 'fr';
}

/**
 * Fonction pour changer de langue
 */
function setLang($new_lang) {
    global $lang;
    if (in_array($new_lang, ['fr', 'en', 'es', 'pt', 'zh', 'ar', 'sw'])) {
        $_SESSION['lang'] = $new_lang;
        $lang = $new_lang;
        setcookie('language', $new_lang, time() + 86400 * 365, '/');
    }
}

?>
