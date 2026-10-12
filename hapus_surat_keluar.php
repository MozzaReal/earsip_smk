<?php
session_start();
include 'koneksi.php';

if($_SESSION['level_user'] == ""){
    header("location:login.php?pesan=belum_login");
    exit();
}

$id = $_GET['id'];

// Ambil data file scan untuk dihapus dari folder
$data = mysqli_query($koneksi, "SELECT * FROM surat_keluar WHERE id_keluar = '$id'");
$d = mysqli_fetch_array($data);
$file = $d['file_scan'];

if(!empty($file) && file_exists("berkas_scan/" . $file)){
    unlink("berkas_scan/" . $file);
}

// Hapus data dari database menggunakan id_keluar
$query = mysqli_query($koneksi, "DELETE FROM surat_keluar WHERE id_keluar = '$id'");

if($query){
    echo "<script>alert('Data berhasil dihapus!'); window.location='surat_keluar.php';</script>";
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
?>