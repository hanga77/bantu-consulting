<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=admin-login');
    exit;
}

$about = $pdo->query("SELECT * FROM about LIMIT 1")->fetch();
if (!$about) {
    $about = ['id' => null, 'motto' => '', 'description' => '', 'mission' => '', 'vision' => ''];
}
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h2><i class="fas fa-file-alt"></i> À Propos</h2>
</div>

<div class="card border-0 shadow-lg">
    <div class="card-body">
        <form method="POST" action="actions/save-about.php">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <div class="mb-3">
                <label for="motto" class="form-label fw-bold">Devise/Slogan</label>
                <input type="text" class="form-control form-control-lg" id="motto" name="motto" value="<?php echo htmlspecialchars($about['motto'] ?? ''); ?>" placeholder="Ex: Votre succès est notre mission">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($about['description'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="mission" class="form-label fw-bold">Mission</label>
                <textarea class="form-control" id="mission" name="mission" rows="3"><?php echo htmlspecialchars($about['mission'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="vision" class="form-label fw-bold">Vision</label>
                <textarea class="form-control" id="vision" name="vision" rows="3"><?php echo htmlspecialchars($about['vision'] ?? ''); ?></textarea>
            </div>

            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </form>
    </div>
</div>
