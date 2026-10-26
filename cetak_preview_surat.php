<?php
session_start();
include 'koneksi.php';

if($_SESSION['level_user'] == ""){
    header("location:login.php?pesan=belum_login");
    exit();
}

// Menangkap data dari POST form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_surat = htmlspecialchars($_POST['no_surat'] ?? '');
    $tgl_surat = htmlspecialchars($_POST['tgl_surat'] ?? '');
    $tujuan = htmlspecialchars($_POST['tujuan'] ?? '');
    $perihal = htmlspecialchars($_POST['perihal'] ?? '');
    $nama_penandatangan = htmlspecialchars($_POST['nama_penandatangan'] ?? '');
    $jabatan_penandatangan = htmlspecialchars($_POST['jabatan_penandatangan'] ?? '');
    
    // KHUSUS ISI SURAT: Dilarang pakai htmlspecialchars agar format CKEditor terbaca
    $isi_surat = $_POST['isi_surat'] ?? ''; 
    
    // Format tanggal Indonesia
    $bulan_indo = array (
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $tanggal_pisah = explode('-', $tgl_surat);
    if(count($tanggal_pisah) == 3){
        $tgl_format_indo = $tanggal_pisah[2] . ' ' . $bulan_indo[(int)$tanggal_pisah[1]] . ' ' . $tanggal_pisah[0];
    } else {
        $tgl_format_indo = date('d F Y'); 
    }
} else {
    header("location:buat_surat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pratinjau Surat Keluar - E-Arsip SMK YMIK</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; background-color: #f4f4f4; padding: 20px; }
        .container { background-color: white; width: 210mm; min-height: 297mm; margin: 0 auto; padding: 20mm; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        
        .kop-surat { text-align: center; border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h4 { margin: 0; font-size: 14px; font-weight: normal; }
        .kop-surat h2 { margin: 5px 0; font-size: 20px; font-weight: bold; }
        .kop-surat p { margin: 0; font-size: 12px; }
        
        .identitas-surat { width: 100%; margin-bottom: 20px; font-size: 14px; }
        .identitas-surat td { padding: 2px 0; vertical-align: top; }
        .identitas-surat .label-kolom { width: 80px; }
        .identitas-surat .titik-dua { width: 15px; }
        .tgl-surat-kanan { text-align: right; }
        
        .tujuan-surat { margin-bottom: 25px; font-size: 14px; line-height: 1.5; }
        
        .isi-surat { font-size: 14px; line-height: 1.5; text-align: justify; margin-bottom: 40px; }
        .isi-surat p { margin-top: 0; margin-bottom: 10px; }
        
        /* CSS TANDA TANGAN YANG SUDAH DIRAPIHKAN */
        .tanda-tangan { 
            float: right; 
            width: 250px; 
            text-align: center; 
            font-size: 14px; 
            margin-right: 20px; 
        }
        .tanda-tangan .nama-pejabat { 
            font-weight: bold; 
            text-decoration: underline; 
            margin-top: 70px; 
        }
        
        .action-buttons { text-align: center; margin-bottom: 30px; }
        .btn-cetak { background-color: #007bff; color: white; border: none; padding: 10px 20px; font-size: 14px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-weight: bold; }
        
        /* CSS TOMBOL KEMBALI */
        .btn-kembali { background-color: #6c757d; color: white; border: none; padding: 10px 20px; font-size: 14px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin-left: 10px; font-weight: bold; }
        
        @media print { body { background-color: transparent; padding: 0; } .container { box-shadow: none; margin: 0; padding: 0; width: 100%; } .action-buttons { display: none; } }
    </style>
</head>
<body>

    <div class="action-buttons">
        <button onclick="window.print()" class="btn-cetak">🖨 Cetak / Simpan PDF</button>
        <!-- FUNGSI TOMBOL KEMBALI YANG MEMPERTAHANKAN DATA -->
        <button onclick="window.close()" class="btn-kembali">« Tutup Preview</button>
    </div>

    <div class="container">
        <div class="kop-surat">
            <h4>YAYASAN Memajukan Ilmu dan Kebudayaan</h4>
            <h2>SMP & SMK YMIK JAKARTA</h2>
            <p>Alamat: Jl. Raya Joglo (Komp. DPR-RI Pribadi), RT.2/RW.1, Kel. Joglo, Kec. Kembangan, Jakarta Barat 11640</p>
            <p>Telp: (021) 5859142 / (021) 5840517 | Email: smkymikcenter@gmail.com / smpymikjkt4@gmail.com</p>
        </div>

        <table class="identitas-surat">
            <tr>
                <td class="label-kolom">Nomor</td>
                <td class="titik-dua">:</td>
                <td><?php echo $no_surat; ?></td>
                <td class="tgl-surat-kanan">Jakarta, <?php echo $tgl_format_indo; ?></td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
                <td></td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><b><?php echo $perihal; ?></b></td>
                <td></td>
            </tr>
        </table>

        <div class="tujuan-surat">
            Kepada Yth.<br>
            <b><?php echo $tujuan; ?></b><br>
            di Tempat
        </div>

        <div class="tujuan-surat">
            Dengan hormat,
        </div>

        <div class="isi-surat">
            <!-- Format HTML dari CKEditor akan otomatis diterapkan di sini -->
            <?php echo $isi_surat; ?>
        </div>

        <div class="tanda-tangan">
            <?php echo $jabatan_penandatangan; ?>,
            <div class="nama-pejabat"><?php echo $nama_penandatangan; ?></div>
        </div>

        <div style="clear: both;"></div>
    </div>

</body>
</html>