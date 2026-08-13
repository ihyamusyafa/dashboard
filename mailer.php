<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Gmail;

function sendOtpMail($toEmail, $otp) {
    try {
        // Setup Google Client
        $client = new Client();
        $client->setAuthConfig(__DIR__ . '/credentials.json'); // file dari Google Cloud
        $client->addScope(Gmail::GMAIL_SEND);
        $client->setAccessType('offline');

        // Load token.json
        $tokenPath = __DIR__ . '/token.json';
        if (!file_exists($tokenPath)) {
            throw new Exception("Token file not found. Jalankan getToken.php dulu.");
        }
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);

        // Refresh token kalau expired
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        // Gmail service
        $gmail = new Gmail($client);

        // Build raw email
        $rawMessage = "From: LPKBNI System <2311501650@student.budiluhur.ac.id>\r\n";
        $rawMessage .= "To: $toEmail\r\n";
        $rawMessage .= "Subject: Kode OTP Registrasi\r\n";
        $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $rawMessage .= "Halo, berikut kode OTP kamu: <b>$otp</b>";

        // Encode ke base64url
        $raw = rtrim(strtr(base64_encode($rawMessage), '+/', '-_'), '=');

        $message = new Gmail\Message();
        $message->setRaw($raw);

        // Kirim email
        $gmail->users_messages->send('me', $message);

        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $e->getMessage());
        return false;
    }
}
?>
