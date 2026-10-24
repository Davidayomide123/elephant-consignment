<?php
// src/includes/mailer.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

function sendAlertEmail($toEmail, $toName, $trackingCode, $location, $statusMessage) {
    $mail = new PHPMailer(true);

    try {
       // Replace the SMTP settings block with this:
$mail->isSMTP(); 
$mail->Host       = 'smtp-relay.brevo.com'; 
$mail->SMTPAuth   = true;
$mail->Username   = 'dpere0765@gmail.com'; // Your Brevo login
$mail->Password   = 'xkeysib-3ce32cccffbefa155f5bd6281ee5e6c794f9180bd9ffa314b9544baf81'; // Paste the API key here
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587;

        // Recipient
        $mail->addAddress($toEmail, $toName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Action Required: Update on your shipment ' . $trackingCode;
        
        // Elephant Breaths styled HTML email
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; background-color: #f5f4f1; padding: 40px; color: #3f3c34;'>
                <div style='max-width: 600px; margin: 0 auto; background-color: #eae7e1; padding: 30px; border-radius: 20px; border: 1px solid #d6d1c7;'>
                    <h2 style='color: #6f8569; margin-top: 0;'>Hello {$toName},</h2>
                    <p>Your package (<strong>{$trackingCode}</strong>) has arrived at <strong>{$location}</strong>.</p>
                    
                    <div style='background-color: #fdf2f2; border-left: 4px solid #ef4444; padding: 15px; margin: 20px 0; border-radius: 4px;'>
                        <p style='margin: 0; color: #b91c1c; font-weight: bold;'>Action Required</p>
                        <p style='margin: 5px 0 0 0; color: #7f1d1d;'>{$statusMessage}</p>
                    </div>

                    <p>Please contact our support team immediately to resolve this and ensure your goods continue their journey safely.</p>
                    
                    <a href='http://localhost:8000/dashboard/consignor.php' style='display: inline-block; background-color: #6f8569; color: white; padding: 12px 24px; text-decoration: none; border-radius: 25px; font-weight: bold; margin-top: 15px;'>Go to Dashboard</a>
                    
                    <p style='font-size: 12px; color: #948d7e; margin-top: 30px;'>Breathe in. Breathe out. We'll get this sorted.</p>
                </div>
            </div>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}