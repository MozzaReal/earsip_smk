<?php
session_start();
include 'koneksi.php';

// Menangkap data yang dikirim dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Menyeleksi data pengguna dengan username dan password yang sesuai
$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($query);

if($cek > 0){
    $data = mysqli_fetch_assoc($query);
    
    // Menyimpan session
    $_SESSION['username'] = $data['username'];
    $_SESSION['level_user'] = $data['level_user'];
    $_SESSION['id_pengguna'] = $data['id_pengguna'];
    
    // Alihkan ke halaman dashboard
    header("location:dashboard.php");
}else{
    // Jika gagal, kembali ke login
    header("location:login.php?pesan=gagal");
}
?>