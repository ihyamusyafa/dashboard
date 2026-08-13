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
        $mail->Host       = getenv('smtp.gmail.com');       // ex: smtp.sendgrid.net / smtp.gmail.com
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('2311501650@student.budiluhur.ac.id');   // email / apikey
        $mail->Password   = getenv('alabatdrxikpltsf');   // app password / API key
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = getenv('587');       // 587 (STARTTLS) atau 465 (SMTPS)
        $mail->Timeout    = 10;

        // Recipients
       $mail->setFrom('2311501650@student.budiluhur.ac.id', 'LPKBNI System');
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
