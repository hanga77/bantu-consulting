<?php
/**
 * Configuration email SMTP
 * Utilise PHPMailer pour l'envoi d'emails
 */

// Paramètres SMTP
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USER', getenv('SMTP_USER') ?: 'contact@bantu-consulting.com');
define('SMTP_PASS', getenv('SMTP_PASS') ?: 'votre_mot_de_passe_app');
define('SMTP_FROM_NAME', 'Bantu Consulting');

// URLs
define('WEBMAIL_URL', 'https://mail.bantu-consulting.com');
define('WEBMAIL_DOMAIN', 'mail.bantu-consulting.com');

function sendEmail($to, $subject, $htmlBody, $txtBody = null) {
    // Charger PHPMailer via Composer ou inclure manuellement
    try {
        require_once __DIR__ . '/../vendor/autoload.php';
        
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->Port = SMTP_PORT;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        
        // Contenu
        $mail->setFrom(SMTP_USER, SMTP_FROM_NAME);
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $htmlBody;
        $mail->AltBody = $txtBody ?? strip_tags($htmlBody);
        
        // Envoyer
        return $mail->send();
        
    } catch (Exception $e) {
        error_log("Erreur email: " . $e->getMessage());
        return false;
    }
}

function sendNewsletterEmail($email, $name = '') {
    $subject = 'Bienvenue à la Newsletter Bantu Consulting';
    
    $htmlBody = "
    <html>
        <body style='font-family: Poppins, sans-serif; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto;'>
                <div style='background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); color: white; padding: 30px; text-align: center; border-radius: 8px;'>
                    <h1>Bienvenue! 🎉</h1>
                    <p>Merci de vous être abonné à notre newsletter</p>
                </div>
                
                <div style='padding: 30px; background: #f8fafc; border-radius: 8px; margin-top: 20px;'>
                    <h2>À Propos de Bantu Consulting</h2>
                    <p>Nous sommes un cabinet de conseil spécialisé en stratégie et transformation digitale.</p>
                    
                    <h3>Vous recevrez :</h3>
                    <ul>
                        <li>📰 Les dernières actualités du cabinet</li>
                        <li>💡 Des conseils et bonnes pratiques</li>
                        <li>📢 Les nouveaux projets et services</li>
                        <li>🎯 Des offres exclusives pour nos abonnés</li>
                    </ul>
                </div>
                
                <div style='padding: 20px; background: white; border-radius: 8px; margin-top: 20px; text-align: center;'>
                    <p style='color: #64748b;'>
                        Accédez à votre webmail : <br>
                        <a href='" . WEBMAIL_URL . "' style='color: #1e40af; text-decoration: none; font-weight: bold;'>
                            " . WEBMAIL_DOMAIN . "
                        </a>
                    </p>
                </div>
                
                <div style='padding: 20px; text-align: center; color: #64748b; font-size: 12px;'>
                    <p>© 2024 Bantu Consulting. Tous droits réservés.</p>
                    <p>
                        <a href='' style='color: #1e40af; text-decoration: none;'>Se désabonner</a>
                    </p>
                </div>
            </div>
        </body>
    </html>
    ";
    
    return sendEmail($email, $subject, $htmlBody);
}
?>
