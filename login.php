<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // contoh validasi sederhana
    if ($username === "ihya" && $password === "12345") {
        $_SESSION['user'] = $username;
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
  <div class="login-container">
    <div class="login-header">
      <img src="fingerprint-icon.png" alt="Fingerprint" class="login-icon">
      <h1>Selamat Datang</h1>
      <p>Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <form method="POST" class="login-form">
      <div class="input-group">
        <input type="text" name="username" placeholder="Username" required>
      </div>
      <div class="input-group">
        <input type="password" name="password" placeholder="Password" required>
      </div>
      <button type="submit" class="login-btn">Login</button>
    </form>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <p class="register-text">
      Belum punya akun? <a href="#">Daftar Sekarang</a>
    </p>
  </div>
</body>
</html>
