<h2>Messages de Contact</h2>

<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
        if (!empty($contacts)):
            foreach ($contacts as $contact):
        ?>
        <tr>
            <td><?php echo htmlspecialchars($contact['name']); ?></td>
            <td><?php echo htmlspecialchars($contact['email']); ?></td>
            <td><?php echo htmlspecialchars(substr($contact['message'], 0, 50)) . '...'; ?></td>
            <td><?php echo date('d/m/Y H:i', strtotime($contact['created_at'])); ?></td>
            <td>
                <a href="actions/delete-contact.php?id=<?php echo $contact['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="5" class="text-center text-muted">Aucun message</td></tr>
        <?php endif; ?>
    </tbody>
</table>
