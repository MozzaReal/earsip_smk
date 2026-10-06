<?php
session_start();
include 'koneksi.php';

// Mengambil data dari form login
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);

// Mencari data pengguna di database
$query  = "SELECT * FROM pengguna WHERE username='$username' AND password='$password'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    
    // Menyimpan data login ke dalam Session
    $_SESSION['id_pengguna'] = $data['id_pengguna'];
    $_SESSION['username']    = $data['username'];
    $_SESSION['level_user']   = $data['level_user'];

    // Mengarahkan ke halaman dashboard
    header("Location: dashboard.php");
    exit();
} else {
    // Jika username/password salah
    echo "<script>
            alert('Username atau Password salah!');
            window.location.href='login.php';
          </script>";
}
?>