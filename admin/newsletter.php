<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=admin-login');
    exit;
}
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h2><i class="fas fa-envelope"></i> Gestion Newsletter</h2>
    <div>
        <a href="?page=admin-dashboard&section=newsletter&action=send" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Envoyer un Email
        </a>
    </div>
</div>

<!-- TAB STATISTIQUES -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="fas fa-users"></i> Abonnés Actifs</h6>
                <h3 class="mb-0">
                    <?php 
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM newsletter_subscribers WHERE status = 'active'");
                    echo $stmt->fetch()['count'];
                    ?>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="fas fa-ban"></i> Désabonnés</h6>
                <h3 class="mb-0">
                    <?php 
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM newsletter_subscribers WHERE status = 'inactive'");
                    echo $stmt->fetch()['count'];
                    ?>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="fas fa-chart-pie"></i> Total</h6>
                <h3 class="mb-0">
                    <?php 
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM newsletter_subscribers");
                    echo $stmt->fetch()['count'];
                    ?>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="fas fa-calendar-alt"></i> Cette Semaine</h6>
                <h3 class="mb-0">
                    <?php 
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM newsletter_subscribers WHERE DATE(subscribed_at) >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
                    echo $stmt->fetch()['count'];
                    ?>
                </h3>
            </div>
        </div>
    </div>
</div>

<!-- FORMULAIRE ENVOI EMAIL -->
<?php if (($_GET['action'] ?? '') === 'send'): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-paper-plane"></i> Envoyer un Email en Bloc</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="actions/send-newsletter-email.php">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="recipient" class="form-label fw-bold">Destinataires *</label>
                        <select class="form-control form-control-lg" id="recipient" name="recipient" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="active">Tous les abonnés actifs</option>
                            <option value="inactive">Abonnés inactifs</option>
                            <option value="all">Tous les abonnés</option>
                        </select>
                        <small class="text-muted d-block mt-2">
                            <strong>Actifs:</strong> Abonnés ayant confirmé leur inscription<br>
                            <strong>Inactifs:</strong> Abonnés désabonnés<br>
                            <strong>Tous:</strong> Ensemble des abonnés
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="subject" class="form-label fw-bold">Sujet *</label>
                        <input type="text" class="form-control form-control-lg" id="subject" name="subject" required placeholder="Ex: Actualités de janvier">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label fw-bold">Message *</label>
                <textarea class="form-control" id="message" name="message" rows="8" required placeholder="Rédigez votre message..."></textarea>
                <small class="text-muted d-block mt-2">
                    <i class="fas fa-lightbulb"></i> Conseil: Utilisez un langage clair et engageant
                </small>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>Prévisualisation:</strong>
                <div id="preview-message" style="background: white; padding: 15px; margin-top: 10px; border-radius: 4px; color: #333;"></div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-paper-plane"></i> Envoyer
                </button>
                <a href="?page=admin-dashboard&section=newsletter" class="btn btn-secondary btn-lg">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Prévisualisation du message
document.getElementById('message').addEventListener('input', function(e) {
    const preview = document.getElementById('preview-message');
    preview.innerHTML = e.target.value.replace(/\n/g, '<br>');
});
</script>
<?php endif; ?>

<!-- LISTE DES ABONNÉS -->
<div class="card border-0 shadow-lg">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> Liste des Abonnés</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Email</th>
                    <th>Nom</th>
                    <th>Statut</th>
                    <th>Date d'abonnement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $page = intval($_GET['page'] ?? 1);
                    $per_page = 20;
                    $offset = ($page - 1) * $per_page;
                    
                    $subscribers = $pdo->query("SELECT * FROM newsletter_subscribers ORDER BY subscribed_at DESC LIMIT $per_page OFFSET $offset")->fetchAll();
                    
                    if (!empty($subscribers)):
                        foreach ($subscribers as $sub):
                ?>
                <tr>
                    <td>
                        <a href="mailto:<?php echo htmlspecialchars($sub['email']); ?>" class="text-decoration-none">
                            <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($sub['email']); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($sub['name'] ?? '-'); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $sub['status'] === 'active' ? 'success' : 'secondary'; ?>">
                            <?php echo ucfirst($sub['status']); ?>
                        </span>
                    </td>
                    <td>
                        <small><?php echo date('d/m/Y H:i', strtotime($sub['subscribed_at'])); ?></small>
                    </td>
                    <td>
                        <a href="actions/toggle-newsletter-subscriber.php?id=<?php echo $sub['id']; ?>" 
                           class="btn btn-sm btn-<?php echo $sub['status'] === 'active' ? 'warning' : 'info'; ?>">
                            <i class="fas fa-toggle-<?php echo $sub['status'] === 'active' ? 'on' : 'off'; ?>"></i>
                            <?php echo $sub['status'] === 'active' ? 'Désabonner' : 'Réabonner'; ?>
                        </a>
                        <a href="actions/delete-newsletter-subscriber.php?id=<?php echo $sub['id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Supprimer définitivement?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Aucun abonné</td></tr>
                <?php endif; } catch (Exception $e) {
                    echo '<tr><td colspan="5" class="alert alert-danger mb-0">' . safeErrorMessage($e) . '</td></tr>';
                } ?>
            </tbody>
        </table>
    </div>
</div>
