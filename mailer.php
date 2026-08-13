<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

function sendOtpMail($toEmail, $otp) {
    $mail = new PHPMailer(true);
    try {
        // Debug (0 = off, 2 = verbose)
        $mail->SMTPDebug  = 0;
        $mail->Debugoutput = function($str, $level) {
            error_log("SMTP: $str");
        };

        // Server settings
        $mailHost     = getenv('MAIL_HOST') ?: 'smtp.gmail.com';
        $mailUsername = getenv('MAIL_USERNAME');
        $mailPassword = getenv('MAIL_PASSWORD');

        $mail->isSMTP();
        $mail->Host       = $mailHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $mailUsername;
        $mail->Password   = $mailPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          // Gunakan SMTPS di port 465
        $mail->Port       = 465;
        $mail->Timeout    = 10;

        // Recipients
        $mail->setFrom($mailUsername, 'LPKBNI System');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Kode OTP Registrasi';
        $mail->Body    = "Halo, berikut kode OTP kamu: <b>$otp</b>";

        return $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>
