# Setup Google OAuth2 untuk Login

## Langkah-Langkah Setup

### 1. Buka Google Cloud Console
- Kunjungi: https://console.cloud.google.com
- Login dengan akun Google

### 2. Buat Project Baru (jika belum ada)
- Klik "Create Project"
- Nama: "LPKBNI Dashboard"
- Klik "Create"

### 3. Enable Gmail API & Google+ API
- Di search bar, cari "Gmail API"
- Klik "Enable"
- Kembali ke search, cari "Google+ API" atau "Google People API"
- Klik "Enable"

### 4. Buat OAuth2 Credentials
- Di sidebar, pilih "Credentials"
- Klik "Create Credentials" → "OAuth client ID"
- Pilih "Web application"
- Nama: "LPKBNI Dashboard"

### 5. Atur Authorized Redirect URIs
Tambahkan kedua URI ini:
```
http://localhost:8080/oauth2callback.php
https://web-production-586de.up.railway.app/oauth2callback.php
```

**PENTING**: Gunakan `http://` untuk localhost (bukan https)

### 6. Download Credentials
- Klik download icon (JSON format)
- File akan bernama: `client_secret_*.json`
- Rename menjadi: `credentials.json`
- Letakkan di folder project: `d:\Project\dashboard\`

### 7. Update Credentials File
Pastikan struktur file `credentials.json` adalah:
```json
{
  "web": {
    "client_id": "YOUR_CLIENT_ID.apps.googleusercontent.com",
    "project_id": "YOUR_PROJECT_ID",
    "auth_uri": "https://accounts.google.com/o/oauth2/auth",
    "token_uri": "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
    "client_secret": "YOUR_CLIENT_SECRET",
    "redirect_uris": [
      "http://localhost:8080/oauth2callback.php",
      "https://web-production-586de.up.railway.app/oauth2callback.php"
    ]
  }
}
```

## Testing

1. Buka: `http://localhost:8080/login.php`
2. Klik "Login dengan Google"
3. Seharusnya redirect ke halaman Google Login
4. Login dengan akun Google
5. Approve permissions
6. Akan redirect kembali ke aplikasi dengan session Google terupdate

## Troubleshooting

### Error 400: Invalid Request
- **Penyebab**: Redirect URI tidak sesuai dengan Google Cloud Console
- **Solusi**: 
  - Update di Google Cloud Console dengan URI yang tepat
  - Download credentials JSON baru
  - Replace file `credentials.json`

### Error: Invalid Scope
- **Penyebab**: Scope tidak ter-enable di Google Cloud
- **Solusi**: Enable Gmail API dan Google+ API di Google Cloud Console

### Token tidak tersimpan
- **Penyebab**: Folder tidak memiliki write permission
- **Solusi**: Berikan permission write ke folder project untuk PHP

## Support
Untuk bantuan lebih lanjut, lihat dokumentasi:
- Google OAuth2: https://developers.google.com/identity/protocols/oauth2
- Gmail API: https://developers.google.com/gmail/api
