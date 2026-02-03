<?php
session_start();
require_once '../config/database.php';
require_once '../config/mail-config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

$recipient = $_POST['recipient'] ?? 'active';
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($subject) || empty($message)) {
    $_SESSION['error'] = 'Le sujet et le message sont obligatoires';
    header('Location: ../?page=admin-dashboard&section=newsletter&action=send');
    exit;
}

try {
    // Déterminer les destinataires
    $query = "SELECT email, name FROM newsletter_subscribers WHERE 1=1";
    
    if ($recipient === 'active') {
        $query .= " AND status = 'active'";
    } elseif ($recipient === 'inactive') {
        $query .= " AND status = 'inactive'";
    }
    
    $subscribers = $pdo->query($query)->fetchAll();
    
    if (empty($subscribers)) {
        $_SESSION['error'] = 'Aucun abonné trouvé';
        header('Location: ../?page=admin-dashboard&section=newsletter&action=send');
        exit;
    }
    
    $sent_count = 0;
    $failed_count = 0;
    
    foreach ($subscribers as $sub) {
        try {
            $htmlBody = "
            <html>
                <body style='font-family: Poppins, sans-serif; color: #333;'>
                    <div style='max-width: 600px; margin: 0 auto;'>
                        <div style='background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); color: white; padding: 20px; text-align: center; border-radius: 8px;'>
                            <h2>Bantu Consulting</h2>
                        </div>
                        
                        <div style='padding: 30px; background: #f8fafc; border-radius: 8px; margin-top: 20px;'>
                            <p>Bonjour " . htmlspecialchars($sub['name'] ?? 'Abonné') . ",</p>
                            <div style='line-height: 1.6;'>
                                " . nl2br(htmlspecialchars($message)) . "
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
            
            if (sendEmail($sub['email'], $subject, $htmlBody)) {
                $sent_count++;
            } else {
                $failed_count++;
            }
        } catch (Exception $e) {
            $failed_count++;
        }
    }
    
    $_SESSION['success'] = "Emails envoyés avec succès! ($sent_count envoyés, $failed_count erreurs)";
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=newsletter');
exit;
?>
