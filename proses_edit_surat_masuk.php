<?php
session_start();
include 'koneksi.php';

if($_SESSION['level_user'] == ""){
    header("location:login.php?pesan=belum_login");
    exit();
}

if(isset($_POST['update'])){
    $id_masuk      = $_POST['id_masuk'];
    $no_surat      = mysqli_real_escape_string($koneksi, $_POST['no_surat']);
    $tgl_diterima  = mysqli_real_escape_string($koneksi, $_POST['tgl_diterima']);
    $tgl_surat     = mysqli_real_escape_string($koneksi, $_POST['tgl_surat']);
    $asal_instansi = mysqli_real_escape_string($koneksi, $_POST['asal_instansi']);
    $perihal       = mysqli_real_escape_string($koneksi, $_POST['perihal']);

    // Inisialisasi default query jika tidak ada file baru atau upload gagal
    $query = "UPDATE surat_masuk SET no_surat = '$no_surat', tgl_diterima = '$tgl_diterima', tgl_surat = '$tgl_surat', asal_instansi = '$asal_instansi', perihal = '$perihal' WHERE id_masuk = '$id_masuk'";

    $nama_file = $_FILES['file_scan']['name'];
    $tmp_file  = $_FILES['file_scan']['tmp_name'];

    if(!empty($nama_file)){
        $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_baru = 'masuk_' . time() . '.' . $ekstensi;
        $folder = 'berkas_scan/';

        if(move_uploaded_file($tmp_file, $folder . $nama_baru)){
            $query_lama = mysqli_query($koneksi, "SELECT file_scan FROM surat_masuk WHERE id_masuk = '$id_masuk'");
            $d_lama = mysqli_fetch_array($query_lama);
            if(!empty($d_lama['file_scan']) && file_exists($folder . $d_lama['file_scan'])){
                unlink($folder . $d_lama['file_scan']);
            }

            $query = "UPDATE surat_masuk SET no_surat = '$no_surat', tgl_diterima = '$tgl_diterima', tgl_surat = '$tgl_surat', asal_instansi = '$asal_instansi', perihal = '$perihal', file_scan = '$nama_baru' WHERE id_masuk = '$id_masuk'";
        }
    }

    $sql = mysqli_query($koneksi, $query);

    if($sql){
        header("location:surat_masuk.php?pesan=update_berhasil");
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($koneksi);
    }
} else {
    header("location:surat_masuk.php");
    exit();
}
?>