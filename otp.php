<?php
session_start();
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputOtp = trim($_POST['otp']);

    if ($inputOtp == $_SESSION['otp']) {
        // update status user jadi verified
        $email = $_SESSION['pending_email'];
        $stmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // set session login
        $_SESSION['user'] = $_SESSION['pending_user'];

        // bersihkan session otp
        unset($_SESSION['otp']);
        unset($_SESSION['pending_user']);
        unset($_SESSION['pending_email']);

        header("Location: index.php");
        exit;
    } else {
        $error = "Kode OTP salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Verifikasi OTP</title>
  <link rel="icon" href="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><circle cx='8' cy='8' r='8' fill='%2300b4d8'/></svg>">
  <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
  <div class="login-container">
    <div class="login-header">
      <img src="fingerprint-icon.png" alt="Fingerprint" class="login-icon">
      <h1>Verifikasi Akun</h1>
      <p>Masukkan kode OTP yang dikirim ke email kamu</p>
    </div>

    <form method="POST" class="login-form">
      <div class="input-group">
        <input type="text" name="otp" placeholder="Masukkan kode OTP" required>
      </div>
      <button type="submit" class="login-btn">Verifikasi</button>
    </form>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
  </div>
</body>
</html>
