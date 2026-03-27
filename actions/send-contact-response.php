<?php
session_start();
require_once '../config/database.php';
require_once '../config/mail-config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Token de sécurité invalide.';
    header('Location: ../?page=admin-dashboard&section=contacts');
    exit;
}

$status_filter = $_POST['status_filter'] ?? 'new';
$subject = trim($_POST['subject'] ?? '');
$response_message = trim($_POST['response_message'] ?? '');

if (empty($subject) || empty($response_message)) {
    $_SESSION['error'] = 'Le sujet et le message sont obligatoires';
    header('Location: ../?page=admin-dashboard&section=contacts&action=send-response');
    exit;
}

try {
    // Déterminer les destinataires
    $allowed_statuses = ['new', 'read'];
    if (in_array($status_filter, $allowed_statuses)) {
        $stmt = $pdo->prepare("SELECT id, email, name FROM contacts WHERE status = ?");
        $stmt->execute([$status_filter]);
    } else {
        $stmt = $pdo->query("SELECT id, email, name FROM contacts");
    }
    $contacts = $stmt->fetchAll();
    
    if (empty($contacts)) {
        $_SESSION['error'] = 'Aucun contact trouvé';
        header('Location: ../?page=admin-dashboard&section=contacts&action=send-response');
        exit;
    }
    
    $sent_count = 0;
    $failed_count = 0;
    $contact_ids = [];
    
    foreach ($contacts as $contact) {
        try {
            // Remplacer {{name}} par le nom du contact
            $personalized_message = str_replace('{{name}}', htmlspecialchars($contact['name']), $response_message);
            
            $htmlBody = "
            <html>
                <body style='font-family: Poppins, sans-serif; color: #333;'>
                    <div style='max-width: 600px; margin: 0 auto;'>
                        <div style='background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); color: white; padding: 20px; text-align: center; border-radius: 8px;'>
                            <h2>Bantu Consulting</h2>
                        </div>
                        
                        <div style='padding: 30px; background: #f8fafc; border-radius: 8px; margin-top: 20px;'>
                            <p>Bonjour " . htmlspecialchars($contact['name']) . ",</p>
                            <div style='line-height: 1.6;'>
                                " . nl2br($personalized_message) . "
                            </div>
                        </div>
                        
                        <div style='padding: 20px; text-align: center; color: #64748b; font-size: 12px;'>
                            <hr style='border: none; border-top: 1px solid #e2e8f0;'>
                            <p>© 2024 Bantu Consulting. Tous droits réservés.</p>
                        </div>
                    </div>
                </body>
            </html>
            ";
            
            if (sendEmail($contact['email'], $subject, $htmlBody)) {
                $sent_count++;
                $contact_ids[] = $contact['id'];
            } else {
                $failed_count++;
            }
        } catch (Exception $e) {
            $failed_count++;
        }
    }
    
    // Marquer comme lus
    if (!empty($contact_ids)) {
        $placeholders = implode(',', array_fill(0, count($contact_ids), '?'));
        $stmt = $pdo->prepare("UPDATE contacts SET status = 'read' WHERE id IN ($placeholders)");
        $stmt->execute(array_map('intval', $contact_ids));
    }
    
    $_SESSION['success'] = "Réponses envoyées! ($sent_count envoyées, $failed_count erreurs)";
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=contacts');
exit;
?>
