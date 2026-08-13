<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Client;

$client = new Client();
$client->setAuthConfig(__DIR__ . '/credentials.json');
$client->setRedirectUri('https://web-production-586de.up.railway.app/oauth2callback');
$client->setScopes(Google\Service\Gmail::GMAIL_SEND);
$client->setAccessType('offline');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    file_put_contents(__DIR__ . '/token.json', json_encode($token));
    echo "✅ Token berhasil disimpan ke token.json";
} else {
    echo "❌ Tidak ada kode otorisasi di URL.";
}
