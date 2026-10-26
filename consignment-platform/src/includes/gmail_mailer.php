<?php
// src/includes/gmail_mailer.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

function sendGmailEmail($toEmail, $toName, $subject, $htmlBody) {
    $mail = new PHPMailer(true);

    try {
        // Gmail SMTP Settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'clintonclifford2293@gmail.com';      // Your full Gmail address
        $mail->Password   = 'mozb jnxw ugnp zhtn'; // The App Password from Step 1
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL
        $mail->Port       = 465;                         // SSL port

        // Recipients
        $mail->setFrom('clintonclifford2293@gmail.com', 'Elephant Logistics');
        $mail->addAddress($toEmail, $toName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Gmail Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}