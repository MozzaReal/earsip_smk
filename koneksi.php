<?php
$host     = "localhost";
$user     = "root";      // Default username MySQL di Laragon
$password = "";          // Default password MySQL di Laragon (kosong)
$database = "db_earsip"; // Nama database yang sudah kamu buat

$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>