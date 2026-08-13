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

        // Server settings - use environment variables or fallback to hardcoded
        $mail->isSMTP();
        $mail->Host       = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('SMTP_USERNAME') ?: '2311501650@student.budiluhur.ac.id';
        $mail->Password   = getenv('SMTP_PASSWORD') ?: 'alabatdrxikpltsf';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          // Use SMTPS instead of STARTTLS
        $mail->Port       = (int)(getenv('SMTP_PORT') ?: 465);    // SMTPS port (was 587)
        $mail->Timeout    = 10;

        // Recipients
        $senderEmail = getenv('SMTP_USERNAME') ?: '2311501650@student.budiluhur.ac.id';
        $senderName = getenv('SENDER_NAME') ?: 'LPKBNI System';
        $mail->setFrom($senderEmail, $senderName);
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Kode OTP Registrasi';
        $mail->Body    = "Halo, berikut kode OTP kamu: <b>$otp</b>";

        return $mail->send();
    } catch (Exception $e) {
        $errorMsg = "Mailer Error: " . $mail->ErrorInfo;
        error_log($errorMsg);
        error_log("Exception: " . $e->getMessage());
        return false;
    }
}
?>
