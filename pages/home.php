<?php
$settings = getSiteSettings();
$videoPath = $settings['presentation_video'] ?? null;
?>

<!-- Section vidéo héros -->
<?php if (!empty($videoPath) && file_exists(__DIR__ . '/../' . $videoPath)): ?>
<section class="hero-video" style="position: relative; height: 500px; overflow: hidden;">
    <video autoplay muted loop style="width: 100%; height: 100%; object-fit: cover;">
        <source src="<?php echo htmlspecialchars($videoPath); ?>" type="video/mp4">
    </video>
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; text-align: center;">
        <h1><?php echo htmlspecialchars($settings['site_name'] ?? 'Bienvenue'); ?></h1>
    </div>
</section>
<?php endif; ?>

<!-- ...existing code...-->