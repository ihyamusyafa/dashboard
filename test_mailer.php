<?php
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/mailer.php';

echo "<h1>🧪 Test Gmail API Email</h1>";
echo "<pre style='background: #f5f5f5; padding: 15px; border-radius: 8px;'>";

// Check token.json
$tokenPath = __DIR__ . '/token.json';
if (!file_exists($tokenPath)) {
    echo "❌ token.json NOT FOUND!\n";
    echo "Solution: Login dengan Google di /login.php dulu untuk generate token.\n\n";
} else {
    echo "✅ token.json exists\n";
    $token = json_decode(file_get_contents($tokenPath), true);
    echo "   - Access Token: " . (isset($token['access_token']) ? '✓' : '✗') . "\n";
    echo "   - Refresh Token: " . (isset($token['refresh_token']) ? '✓' : '✗') . "\n";
    echo "   - Expires: " . (isset($token['expires_in']) ? $token['expires_in'] . 's' : '?') . "\n\n";
}

// Check credentials.json
$credentialsPath = __DIR__ . '/credentials.json';
if (!file_exists($credentialsPath)) {
    echo "❌ credentials.json NOT FOUND!\n\n";
} else {
    echo "✅ credentials.json exists\n\n";
}

$testEmail = 'test@example.com';
$testOtp = '123456';

echo "=== Testing Email Send ===\n";
echo "To: $testEmail\n";
echo "OTP: $testOtp\n\n";

if (file_exists($tokenPath)) {
    if (sendOtpMail($testEmail, $testOtp)) {
        echo "✅ Email BERHASIL dikirim ke Gmail!\n";
    } else {
        echo "❌ Email GAGAL dikirim!\n";
        echo "Check Railway logs atau PHP error_log untuk detail.\n";
    }
} else {
    echo "⚠️ Skipped - token.json tidak ada\n";
}

echo "</pre>";

echo "<h2>📋 Status Checklist:</h2>";
echo "<ul>";
echo "<li>" . (file_exists($credentialsPath) ? "✅" : "❌") . " credentials.json\n";
echo "<li>" . (file_exists($tokenPath) ? "✅" : "❌") . " token.json\n";
echo "<li>Pastikan sudah login dengan Google di <a href='login.php'>Login Page</a>\n";
echo "</ul>";

echo "<hr>";
echo "<p><a href='login.php'>← Login dengan Google</a> | <a href='register.php'>Daftar</a></p>";
?>
