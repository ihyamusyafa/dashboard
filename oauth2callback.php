<?php
// Suppress deprecation notices in production and enable logging so Railway captures warnings
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

session_start();
require __DIR__ . '/vendor/autoload.php';

use Google\Client;

// Determine redirect URI based on environment
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

// For development on localhost, use localhost callback
if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
    $redirectUri = 'http://localhost:8080/oauth2callback.php';
} else {
    $redirectUri = $protocol . '://' . $host . '/oauth2callback.php';
}

// Check if credentials.json exists
$credentialsPath = __DIR__ . '/credentials.json';
if (!file_exists($credentialsPath)) {
    die('❌ File credentials.json tidak ditemukan. Silakan download dari Google Cloud Console dan letakkan di folder project.');
}

$errorMsg = '';
$successMsg = '';

try {
    $client = new Client();
    $client->setAuthConfig($credentialsPath);
    $client->setRedirectUri($redirectUri);
    $client->setScopes([
        'https://www.googleapis.com/auth/gmail.send',
        'https://www.googleapis.com/auth/gmail.readonly',
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile'
    ]);
    $client->setAccessType('offline');
    $client->setPrompt('consent');

    $authCode = null;

    // Check for authorization code in URL
    if (isset($_GET['code'])) {
        $authCode = $_GET['code'];
    }
    // Check for manual form submission
    elseif (isset($_POST['auth_code'])) {
        $authCode = trim($_POST['auth_code']);
    }

    if ($authCode) {
        try {
            // Exchange authorization code for access token
            $token = $client->fetchAccessTokenWithAuthCode($authCode);
            
            if (isset($token['error'])) {
                $errorMsg = '❌ Error: ' . $token['error'] . ' - ' . (isset($token['error_description']) ? $token['error_description'] : 'Unknown error');
            } else {
                // Save token to file
                $tokenPath = __DIR__ . '/token.json';
                if (file_put_contents($tokenPath, json_encode($token))) {
                    // Get user info from Google
                    $client->setAccessToken($token);
                    $oauth2 = new Google\Service\Oauth2($client);
                    $userInfo = $oauth2->userinfo->get();
                    
                    // Store user info in session
                    $_SESSION['user'] = $userInfo->email;
                    $_SESSION['google_name'] = $userInfo->name;
                    $_SESSION['google_picture'] = $userInfo->picture;
                    
                    $successMsg = '✅ Berhasil login dengan Google! Redirecting...';
                    header('Refresh: 2; url=index.php');
                } else {
                    $errorMsg = '❌ Gagal menyimpan token.json. Pastikan folder memiliki permission untuk write.';
                }
            }
        } catch (Exception $e) {
            $errorMsg = '❌ Error saat exchange token: ' . $e->getMessage();
        }
    }
} catch (Exception $e) {
    $errorMsg = '❌ Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>OAuth2 Callback</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-code { margin-top: 20px; padding: 20px; background: #f9f9f9; border-radius: 8px; }
        .form-code input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; font-family: monospace; box-sizing: border-box; }
        .form-code button { width: 100%; }
        .instructions { background: #e3f2fd; padding: 15px; border-radius: 6px; margin: 20px 0; font-size: 13px; }
        code { background: white; padding: 3px 6px; border-radius: 3px; }
    </style>
</head>
<body class="login-body">
    <div class="login-container" style="width: 500px;">
        <h1>🔐 Google OAuth2</h1>
        
        <?php if ($successMsg): ?>
            <p style="font-size: 14px; padding: 15px; background: #d4edda; color: #155724; border-radius: 6px;">
                <?php echo htmlspecialchars($successMsg); ?>
            </p>
        <?php endif; ?>
        
        <?php if ($errorMsg): ?>
            <p style="font-size: 14px; padding: 15px; background: #f8d7da; color: #721c24; border-radius: 6px;">
                <?php echo htmlspecialchars($errorMsg); ?>
            </p>
        <?php endif; ?>
        
        <?php if (!$successMsg && !isset($_GET['code']) && !isset($_POST['auth_code'])): ?>
            <div class="instructions">
                <strong>📌 Kode Otorisasi dari URL:</strong>
                <p>Ketika Google redirect kembali, URL akan terlihat seperti ini:</p>
                <code style="display: block; background: white; padding: 10px; margin: 10px 0; overflow-x: auto; word-break: break-all;">
http://localhost:8080/oauth2callback.php?code=4/0AY0e...
                </code>
                <p>Salin kode setelah <code>code=</code> dan paste di form di bawah.</p>
            </div>
            
            <form method="POST" class="form-code">
                <label for="auth_code"><strong>Authorization Code:</strong></label>
                <input 
                    type="text" 
                    id="auth_code" 
                    name="auth_code" 
                    placeholder="Paste kode otorisasi (dimulai dengan 4/0AY0e...)"
                    required
                    autocomplete="off"
                >
                <button type="submit" class="login-btn">✓ Submit Kode</button>
            </form>
            
            <div style="text-align: center; margin-top: 20px; font-size: 13px; color: #666;">
                <p><a href="oauth2authorize.php" style="color: #00b4d8; text-decoration: none;"><strong>→ Coba lagi "Login dengan Google"</strong></a></p>
            </div>
        <?php endif; ?>
        
        <a href="login.php" class="login-btn" style="margin-top: 20px; background: #666;">← Kembali</a>
    </div>
</body>
</html>
