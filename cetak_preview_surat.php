<?php
session_start();
include 'koneksi.php';

if ($_SESSION['level_user'] == "") {
    header("location:login.php?pesan=belum_login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_surat = mysqli_real_escape_string($koneksi, $_POST['jenis_surat'] ?? 'Surat Keluar');
    $no_surat = htmlspecialchars($_POST['no_surat'] ?? '');
    $tgl_surat = htmlspecialchars($_POST['tgl_surat'] ?? date('Y-m-d'));
    
    if ($jenis_surat === "Surat Keterangan Aktif Peserta Didik") {
        $nama = htmlspecialchars($_POST['val_nama'] ?? '');
        $nis = htmlspecialchars($_POST['val_nis'] ?? '');
        $ttl = htmlspecialchars($_POST['val_ttl'] ?? '');
        $jk = htmlspecialchars($_POST['val_jk'] ?? '');
        $kelas = htmlspecialchars($_POST['val_kelas'] ?? '');
        $jurusan = htmlspecialchars($_POST['val_jurusan'] ?? '');
        $ortu = htmlspecialchars($_POST['val_ortu'] ?? '');
        $alamat = htmlspecialchars($_POST['val_alamat'] ?? '');
        $keperluan = htmlspecialchars($_POST['val_keperluan'] ?? '');

        $tujuan = "-";
        $perihal = "Surat Keterangan Aktif Peserta Didik";
        $nama_penandatangan = "Dra. Hj. Fajar Inawan.";
        $jabatan_penandatangan = "Kepala Sekolah,";

        $isi_surat = '
        <div style="text-align:center; font-family:\'Times New Roman\', Times, serif;">
            <p style="font-size:18px; font-weight:bold; text-decoration:underline; margin:0;">SURAT KETERANGAN AKTIF PESERTA DIDIK</p>
            <p style="font-size:16px; margin-top:5px; margin-bottom:30px;">Nomor : '.$no_surat.'</p>
        </div>
        <div style="font-size:16px; font-family:\'Times New Roman\', Times, serif; line-height:1.8;">
            <p style="text-align:justify; margin-bottom:15px; text-indent:40px;">Yang bertanda tangan di bawah ini, Kepala SMK YMIK Jakarta, menerangkan bahwa:</p>
            
            <table style="width:100%; border:none; margin-left:30px; margin-bottom:20px; font-size:16px; line-height:1.8;">
                <tr><td style="width:180px; vertical-align:top;">Nama</td><td style="width:15px; vertical-align:top;">:</td><td><b>'.$nama.'</b></td></tr>
                <tr><td style="vertical-align:top;">NIS / NISN</td><td style="vertical-align:top;">:</td><td>'.$nis.'</td></tr>
                <tr><td style="vertical-align:top;">Tempat / Tgl. Lahir</td><td style="vertical-align:top;">:</td><td>'.$ttl.'</td></tr>
                <tr><td style="vertical-align:top;">Jenis Kelamin</td><td style="vertical-align:top;">:</td><td>'.$jk.'</td></tr>
                <tr><td style="vertical-align:top;">Kelas</td><td style="vertical-align:top;">:</td><td>'.$kelas.'</td></tr>
                <tr><td style="vertical-align:top;">Jurusan</td><td style="vertical-align:top;">:</td><td>'.$jurusan.'</td></tr>
                <tr><td style="vertical-align:top;">Nama Orang Tua</td><td style="vertical-align:top;">:</td><td>'.$ortu.'</td></tr>
                <tr><td style="vertical-align:top;">Alamat</td><td style="vertical-align:top;">:</td><td>'.$alamat.'</td></tr>
            </table>
            
            <p style="text-align:justify; margin-bottom:15px; text-indent:40px;">Adalah benar yang namanya tersebut di atas, tercatat sebagai siswa aktif di SMK YMIK.</p>
            <p style="text-align:justify; margin-bottom:15px; text-indent:40px;">Surat keterangan aktif ini dikeluarkan untuk '.$keperluan.'.</p>
            <p style="text-align:justify; margin-bottom:50px; text-indent:40px;">Demikian Surat Keterangan ini di buat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
        </div>';

    } else if ($jenis_surat === "Surat Permohonan PKL") {
        $nama_perusahaan = htmlspecialchars($_POST['pkl_nama_perusahaan'] ?? '');
        $alamat_perusahaan = htmlspecialchars($_POST['pkl_alamat_perusahaan'] ?? '');
        $nama_siswa = htmlspecialchars($_POST['pkl_nama_siswa'] ?? '');
        $nis_siswa = htmlspecialchars($_POST['pkl_nis'] ?? '');
        $jurusan_siswa = htmlspecialchars($_POST['pkl_jurusan'] ?? '');

        $tujuan = $nama_perusahaan;
        $perihal = "Permohonan Praktik Kerja Lapangan (PKL)";
        $nama_penandatangan = "Dra. Hj. Fajar Inawan.";
        $jabatan_penandatangan = "Kepala Sekolah,";

        $isi_surat = '
        <table style="width:100%; margin-bottom:20px; font-size:16px;">
            <tr><td style="width:90px;">Nomor</td><td style="width:15px;">:</td><td>'.$no_surat.'</td></tr>
            <tr><td>Lampiran</td><td>:</td><td>-</td></tr>
            <tr><td>Perihal</td><td>:</td><td><b>Permohonan Praktik Kerja Lapangan (PKL)</b></td></tr>
        </table>
        <div style="margin-bottom:20px; font-size:16px; line-height:1.5;">
            Kepada Yth.<br><b>Bapak/Ibu Pimpinan '.$nama_perusahaan.'</b><br>'.$alamat_perusahaan.'
        </div>
        <div style="font-size:16px; line-height:1.6; text-align:justify;">
            <p>Dengan hormat,</p>
            <p style="text-indent:40px;">Berkaitan dengan program pemerintah di bidang pendidikan dalam mewujudkan keterkaitan dan kesepadanan (Link and Match) antara pendidikan dengan dunia usaha/industri, maka siswa/i kami diwajibkan untuk mengikuti kegiatan Praktik Kerja Lapangan (PKL).</p>
            <p style="text-indent:40px;">Oleh karena itu, kami mengajukan permohonan agar kiranya siswa/i kami dapat melaksanakan Praktik Kerja Lapangan (PKL) di perusahaan/instansi yang Bapak/Ibu pimpin. Adapun data siswa/i kami tersebut adalah:</p>
            
            <table style="width:100%; border:none; margin-left:30px; margin-bottom:15px; font-size:16px; line-height:1.8;">
                <tr><td style="width:180px; vertical-align:top;">Nama</td><td style="width:15px; vertical-align:top;">:</td><td><b>'.$nama_siswa.'</b></td></tr>
                <tr><td style="vertical-align:top;">NIS / NISN</td><td style="vertical-align:top;">:</td><td>'.$nis_siswa.'</td></tr>
                <tr><td style="vertical-align:top;">Program Keahlian</td><td style="vertical-align:top;">:</td><td>'.$jurusan_siswa.'</td></tr>
            </table>
            
            <p style="text-indent:40px; margin-bottom:40px;">Demikian surat permohonan ini kami sampaikan. Atas perhatian, bantuan, dan kerjasama yang baik dari Bapak/Ibu, kami ucapkan terima kasih.</p>
        </div>';
        
    } else {
        $tujuan = htmlspecialchars($_POST['tujuan'] ?? '');
        $perihal = htmlspecialchars($_POST['perihal'] ?? '');
        $nama_penandatangan = htmlspecialchars($_POST['nama_penandatangan'] ?? '');
        $jabatan_penandatangan = htmlspecialchars($_POST['jabatan_penandatangan'] ?? '') . ",";
        $raw_isi = $_POST['isi_surat'] ?? '';
        
        $isi_surat = '
        <table style="width:100%; margin-bottom:25px; font-size:16px;">
            <tr><td style="width:90px;">Nomor</td><td style="width:15px;">:</td><td>'.$no_surat.'</td></tr>
            <tr><td>Lampiran</td><td>:</td><td>-</td></tr>
            <tr><td>Perihal</td><td>:</td><td><b>'.$perihal.'</b></td></tr>
        </table>
        <div style="margin-bottom:30px; font-size:16px; line-height:1.5;">
            Kepada Yth.<br><b>'.$tujuan.'</b><br>di Tempat
        </div>
        <div style="margin-bottom:20px; font-size:16px;">Dengan hormat,</div>
        <div style="font-size:16px; line-height:1.6; text-align:justify; margin-bottom:50px;">
            '.$raw_isi.'
        </div>';
    }

    $bulan_indo = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    $tanggal_pisah = explode('-', $tgl_surat);
    $tgl_format_indo = (count($tanggal_pisah) == 3) ? (int)$tanggal_pisah[2] . ' ' . $bulan_indo[(int)$tanggal_pisah[1]] . ' ' . $tanggal_pisah[0] : date('d F Y');

    $isi_surat_aman = mysqli_real_escape_string($koneksi, $isi_surat);

    $cek_history = mysqli_query($koneksi, "SELECT * FROM history_surat WHERE no_surat = '$no_surat' AND jenis_surat = '$jenis_surat'");
    if(mysqli_num_rows($cek_history) == 0 && $no_surat != "") {
        mysqli_query($koneksi, "INSERT INTO history_surat (jenis_surat, no_surat, tgl_surat, tujuan, perihal, isi_surat, nama_penandatangan, jabatan_penandatangan) 
                                VALUES ('$jenis_surat', '$no_surat', '$tgl_surat', '$tujuan', '$perihal', '$isi_surat_aman', '$nama_penandatangan', '$jabatan_penandatangan')");
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
        @page { size: A4; margin: 0; }
        * { box-sizing: border-box; }
        body { font-family: "Times New Roman", Times, serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
        
        /* Padding atas diset 45mm untuk memberi ruang pada Kop Surat Fisik */
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
        <button onclick="window.location.href='buat_surat.php'" class="btn-kembali">« Kembali / Edit Surat</button>
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