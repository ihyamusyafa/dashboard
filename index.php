<?php
session_start();


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="icon" href="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'><circle cx='8' cy='8' r='8' fill='%2300b4d8'/></svg>">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>My Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION['user']; ?>!</p>
    <a href="logout.php">Logout</a>
  </header>

  <main>
    <section id="user-table">
      <h2>Users</h2>
      <table>
        <thead>
          <tr><th>ID</th><th>Name</th><th>Email</th></tr>
        </thead>
        <tbody id="users-data">
          <!-- Data dari Supabase akan muncul di sini -->
        </tbody>
      </table>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
  <script src="script.js"></script>
</body>
</html>
