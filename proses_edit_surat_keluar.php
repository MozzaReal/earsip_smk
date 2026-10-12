<?php
session_start();
include 'koneksi.php';

$id_keluar = $_POST['id_keluar'];
$no_surat  = $_POST['no_surat'];
$tgl_surat = $_POST['tgl_surat'];
$tujuan    = $_POST['tujuan'];
$perihal   = $_POST['perihal'];

$nama_asli = $_FILES['file_scan']['name'] ?? '';
$tmp_file  = $_FILES['file_scan']['tmp_name'] ?? '';

if (!empty($nama_asli)) {
    // Ambil file lama untuk dihapus dari folder
    $q_lama = mysqli_query($koneksi, "SELECT file_scan FROM surat_keluar WHERE id_keluar = '$id_keluar'");
    $d_lama = mysqli_fetch_array($q_lama);
    if(!empty($d_lama['file_scan']) && file_exists("berkas_scan/" . $d_lama['file_scan'])){
        unlink("berkas_scan/" . $d_lama['file_scan']);
    }

    // Bersihkan nama file baru
    $nama_file_bersih = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $nama_asli);
    $file_baru        = uniqid() . '-' . $nama_file_bersih;
    $path             = "berkas_scan/" . $file_baru;

    if (move_uploaded_file($tmp_file, $path)) {
        $query = "UPDATE surat_keluar SET 
                    no_surat = '$no_surat', 
                    tgl_surat = '$tgl_surat', 
                    tujuan = '$tujuan', 
                    perihal = '$perihal', 
                    file_scan = '$file_baru' 
                  WHERE id_keluar = '$id_keluar'";
    } else {
        echo "Gagal mengunggah file baru ke berkas_scan!";
        exit();
    }
} else {
    $query = "UPDATE surat_keluar SET 
                no_surat = '$no_surat', 
                tgl_surat = '$tgl_surat', 
                tujuan = '$tujuan', 
                perihal = '$perihal' 
              WHERE id_keluar = '$id_keluar'";
}

$sql = mysqli_query($koneksi, $query);

if ($sql) {
    echo "<script>alert('Data berhasil diperbarui!'); window.location='surat_keluar.php';</script>";
    exit();
} else {
    echo "Gagal memperbarui data: " . mysqli_error($koneksi);
}
?>