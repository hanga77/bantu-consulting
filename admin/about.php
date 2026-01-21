<h2>Gestion À Propos</h2>

<?php
$about = $pdo->query("SELECT * FROM about LIMIT 1")->fetch();
if (!$about) {
    $pdo->exec("INSERT INTO about (motto, description) VALUES ('', '')");
    $about = $pdo->query("SELECT * FROM about LIMIT 1")->fetch();
}
?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="actions/save-about.php">
            <div class="mb-3">
                <label for="motto" class="form-label">Devise</label>
                <input type="text" class="form-control" id="motto" name="motto" value="<?php echo htmlspecialchars($about['motto'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="6"><?php echo htmlspecialchars($about['description'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Enregistrer</button>
        </form>
    </div>
</div>
