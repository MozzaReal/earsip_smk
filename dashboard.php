<?php
session_start();

// Cek apakah user sudah login atau belum
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - E-Arsip</title>
</head>
<body>
    <h1>Selamat Datang, <?= $_SESSION['username']; ?>!</h1>
    <p>Hak Akses Anda: <strong><?= $_SESSION['level_user']; ?></strong></p>
    <hr>
    <a href="logout.php">Logout</a>
</body>
</html>