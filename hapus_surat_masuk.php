<?php
session_start();
include 'koneksi.php';

if($_SESSION['level_user'] != 'Admin TU'){
    exit("Akses Ditolak!");
}

$id = $_GET['id'];

$query_file = mysqli_query($koneksi, "SELECT file_scan FROM surat_masuk WHERE id_masuk='$id'");
$data_file = mysqli_fetch_array($query_file);
$nama_file = $data_file['file_scan'];

if(file_exists("berkas_scan/".$nama_file) && $nama_file != ""){
    unlink("berkas_scan/".$nama_file);
}

$hapus = mysqli_query($koneksi, "DELETE FROM surat_masuk WHERE id_masuk='$id'");

if($hapus){
    echo "<script>alert('Data berhasil dihapus!'); window.location='surat_masuk.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data!'); window.location='surat_masuk.php';</script>";
}
?>