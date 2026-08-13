<?php
session_start();
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputOtp = trim($_POST['otp']);

    if ($inputOtp == $_SESSION['otp']) {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, is_verified) 
                                VALUES (:u, :e, :p, true)");
        $stmt->execute([
            ':u' => $_SESSION['pending_user'],
            ':e' => $_SESSION['pending_email'],
            ':p' => $_SESSION['pending_pass']
        ]);

        unset($_SESSION['otp'], $_SESSION['pending_user'], $_SESSION['pending_email'], $_SESSION['pending_pass']);
        $success = "Akun berhasil terdaftar dan terverifikasi!";
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
