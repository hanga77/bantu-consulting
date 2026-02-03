<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=admin-login');
    exit;
}
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h2><i class="fas fa-envelope"></i> Gestion Newsletter</h2>
</div>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-4">
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
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="fas fa-trash"></i> Désabonnés</h6>
                <h3 class="mb-0">
                    <?php 
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM newsletter_subscribers WHERE status = 'inactive'");
                    echo $stmt->fetch()['count'];
                    ?>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="fas fa-total"></i> Total</h6>
                <h3 class="mb-0">
                    <?php 
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM newsletter_subscribers");
                    echo $stmt->fetch()['count'];
                    ?>
                </h3>
            </div>
        </div>
    </div>
</div>

<!-- Liste des abonnés -->
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
                    $subscribers = $pdo->query("SELECT * FROM newsletter_subscribers ORDER BY subscribed_at DESC LIMIT 100")->fetchAll();
                    if (!empty($subscribers)):
                        foreach ($subscribers as $sub):
                ?>
                <tr>
                    <td>
                        <a href="mailto:<?php echo htmlspecialchars($sub['email']); ?>">
                            <?php echo htmlspecialchars($sub['email']); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($sub['name'] ?? '-'); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $sub['status'] === 'active' ? 'success' : 'secondary'; ?>">
                            <?php echo ucfirst($sub['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($sub['subscribed_at'])); ?></td>
                    <td>
                        <a href="actions/newsletter-toggle.php?id=<?php echo $sub['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-toggle-<?php echo $sub['status'] === 'active' ? 'on' : 'off'; ?>"></i>
                        </a>
                        <a href="actions/newsletter-delete.php?id=<?php echo $sub['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet abonné ?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Aucun abonné</td></tr>
                <?php endif; } catch (Exception $e) {
                    echo '<tr><td colspan="5" class="alert alert-danger mb-0">' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                } ?>
            </tbody>
        </table>
    </div>
</div>
