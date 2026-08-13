<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

function sendOtpMail($toEmail, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '2311501650@student.budiluhur.ac.id';
        $mail->Password   = 'alabatdrxikpltsf'; // ganti dengan App Password Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->Timeout    = 10;

        $mail->setFrom('2311501650@student.budiluhur.ac.id', 'LPKBNI System');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Kode OTP Registrasi';
        $mail->Body    = "Halo, berikut kode OTP kamu: <b>$otp</b>";

        return $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
