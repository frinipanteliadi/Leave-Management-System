<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require_once '../vendor/autoload.php';

/**
 * @param $recipient_mail
 * @param $recipient_name
 * @param $subject
 * @param $body
 */
function sendMail($recipient_mail, $recipient_name, $subject, $body)
{
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        configureSettings($mail);

        setSenderFromSession($mail);
        $mail->addAddress($recipient_mail, $recipient_name);

        // Content
        $mail->Subject = $subject;

        $mail->Body = $body;

        clearApplicationSession();

        $mail->send();

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

/**
 * @param PHPMailer $mail
 */
function configureSettings(PHPMailer $mail)
{
    $MAIL_HOST = 'smtp.mailtrap.io';
    $MAIL_USERNAME = '84bac02d62fc09';
    $MAIL_PASSWORD = '18d5dd1b571996';

    $mail->isSMTP();                                                // Send using SMTP
    $mail->SMTPDebug = 0;                                             // Enable verbose debug output
    $mail->SMTPAuth = true;                                         // Enable SMTP authentication
    $mail->Host = $MAIL_HOST;                                       // Set the SMTP server to send through
    $mail->Username = $MAIL_USERNAME;                               // SMTP username
    $mail->Password = $MAIL_PASSWORD;                               // SMTP password
    $mail->SMTPSecure = 'tls';                                      // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port = 2525;                                             // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    $mail->isHTML(true);                                            // Set email format to HTML
}

function setSenderFromSession(PHPMailer $mail)
{
    $mail->setFrom($_SESSION['email'], $_SESSION['first_name'] . " " . $_SESSION['last_name']);
}

function clearApplicationSession()
{
    if (isset($_SESSION['start']))
        unset($_SESSION['start']);
    if (isset($_SESSION['end']))
        unset($_SESSION['end']);
    if (isset($_SESSION['reason']))
        unset($_SESSION['reason']);
}
