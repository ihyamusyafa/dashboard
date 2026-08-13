<?php
// Suppress deprecation notices and log errors in production so vendor warnings don't break redirects
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

session_start();
require __DIR__ . '/vendor/autoload.php';

use Google\Client;

// Check if credentials.json exists
$credentialsPath = __DIR__ . '/credentials.json';
if (!file_exists($credentialsPath)) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Error - Credentials Missing</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="login-body">
        <div class="login-container">
            <h1>⚠️ Credentials.json Tidak Ditemukan</h1>
            <p>Untuk menggunakan fitur Login dengan Google, Anda perlu:</p>
            <ol style="text-align: left; margin: 20px 0;">
                <li>Buka <a href="https://console.cloud.google.com" target="_blank" style="color: #00b4d8;">Google Cloud Console</a></li>
                <li>Buat project baru atau pilih yang sudah ada</li>
                <li>Enable Gmail API dan Google+ API</li>
                <li>Buat OAuth 2.0 Client ID (Desktop app)</li>
                <li>Download credentials JSON</li>
                <li>Rename menjadi <code>credentials.json</code></li>
                <li>Letakkan di folder project: <code><?php echo htmlspecialchars(__DIR__); ?></code></li>
            </ol>
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                Current redirect URI: <code>http://<?php echo $_SERVER['HTTP_HOST']; ?>/oauth2callback.php</code>
            </p>
            <a href="login.php" class="login-btn">← Kembali ke Login</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

try {
    $client = new Client();
    $client->setAuthConfig($credentialsPath);
    
    // Determine redirect URI based on environment
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    
    // For development on localhost, use localhost callback
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        $redirectUri = 'http://localhost:8080/oauth2callback.php';
    } else {
        $redirectUri = $protocol . '://' . $host . '/oauth2callback.php';
    }
    
    $client->setRedirectUri($redirectUri);
    $client->setScopes([
        'https://www.googleapis.com/auth/gmail.send',
        'https://www.googleapis.com/auth/gmail.readonly',
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile'
    ]);
    $client->setAccessType('offline');
    $client->setPrompt('consent');

    // Generate authorization URL
    $authUrl = $client->createAuthUrl();
    
    // Debug info (remove in production)
    $_SESSION['oauth_debug'] = [
        'redirect_uri' => $redirectUri,
        'auth_url' => substr($authUrl, 0, 100) . '...'
    ];
    
    // Redirect to Google authorization page
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit;
} catch (Exception $e) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Error - OAuth2</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="login-body">
        <div class="login-container">
            <h1>❌ Error OAuth2</h1>
            <p style="color: #ff4d4d; word-break: break-all;">
                <?php echo htmlspecialchars($e->getMessage()); ?>
            </p>
            <p style="font-size: 12px; color: #999; margin-top: 20px;">
                Credentials path: <?php echo htmlspecialchars($credentialsPath); ?>
            </p>
            <a href="login.php" class="login-btn">← Kembali ke Login</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>
