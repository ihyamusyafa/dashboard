<?php
session_start();
include 'config/database.php'; // koneksi pakai PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = trim($_POST['password']);
    $confirmPwd = trim($_POST['confirm_password']);

    if ($password !== $confirmPwd) {
        $error = "Password dan konfirmasi tidak sama!";
    } else {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, is_verified) 
                                    VALUES (:u, :e, :p, false)");
            $success = $stmt->execute([
                ':u' => $username,
                ':e' => $email,
                ':p' => $hashedPassword
            ]);

            if ($success) {
                // generate OTP
                $otp = rand(100000, 999999);
                $_SESSION['pending_user'] = $username;
                $_SESSION['pending_email'] = $email;
                $_SESSION['otp'] = $otp;

                // sementara skip mail() dulu biar nggak error di Railway
                // nanti diganti PHPMailer + SMTP

                header("Location: otp.php");
                exit;
            } else {
                $error = "Gagal daftar: " . implode(" | ", $stmt->errorInfo());
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="icon" href="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><circle cx='8' cy='8' r='8' fill='%2300b4d8'/></svg>">
  <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
  <div class="login-container">
    <div class="login-header">
      <img src="fingerprint-icon.png" alt="Fingerprint" class="login-icon">
      <h1>Daftar Akun Baru</h1>
      <p>Isi form di bawah untuk membuat akun</p>
    </div>

    <form method="POST" class="login-form">
      <div class="input-group">
        <input type="text" name="username" placeholder="Username" required>
      </div>
      <div class="input-group">
        <input type="email" name="email" placeholder="Email" required>
      </div>
      <div class="input-group">
        <input type="password" name="password" placeholder="Password" required>
      </div>
      <div class="input-group">
        <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
      </div>
      <button type="submit" class="login-btn">Daftar</button>
    </form>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <p class="register-text">
      Sudah punya akun? <a href="login.php">Login di sini</a>
    </p>
  </div>
</body>
</html>
