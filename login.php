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
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
  </div>
</body>
</html>
