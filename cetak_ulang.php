<?php
session_start();
include 'koneksi.php';

if ($_SESSION['level_user'] == "") {
    header("location:login.php?pesan=belum_login");
    exit();
}

if (!isset($_GET['id']) || $_GET['id'] == "") {
    header("location:history_surat.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM history_surat WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data surat tidak ditemukan!";
    exit();
}

$isi_surat = $data['isi_surat'];
$nama_penandatangan = $data['nama_penandatangan'];
$jabatan_penandatangan = $data['jabatan_penandatangan'];
$tgl_surat = $data['tgl_surat'];

$bulan_indo = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
$tanggal_pisah = explode('-', $tgl_surat);
$tgl_format_indo = (count($tanggal_pisah) == 3) ? (int)$tanggal_pisah[2] . ' ' . $bulan_indo[(int)$tanggal_pisah[1]] . ' ' . $tanggal_pisah[0] : date('d F Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Ulang Surat - E-Arsip SMK YMIK</title>
    <style>
        @page { size: A4; margin: 0; }
        * { box-sizing: border-box; }
        body { font-family: "Times New Roman", Times, serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
        
        /* Padding atas 45mm untuk memberi ruang pada Kop Surat Fisik */
        .container { background-color: white; width: 210mm; min-height: 297mm; margin: 0 auto; padding: 45mm 25mm 25mm 25mm; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        
        .isi-surat { font-size: 16px; line-height: 1.6; text-align: justify; color: #000; }
        .tanda-tangan { float: right; width: 300px; text-align: left; font-size: 16px; margin-right: 20px; font-family: "Times New Roman", Times, serif; }
        
        .action-buttons { text-align: center; margin-bottom: 30px; }
        .btn-cetak { background-color: #007bff; color: white; border: none; padding: 12px 24px; font-size: 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-kembali { background-color: #6c757d; color: white; border: none; padding: 12px 24px; font-size: 15px; border-radius: 4px; cursor: pointer; margin-left: 10px; font-weight: bold; }
        
        @media print {
            body { background-color: white; padding: 0; margin: 0; }
            .container { box-shadow: none; margin: 0; padding: 45mm 25mm 25mm 25mm; width: 210mm; min-height: 297mm; }
            .action-buttons { display: none; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body>
    <div class="action-buttons">
        <button onclick="window.print()" class="btn-cetak">🖨 Cetak ke Kertas Kop Sekolah</button>
        <button onclick="window.close()" class="btn-kembali">✖ Tutup Tab Ini</button>
    </div>

    <div class="container">
        <!-- Bagian Tabel Logo dan Teks Sekolah SUDAH DIHAPUS DI SINI -->

        <div class="isi-surat">
            <?php echo $isi_surat; ?>
        </div>

        <div class="tanda-tangan">
            Jakarta, <?php echo $tgl_format_indo; ?><br>
            Yang menerangkan,<br>
            <?php echo $jabatan_penandatangan; ?>
            <div style="margin-top: 80px; font-weight: bold; text-decoration: underline;"><?php echo $nama_penandatangan; ?></div>
            NIP. -
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>