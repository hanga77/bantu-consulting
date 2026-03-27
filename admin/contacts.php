<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=admin-login');
    exit;
}
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h2><i class="fas fa-envelope"></i> Messages de Contact</h2>
    <a href="?page=admin-dashboard&section=contacts&action=send-response" class="btn btn-primary">
        <i class="fas fa-reply"></i> Répondre en Bloc
    </a>
</div>

<!-- STATISTIQUES -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="fas fa-exclamation-circle"></i> Non Lus</h6>
                <h3 class="mb-0">
                    <?php 
                    try {
                        $stmt = $pdo->query("SELECT COUNT(*) as count FROM contacts WHERE status = 'new'");
                        echo $stmt->fetch()['count'];
                    } catch (Exception $e) { echo '0'; }
                    ?>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="fas fa-check-circle"></i> Traités</h6>
                <h3 class="mb-0">
                    <?php 
                    try {
                        $stmt = $pdo->query("SELECT COUNT(*) as count FROM contacts WHERE status = 'read'");
                        echo $stmt->fetch()['count'];
                    } catch (Exception $e) { echo '0'; }
                    ?>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="fas fa-envelope"></i> Total</h6>
                <h3 class="mb-0">
                    <?php 
                    try {
                        $stmt = $pdo->query("SELECT COUNT(*) as count FROM contacts");
                        echo $stmt->fetch()['count'];
                    } catch (Exception $e) { echo '0'; }
                    ?>
                </h3>
            </div>
        </div>
    </div>
</div>

<!-- FORMULAIRE RÉPONSE EN BLOC -->
<?php if (($_GET['action'] ?? '') === 'send-response'): ?>
<div class="card border-0 shadow-lg mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-reply"></i> Envoyer une Réponse en Bloc</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="actions/send-contact-response.php">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status_filter" class="form-label fw-bold">Destinataires *</label>
                        <select class="form-control form-control-lg" id="status_filter" name="status_filter" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="new">Messages non lus</option>
                            <option value="read">Messages traités</option>
                            <option value="all">Tous les messages</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="subject" class="form-label fw-bold">Sujet *</label>
                        <input type="text" class="form-control form-control-lg" id="subject" name="subject" required placeholder="Re: Votre demande">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="response_message" class="form-label fw-bold">Message de réponse *</label>
                <textarea class="form-control" id="response_message" name="response_message" rows="8" required placeholder="Rédigez votre réponse..."></textarea>
            </div>

            <div class="alert alert-warning">
                <i class="fas fa-lightbulb"></i> 
                <strong>Conseil:</strong> Vous pouvez utiliser <code>{{name}}</code> pour insérer le nom du correspondant
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-paper-plane"></i> Envoyer
                </button>
                <a href="?page=admin-dashboard&section=contacts" class="btn btn-secondary btn-lg">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- LISTE DES MESSAGES -->
<div class="card border-0 shadow-lg">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-envelope-open"></i> Messages Reçus</h5>
        <div class="btn-group btn-group-sm">
            <a href="?page=admin-dashboard&section=contacts" class="btn btn-outline-light <?php echo empty($_GET['filter']) ? 'active' : ''; ?>">Tous</a>
            <a href="?page=admin-dashboard&section=contacts&filter=new" class="btn btn-outline-light <?php echo ($_GET['filter'] ?? '') === 'new' ? 'active' : ''; ?>">Non lus</a>
            <a href="?page=admin-dashboard&section=contacts&filter=read" class="btn btn-outline-light <?php echo ($_GET['filter'] ?? '') === 'read' ? 'active' : ''; ?>">Lus</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>De</th>
                    <th>Sujet</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $filter = $_GET['filter'] ?? '';
                    $query = "SELECT * FROM contacts";
                    
                    if ($filter === 'new' || $filter === 'read') {
                        $query .= " WHERE status = '{$filter}'";
                    }
                    
                    $query .= " ORDER BY created_at DESC LIMIT 50";
                    
                    $contacts = $pdo->query($query)->fetchAll();
                    
                    if (!empty($contacts)):
                        foreach ($contacts as $contact):
                ?>
                <tr class="<?php echo $contact['status'] === 'new' ? 'table-light fw-bold' : ''; ?>">
                    <td>
                        <strong><?php echo htmlspecialchars($contact['name']); ?></strong>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($contact['subject'] ?? '(Sans sujet)'); ?>
                    </td>
                    <td>
                        <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>" class="text-decoration-none">
                            <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($contact['email']); ?>
                        </a>
                    </td>
                    <td>
                        <small><?php echo date('d/m/Y H:i', strtotime($contact['created_at'])); ?></small>
                    </td>
                    <td>
                        <span class="badge bg-<?php echo $contact['status'] === 'new' ? 'danger' : 'success'; ?>">
                            <?php echo $contact['status'] === 'new' ? 'Non lu' : 'Lu'; ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $contact['id']; ?>">
                            <i class="fas fa-eye"></i> Voir
                        </button>
                        <a href="actions/mark-contact.php?id=<?php echo $contact['id']; ?>&status=<?php echo $contact['status'] === 'new' ? 'read' : 'new'; ?>" 
                           class="btn btn-sm btn-warning">
                            <i class="fas fa-check"></i>
                        </a>
                        <form method="POST" action="actions/delete-contact.php" class="d-inline" onsubmit="return confirm('Supprimer ce message ?')">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                            <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>

                <!-- MODAL VUE DÉTAIL -->
                <div class="modal fade" id="viewModal<?php echo $contact['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title"><i class="fas fa-envelope"></i> Message de <?php echo htmlspecialchars($contact['name']); ?></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <strong>De:</strong> <?php echo htmlspecialchars($contact['name']); ?><br>
                                    <strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>"><?php echo htmlspecialchars($contact['email']); ?></a><br>
                                    <?php if (!empty($contact['phone'])): ?>
                                    <strong>Téléphone:</strong> <a href="tel:<?php echo htmlspecialchars($contact['phone']); ?>"><?php echo htmlspecialchars($contact['phone']); ?></a><br>
                                    <?php endif; ?>
                                    <strong>Date:</strong> <?php echo date('d/m/Y H:i', strtotime($contact['created_at'])); ?>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <strong>Sujet:</strong><br>
                                    <?php echo htmlspecialchars($contact['subject'] ?? '(Sans sujet)'); ?>
                                </div>
                                <div class="bg-light p-3 rounded">
                                    <strong>Message:</strong><br>
                                    <?php echo nl2br(htmlspecialchars($contact['message'])); ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>" class="btn btn-primary">
                                    <i class="fas fa-reply"></i> Répondre
                                </a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Aucun message</td></tr>
                <?php endif; } catch (Exception $e) {
                    echo '<tr><td colspan="6" class="alert alert-danger mb-0">' . safeErrorMessage($e) . '</td></tr>';
                } ?>
            </tbody>
        </table>
    </div>
</div>
