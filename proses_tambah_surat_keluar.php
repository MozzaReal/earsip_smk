<?php
session_start();
include 'koneksi.php';

$no_surat    = $_POST['no_surat'] ?? '';
$tgl_surat   = !empty($_POST['tgl_surat']) ? $_POST['tgl_surat'] : date('Y-m-d');
$tujuan      = $_POST['tujuan'] ?? '';
$perihal     = $_POST['perihal'] ?? '';
$id_pengguna = $_SESSION['id_pengguna'] ?? 1;

$nama_asli   = $_FILES['file_scan']['name'] ?? '';
$tmp_file    = $_FILES['file_scan']['tmp_name'] ?? '';

if (!empty($nama_asli)) {
    // Bersihkan nama file dari spasi dan simbol aneh (seperti +) agar tidak 404
    $nama_file_bersih = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $nama_asli);
    $file_baru        = uniqid() . '-' . $nama_file_bersih;
    $path             = "berkas_scan/" . $file_baru;

    if (move_uploaded_file($tmp_file, $path)) {
        $query = "INSERT INTO surat_keluar (id_pengguna, no_surat, tgl_surat, tujuan, perihal, file_scan) 
                  VALUES ('$id_pengguna', '$no_surat', '$tgl_surat', '$tujuan', '$perihal', '$file_baru')";
        
        $sql = mysqli_query($koneksi, $query);

        if ($sql) {
            echo "<script>alert('Data berhasil disimpan!'); window.location='surat_keluar.php';</script>";
            exit();
        } else {
            echo "Gagal menyimpan ke database: " . mysqli_error($koneksi);
        }
    } else {
        echo "Gagal mengunggah file ke folder berkas_scan!";
    }
} else {
    echo "File scan belum diunggah!";
}
?>