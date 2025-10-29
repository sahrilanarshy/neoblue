<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Guru</title>
</head>
<body>
  <h1>Selamat Datang <?php echo $_SESSION['username']; ?>!</h1>
  <a href="../logout.php">Logout</a>
</body>
</html>