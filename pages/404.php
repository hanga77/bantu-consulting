<?php
if (!defined('IN_FOOTER')) {
    include 'templates/header.php';
}
?>

<section class="hero-section" style="min-height: 500px; display: flex; align-items: center;">
    <div class="container text-center">
        <h1 style="font-size: 5rem; color: var(--secondary-color); font-weight: 700;">404</h1>
        <h2 style="font-size: 2rem; margin-bottom: 20px;">Page Non Trouvée</h2>
        <p style="font-size: 1.2rem; margin-bottom: 30px;">
            Désolé, la page que vous cherchez n'existe pas.
        </p>
        <a href="?page=accueil" class="btn btn-primary btn-lg">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>
    </div>
</section>

<?php
if (!defined('IN_FOOTER')) {
    include 'templates/footer.php';
}
?>
