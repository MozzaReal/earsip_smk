<?php
session_start();
include 'koneksi.php';


if($_SESSION['level_user'] != 'Admin TU'){
    exit("Akses Ditolak!");
}


$id_pengguna   = $_SESSION['id_pengguna']; 
$no_surat      = $_POST['no_surat'];
$tgl_diterima  = $_POST['tgl_diterima'];
$tgl_surat     = $_POST['tgl_surat'];
$asal_instansi = $_POST['asal_instansi'];
$perihal       = $_POST['perihal'];


$ekstensi_diperbolehkan = array('pdf','jpg','png','jpeg');
$nama_file = $_FILES['file_scan']['name'];
$x = explode('.', $nama_file);
$ekstensi = strtolower(end($x));
$ukuran = $_FILES['file_scan']['size'];
$file_tmp = $_FILES['file_scan']['tmp_name'];


$nama_file_baru = time() . '-' . $nama_file;

if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
    if($ukuran < 5000000){ 
        move_uploaded_file($file_tmp, 'berkas_scan/'.$nama_file_baru);
        
        // Simpan ke database sesuai struktur LRS
        $query = "INSERT INTO surat_masuk (id_pengguna, no_surat, tgl_diterima, tgl_surat, asal_instansi, perihal, file_scan) 
                  VALUES ('$id_pengguna', '$no_surat', '$tgl_diterima', '$tgl_surat', '$asal_instansi', '$perihal', '$nama_file_baru')";
        
        $simpan = mysqli_query($koneksi, $query);

        if($simpan){
            echo "<script>alert('Data berhasil ditambahkan!'); window.location='surat_masuk.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan ke database!'); window.location='tambah_surat_masuk.php';</script>";
        }

    }else{
        echo "<script>alert('Ukuran file terlalu besar!'); window.location='tambah_surat_masuk.php';</script>";
    }
}else{
    echo "<script>alert('Ekstensi file tidak diperbolehkan!'); window.location='tambah_surat_masuk.php';</script>";
}
?>