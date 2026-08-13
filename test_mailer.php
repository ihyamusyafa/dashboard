<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/mailer.php';

echo "<h1>🧪 Test SMTP Email</h1>";
echo "<pre>";

$testEmail = 'test@example.com';
$testOtp = '123456';

echo "Mengirim OTP test ke: $testEmail\n";
echo "OTP Code: $testOtp\n\n";

if (sendOtpMail($testEmail, $testOtp)) {
    echo "✅ Email BERHASIL dikirim!\n";
} else {
    echo "❌ Email GAGAL dikirim!\n";
    echo "Periksa error_log untuk detail error.\n";
}

echo "</pre>";

echo "<h2>Environment Variables:</h2>";
echo "<pre>";
echo "SMTP_HOST: " . (getenv('SMTP_HOST') ?: 'NOT SET') . "\n";
echo "SMTP_USERNAME: " . (getenv('SMTP_USERNAME') ?: 'NOT SET') . "\n";
echo "SMTP_PASSWORD: " . (getenv('SMTP_PASSWORD') ? '✓ SET' : 'NOT SET') . "\n";
echo "SMTP_PORT: " . (getenv('SMTP_PORT') ?: 'NOT SET') . "\n";
echo "SMTP_DEBUG: " . (getenv('SMTP_DEBUG') ?: '0 (disabled)') . "\n";
echo "</pre>";

echo "<hr>";
echo "<p><a href='register.php'>← Kembali ke Register</a></p>";
?>
