<?php
session_start();
include 'koneksi.php';

if($_SESSION['level_user'] != 'Admin TU'){
    exit("Akses Ditolak!");
}

$id_masuk      = $_POST['id_masuk'];
$no_surat      = $_POST['no_surat'];
$tgl_diterima  = $_POST['tgl_diterima'];
$tgl_surat     = $_POST['tgl_surat'];
$asal_instansi = $_POST['asal_instansi'];
$perihal       = $_POST['perihal'];
$file_lama     = $_POST['file_lama'];

if($_FILES['file_scan']['name'] != ""){
    $ekstensi_diperbolehkan = array('pdf','jpg','png','jpeg');
    $nama_file = $_FILES['file_scan']['name'];
    $x = explode('.', $nama_file);
    $ekstensi = strtolower(end($x));
    $ukuran = $_FILES['file_scan']['size'];
    $file_tmp = $_FILES['file_scan']['tmp_name'];
    $nama_file_baru = time() . '-' . $nama_file;

    if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
        if($ukuran < 5000000){
            if(file_exists("berkas_scan/".$file_lama)){
                unlink("berkas_scan/".$file_lama);
            }
            
            move_uploaded_file($file_tmp, 'berkas_scan/'.$nama_file_baru);
            
            $query = "UPDATE surat_masuk SET no_surat='$no_surat', tgl_diterima='$tgl_diterima', tgl_surat='$tgl_surat', asal_instansi='$asal_instansi', perihal='$perihal', file_scan='$nama_file_baru' WHERE id_masuk='$id_masuk'";
        } else {
            echo "<script>alert('Ukuran file terlalu besar!'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('Ekstensi file tidak diperbolehkan!'); window.history.back();</script>";
        exit();
    }
} else {
    $query = "UPDATE surat_masuk SET no_surat='$no_surat', tgl_diterima='$tgl_diterima', tgl_surat='$tgl_surat', asal_instansi='$asal_instansi', perihal='$perihal' WHERE id_masuk='$id_masuk'";
}

$update = mysqli_query($koneksi, $query);

if($update){
    echo "<script>alert('Data berhasil diubah!'); window.location='surat_masuk.php';</script>";
} else {
    echo "<script>alert('Gagal mengubah data!'); window.history.back();</script>";
}
?>