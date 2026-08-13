<?php
session_start();
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputOtp = trim($_POST['otp']);

    if ($inputOtp == $_SESSION['otp']) {
        try {
            $stmt = $conn->prepare("UPDATE users SET is_verified = true WHERE email = :e");
            $stmt->execute([':e' => $_SESSION['pending_email']]);

            // bersihkan session OTP
            unset($_SESSION['otp']);
            unset($_SESSION['pending_email']);
            unset($_SESSION['pending_user']);

            $success = "Akun berhasil diverifikasi! Silakan login.";
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
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
  <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
  <div class="login-container">
    <h1>Verifikasi OTP</h1>
    <form method="POST">
      <div class="input-group">
        <input type="text" name="otp" placeholder="Masukkan kode OTP" required>
      </div>
      <button type="submit" class="login-btn">Verifikasi</button>
    </form>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
  </div>
</body>
</html>
