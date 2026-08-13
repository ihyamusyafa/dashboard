<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>OAuth2 Setup Check</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .check-item { margin: 15px 0; padding: 10px; border-radius: 6px; }
        .check-ok { background: #d4edda; color: #155724; }
        .check-error { background: #f8d7da; color: #721c24; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body class="login-body">
    <div class="login-container" style="width: 500px;">
        <h1>🔧 OAuth2 Setup Check</h1>
        
        <?php
        $checks = [];
        
        // Check 1: credentials.json exists
        $credentialsPath = __DIR__ . '/credentials.json';
        $checks['credentials_exists'] = file_exists($credentialsPath);
        
        // Check 2: credentials.json valid JSON
        if ($checks['credentials_exists']) {
            $content = file_get_contents($credentialsPath);
            $decoded = json_decode($content, true);
            $checks['credentials_valid'] = !is_null($decoded) && isset($decoded['web']);
            
            if ($checks['credentials_valid']) {
                $checks['has_client_id'] = !empty($decoded['web']['client_id']);
                $checks['has_client_secret'] = !empty($decoded['web']['client_secret']);
                $checks['has_redirect_uris'] = !empty($decoded['web']['redirect_uris']);
                $checks['localhost_in_uris'] = in_array('http://localhost:8080/oauth2callback.php', $decoded['web']['redirect_uris']);
            }
        }
        
        // Check 3: PHPMailer vendor
        $checks['vendor_exists'] = file_exists(__DIR__ . '/vendor/autoload.php');
        
        // Check 4: token.json writable
        $checks['token_writable'] = is_writable(__DIR__);
        
        ?>
        
        <div style="text-align: left; margin-top: 20px;">
            <h3>Checklist:</h3>
            
            <div class="check-item <?php echo $checks['credentials_exists'] ? 'check-ok' : 'check-error'; ?>">
                <?php echo $checks['credentials_exists'] ? '✅' : '❌'; ?>
                <strong>credentials.json exists</strong>
                <br><small>Path: <?php echo htmlspecialchars($credentialsPath); ?></small>
            </div>
            
            <?php if ($checks['credentials_exists']): ?>
                <div class="check-item <?php echo $checks['credentials_valid'] ? 'check-ok' : 'check-error'; ?>">
                    <?php echo $checks['credentials_valid'] ? '✅' : '❌'; ?>
                    <strong>credentials.json is valid JSON</strong>
                </div>
                
                <?php if ($checks['credentials_valid']): ?>
                    <div class="check-item <?php echo $checks['has_client_id'] ? 'check-ok' : 'check-error'; ?>">
                        <?php echo $checks['has_client_id'] ? '✅' : '❌'; ?>
                        <strong>Client ID configured</strong>
                        <br><small><?php echo isset($decoded['web']['client_id']) ? substr($decoded['web']['client_id'], 0, 20) . '...' : 'Missing'; ?></small>
                    </div>
                    
                    <div class="check-item <?php echo $checks['has_client_secret'] ? 'check-ok' : 'check-error'; ?>">
                        <?php echo $checks['has_client_secret'] ? '✅' : '❌'; ?>
                        <strong>Client Secret configured</strong>
                    </div>
                    
                    <div class="check-item <?php echo $checks['has_redirect_uris'] ? 'check-ok' : 'check-error'; ?>">
                        <?php echo $checks['has_redirect_uris'] ? '✅' : '❌'; ?>
                        <strong>Redirect URIs configured</strong>
                        <br><small>
                            <?php foreach ($decoded['web']['redirect_uris'] as $uri): ?>
                                <code><?php echo htmlspecialchars($uri); ?></code><br>
                            <?php endforeach; ?>
                        </small>
                    </div>
                    
                    <div class="check-item <?php echo $checks['localhost_in_uris'] ? 'check-ok' : 'check-error'; ?>">
                        <?php echo $checks['localhost_in_uris'] ? '✅' : '❌'; ?>
                        <strong>Localhost redirect URI added</strong>
                        <br><small>
                            <code>http://localhost:8080/oauth2callback.php</code>
                            <?php if (!$checks['localhost_in_uris']): ?>
                                <br>⚠️ This must be added to Google Cloud Console!
                            <?php endif; ?>
                        </small>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <div class="check-item <?php echo $checks['vendor_exists'] ? 'check-ok' : 'check-error'; ?>">
                <?php echo $checks['vendor_exists'] ? '✅' : '❌'; ?>
                <strong>PHP Dependencies (vendor/autoload.php)</strong>
            </div>
            
            <div class="check-item <?php echo $checks['token_writable'] ? 'check-ok' : 'check-error'; ?>">
                <?php echo $checks['token_writable'] ? '✅' : '❌'; ?>
                <strong>Project folder is writable</strong>
                <br><small>Needed to save token.json</small>
            </div>
        </div>
        
        <div style="margin-top: 30px; padding: 15px; background: #e7f3ff; border-radius: 6px;">
            <h3>⚠️ Important:</h3>
            <p style="font-size: 13px; margin: 0;">
                Untuk error 400 di Google, Anda harus:
                <br>1. Login ke <a href="https://console.cloud.google.com" target="_blank" style="color: #0066cc;">Google Cloud Console</a>
                <br>2. Pilih project: <strong>otp-mailer-505403</strong>
                <br>3. Buka: Credentials → OAuth 2.0 Client ID
                <br>4. Tambahkan ke "Authorized redirect URIs":
                <br><code style="display: inline-block; background: white; padding: 5px 10px; margin: 5px 0;">http://localhost:8080/oauth2callback.php</code>
                <br>5. Klik Save
                <br>6. Download JSON ulang dan replace credentials.json
            </p>
        </div>
        
        <a href="login.php" class="login-btn" style="margin-top: 20px;">← Kembali ke Login</a>
    </div>
</body>
</html>
