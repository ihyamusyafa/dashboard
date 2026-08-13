<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Client;

$client = new Client();
$client->setApplicationName('OTP Mailer');
$client->setScopes(Google\Service\Gmail::GMAIL_SEND);
$client->setAuthConfig(__DIR__ . '/credentials.json');
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

// Generate URL untuk login
$authUrl = $client->createAuthUrl();
echo "Buka link ini di browser:\n$authUrl\n";

// Setelah login, Google redirect ke /oauth2callback dengan kode
echo "Masukkan kode otorisasi dari URL: ";
$authCode = trim(fgets(STDIN));

// Tukar kode jadi token
$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

// Simpan token.json
file_put_contents(__DIR__ . '/token.json', json_encode($accessToken));
echo "Token disimpan ke token.json\n";
