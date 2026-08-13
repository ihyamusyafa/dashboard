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
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';          // SMTP host
        $mail->SMTPAuth   = true;
        $mail->Username   = '2311501650@student.budiluhur.ac.id'; // email kamu
        $mail->Password   = 'alabatdrxikpltsf';            // App Password Gmail (bukan password biasa)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // kalau gagal, coba STARTTLS
        $mail->Port       = 587;                        // kalau gagal, coba 587
        $mail->Timeout    = 10;                         // maksimal 10 detik

        // Recipients
        $mail->setFrom('2311501650@student.budiluhur.ac.id', 'LPKBNI System');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Kode OTP Registrasi';
        $mail->Body    = "Halo, berikut kode OTP kamu: <b>$otp</b>";

        return $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $e->getMessage());
        return false;
    }
}
?>
