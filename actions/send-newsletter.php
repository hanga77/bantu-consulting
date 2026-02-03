<?php
session_start();
require_once '../config/database.php';
require_once '../config/mail-config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../?page=admin-login');
    exit;
}

try {
    $subject = trim($_POST['subject'] ?? '');
    $content = $_POST['content'] ?? '';
    $recipients = $_POST['recipients'] ?? 'active';
    $test_email = isset($_POST['test_email']);

    if (empty($subject) || empty($content)) {
        throw new Exception('Sujet et contenu sont obligatoires');
    }

    // Récupérer les destinataires
    if ($recipients === 'active') {
        $query = "SELECT * FROM newsletter_subscribers WHERE status = 'active'";
    } else {
        $query = "SELECT * FROM newsletter_subscribers";
    }

    $subscribers = $pdo->query($query)->fetchAll();

    if (empty($subscribers)) {
        throw new Exception('Aucun destinataire trouvé');
    }

    $sent_count = 0;
    $failed_count = 0;

    // Si test, envoyer seulement à l'admin
    if ($test_email) {
        $test_recipients = [
            ['email' => $_SESSION['user_email'] ?? 'admin@bantu-consulting.com', 'name' => 'Admin Test']
        ];
    } else {
        $test_recipients = $subscribers;
    }

    foreach ($test_recipients as $subscriber) {
        try {
            $html_content = str_replace(
                ['{email}', '{name}', '{subject}'],
                [$subscriber['email'], $subscriber['name'] ?? 'Subscriber', $subject],
                $content
            );

            $email_sent = sendEmail(
                $subscriber['email'],
                $subject,
                $html_content
            );

            if ($email_sent) {
                $sent_count++;
            } else {
                $failed_count++;
            }
        } catch (Exception $e) {
            $failed_count++;
            error_log("Newsletter send failed for {$subscriber['email']}: " . $e->getMessage());
        }
    }

    if ($test_email) {
        $_SESSION['success'] = "Email de test envoyé avec succès!";
    } else {
        $_SESSION['success'] = "Newsletter envoyée: $sent_count réussis, $failed_count échoués";
    }

} catch (Exception $e) {
    $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
}

header('Location: ../?page=admin-dashboard&section=newsletter');
exit;
?>
