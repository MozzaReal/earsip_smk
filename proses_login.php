<?php
session_start();
include 'koneksi.php';


$username = $_POST['username'];
$password = $_POST['password'];


$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($query);

if($cek > 0){
    $data = mysqli_fetch_assoc($query);
    
    
    $_SESSION['username'] = $data['username'];
    $_SESSION['level_user'] = $data['level_user'];
    $_SESSION['id_pengguna'] = $data['id_pengguna'];
    
    
    header("location:dashboard.php");
}else{
    
    header("location:login.php?pesan=gagal");
}
?>