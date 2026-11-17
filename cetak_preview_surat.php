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

        $isi_surat = '... (isi surat aktif sama seperti sebelumnya) ...';
        // (Isi tag HTML aktif tetap sama)
        $isi_surat = '<div style="text-align:center; font-family:\'Times New Roman\', Times, serif;"><p style="font-size:18px; font-weight:bold; text-decoration:underline; margin:0;">SURAT KETERANGAN AKTIF PESERTA DIDIK</p><p style="font-size:16px; margin-top:5px; margin-bottom:20px;">Nomor : '.$no_surat.'</p></div><div style="font-size:16px; font-family:\'Times New Roman\', Times, serif; line-height:1.5;"><p style="text-align:justify; margin-bottom:10px; text-indent:40px;">Yang bertanda tangan di bawah ini, Kepala SMK YMIK Jakarta, menerangkan bahwa:</p><table style="width:100%; border:none; margin-left:30px; margin-bottom:15px; font-size:16px; line-height:1.6;"><tr><td style="width:180px; vertical-align:top;">Nama</td><td style="width:15px; vertical-align:top;">:</td><td><b>'.$nama.'</b></td></tr><tr><td style="vertical-align:top;">NIS / NISN</td><td style="vertical-align:top;">:</td><td>'.$nis.'</td></tr><tr><td style="vertical-align:top;">Tempat / Tgl. Lahir</td><td style="vertical-align:top;">:</td><td>'.$ttl.'</td></tr><tr><td style="vertical-align:top;">Jenis Kelamin</td><td style="vertical-align:top;">:</td><td>'.$jk.'</td></tr><tr><td style="vertical-align:top;">Kelas</td><td style="vertical-align:top;">:</td><td>'.$kelas.'</td></tr><tr><td style="vertical-align:top;">Jurusan</td><td style="vertical-align:top;">:</td><td>'.$jurusan.'</td></tr><tr><td style="vertical-align:top;">Nama Orang Tua</td><td style="vertical-align:top;">:</td><td>'.$ortu.'</td></tr><tr><td style="vertical-align:top;">Alamat</td><td style="vertical-align:top;">:</td><td>'.$alamat.'</td></tr></table><p style="text-align:justify; margin-bottom:10px; text-indent:40px;">Adalah benar yang namanya tersebut di atas, tercatat sebagai siswa aktif di SMK YMIK.</p><p style="text-align:justify; margin-bottom:10px; text-indent:40px;">Surat keterangan aktif ini dikeluarkan untuk '.$keperluan.'.</p><p style="text-align:justify; margin-bottom:20px; text-indent:40px;">Demikian Surat Keterangan ini di buat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p></div>';

    } else if ($jenis_surat === "Surat Permohonan PKL") {
        $nama_perusahaan = htmlspecialchars($_POST['pkl_nama_perusahaan'] ?? '');
        $alamat_perusahaan = htmlspecialchars($_POST['pkl_alamat_perusahaan'] ?? '');
        $periode_pkl = htmlspecialchars($_POST['pkl_periode'] ?? '');

        $array_nama = $_POST['pkl_siswa_nama'] ?? [];
        $array_nis = $_POST['pkl_siswa_nis'] ?? [];
        $array_jurusan = $_POST['pkl_siswa_jurusan'] ?? [];

        $tujuan = $nama_perusahaan;
        $perihal = "Permohonan Praktik Kerja Lapangan (PKL)";
        $nama_penandatangan = "Dra. Hj. Fajar Inawan.";
        $jabatan_penandatangan = "Kepala Sekolah,";

        $tabel_siswa_html = '';
        for ($i = 0; $i < count($array_nama); $i++) {
            $no_urut = $i + 1;
            $tabel_siswa_html .= '<tr><td style="border:1px solid #000; text-align:center; padding:4px;">'.$no_urut.'</td><td style="border:1px solid #000; padding:4px;"><b>'.htmlspecialchars($array_nama[$i]).'</b></td><td style="border:1px solid #000; text-align:center; padding:4px;">'.htmlspecialchars($array_nis[$i]).'</td><td style="border:1px solid #000; padding:4px;">'.htmlspecialchars($array_jurusan[$i]).'</td></tr>';
        }

        $isi_surat = '<table style="width:100%; margin-bottom:10px; font-size:15px;"><tr><td style="width:90px;">Nomor</td><td style="width:15px;">:</td><td>'.$no_surat.'</td></tr><tr><td>Lampiran</td><td>:</td><td>-</td></tr><tr><td>Perihal</td><td>:</td><td><b>Permohonan Praktik Kerja Lapangan (PKL)</b></td></tr></table><div style="margin-bottom:10px; font-size:15px; line-height:1.4;">Kepada Yth.<br><b>Bapak/Ibu Pimpinan '.$nama_perusahaan.'</b><br>'.$alamat_perusahaan.'</div><div style="font-size:15px; line-height:1.4; text-align:justify;"><p style="margin-top:0; margin-bottom:5px;">Dengan hormat,</p><p style="text-indent:40px; margin-top:5px; margin-bottom:5px;">Berkaitan dengan program pemerintah di bidang pendidikan dalam mewujudkan keterkaitan dan kesepadanan (Link and Match) antara pendidikan dengan dunia usaha/industri, maka siswa/i kami diwajibkan untuk mengikuti kegiatan Praktik Kerja Lapangan (PKL).</p><p style="text-indent:40px; margin-top:5px; margin-bottom:5px;">Pelaksanaan kegiatan Praktik Kerja Lapangan (PKL) ini direncanakan akan berlangsung pada periode <b>'.$periode_pkl.'</b>.</p><p style="text-indent:40px; margin-top:5px; margin-bottom:5px;">Oleh karena itu, kami mengajukan permohonan agar kiranya siswa/i kelompok kami dapat diberikan kesempatan untuk melaksanakan Praktik Kerja Lapangan (PKL) di perusahaan/instansi yang Bapak/Ibu pimpin. Adapun daftar nama siswa/i tersebut adalah:</p><table style="width:100%; border-collapse:collapse; margin-top:10px; margin-bottom:10px; font-size:14px;"><thead><tr style="background-color:#f2f2f2;"><th style="border:1px solid #000; padding:4px; width:30px; text-align:center;">No</th><th style="border:1px solid #000; padding:4px;">Nama Siswa/i</th><th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">NIS / NISN</th><th style="border:1px solid #000; padding:4px;">Program Keahlian (Jurusan)</th></tr></thead><tbody>'.$tabel_siswa_html.'</tbody></table><p style="text-indent:40px; margin-top:5px; margin-bottom:10px;">Demikian surat permohonan ini kami sampaikan. Atas perhatian, bantuan, dan kerjasama yang baik dari Bapak/Ibu pimpinan, kami ucapkan terima kasih.</p></div>';
        
    } else if ($jenis_surat === "Surat Balasan PKL") {
        $balasan_perusahaan = htmlspecialchars($_POST['balasan_perusahaan'] ?? '');
        $balasan_alamat = htmlspecialchars($_POST['balasan_alamat'] ?? '');
        $balasan_mulai = htmlspecialchars($_POST['balasan_mulai'] ?? '');

        $array_b_nama = $_POST['balasan_siswa_nama'] ?? [];
        $array_b_nis = $_POST['balasan_siswa_nis'] ?? [];
        $array_b_jurusan = $_POST['balasan_siswa_jurusan'] ?? [];

        $tujuan = "SMK YMIK Jakarta";
        $perihal = "Surat Balasan / Penerimaan Permohonan PKL";
        
        // Memaksa isian menjadi titik-titik untuk surat balasan
        $jabatan_penandatangan = "....................................................";
        $nama_penandatangan = "....................................................";

        $tabel_b_siswa_html = '';
        for ($i = 0; $i < count($array_b_nama); $i++) {
            $no_urut = $i + 1;
            $tabel_b_siswa_html .= '<tr><td style="border:1px solid #000; text-align:center; padding:4px;">'.$no_urut.'</td><td style="border:1px solid #000; padding:4px;"><b>'.htmlspecialchars($array_b_nama[$i]).'</b></td><td style="border:1px solid #000; text-align:center; padding:4px;">'.htmlspecialchars($array_b_nis[$i]).'</td><td style="border:1px solid #000; padding:4px;">'.htmlspecialchars($array_b_jurusan[$i]).'</td></tr>';
        }

        $isi_surat = '<table style="width:100%; margin-bottom:15px; font-size:15px;"><tr><td style="width:90px;">Nomor</td><td style="width:15px;">:</td><td>'.$no_surat.'</td></tr><tr><td>Lampiran</td><td>:</td><td>-</td></tr><tr><td>Perihal</td><td>:</td><td><b>Balasan Permohonan Praktik Kerja Lapangan (PKL)</b></td></tr></table><div style="margin-bottom:15px; font-size:15px; line-height:1.4;">Kepada Yth.<br><b>Kepala SMK YMIK Jakarta</b><br>di Jakarta</div><div style="font-size:15px; line-height:1.4; text-align:justify;"><p style="margin-top:0; margin-bottom:5px;">Dengan hormat,</p><p style="text-indent:40px; margin-top:5px; margin-bottom:5px;">Menindaklanjuti surat permohonan Praktik Kerja Lapangan (PKL) dari SMK YMIK Jakarta, dengan ini kami dari pihak <b>'.$balasan_perusahaan.'</b> yang beralamat di '.$balasan_alamat.', memberitahukan bahwa permohonan tersebut dapat kami terima.</p><p style="text-indent:40px; margin-top:5px; margin-bottom:5px;">Adapun daftar siswa/i dari sekolah yang Bapak/Ibu pimpin yang diterima untuk melaksanakan PKL di instansi kami pada periode <b>'.$balasan_mulai.'</b> adalah sebagai berikut:</p><table style="width:100%; border-collapse:collapse; margin-top:10px; margin-bottom:10px; font-size:14px;"><thead><tr style="background-color:#f2f2f2;"><th style="border:1px solid #000; padding:4px; width:30px; text-align:center;">No</th><th style="border:1px solid #000; padding:4px;">Nama Siswa/i</th><th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">NIS / NISN</th><th style="border:1px solid #000; padding:4px;">Program Keahlian (Jurusan)</th></tr></thead><tbody>'.$tabel_b_siswa_html.'</tbody></table><p style="text-indent:40px; margin-top:5px; margin-bottom:10px;">Demikian surat pemberitahuan ini kami sampaikan agar dapat dipergunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p></div>';
    } else {
        $tujuan = htmlspecialchars($_POST['tujuan'] ?? '');
        $perihal = htmlspecialchars($_POST['perihal'] ?? '');
        $nama_penandatangan = htmlspecialchars($_POST['nama_penandatangan'] ?? '');
        $jabatan_penandatangan = htmlspecialchars($_POST['jabatan_penandatangan'] ?? '') . ",";
        $raw_isi = $_POST['isi_surat'] ?? '';
        
        $isi_surat = '<table style="width:100%; margin-bottom:15px; font-size:16px;"><tr><td style="width:90px;">Nomor</td><td style="width:15px;">:</td><td>'.$no_surat.'</td></tr><tr><td>Lampiran</td><td>:</td><td>-</td></tr><tr><td>Perihal</td><td>:</td><td><b>'.$perihal.'</b></td></tr></table><div style="margin-bottom:20px; font-size:16px; line-height:1.5;">Kepada Yth.<br><b>'.$tujuan.'</b><br>di Tempat</div><div style="margin-bottom:15px; font-size:16px;">Dengan hormat,</div><div style="font-size:16px; line-height:1.5; text-align:justify; margin-bottom:20px;">'.$raw_isi.'</div>';
    }

    $bulan_indo = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    $tanggal_pisah = explode('-', $tgl_surat);
    $tgl_format_indo = (count($tanggal_pisah) == 3) ? (int)$tanggal_pisah[2] . ' ' . $bulan_indo[(int)$tanggal_pisah[1]] . ' ' . $tanggal_pisah[0] : date('d F Y');

    $isi_surat_aman = mysqli_real_escape_string($koneksi, $isi_surat);

    if($no_surat != "") {
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
        .container { background-color: white; width: 210mm; min-height: 297mm; margin: 0 auto; padding: 59mm 20mm 10mm 20mm; box-shadow: 0 0 10px rgba(0,0,0,0.1); position: relative; }
        .isi-surat { font-size: 15px; line-height: 1.4; text-align: justify; color: #000; margin-bottom: 5px; }
        .tanda-tangan { float: right; width: 300px; text-align: left; font-size: 15px; margin-right: 15px; font-family: "Times New Roman", Times, serif; page-break-inside: avoid; }
        .action-buttons { text-align: center; margin-bottom: 30px; }
        .btn-cetak { background-color: #007bff; color: white; border: none; padding: 12px 24px; font-size: 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-kembali { background-color: #6c757d; color: white; border: none; padding: 12px 24px; font-size: 15px; border-radius: 4px; cursor: pointer; margin-left: 10px; font-weight: bold; }
        @media print {
            body { background-color: white; padding: 0; margin: 0; }
            .container { box-shadow: none; margin: 0; padding: 59mm 20mm 10mm 20mm; width: 210mm; min-height: 297mm; }
            .action-buttons { display: none; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body>
    <div class="action-buttons">
        <button onclick="window.print()" class="btn-cetak">🖨 Cetak ke Kertas Kop Sekolah (5.9 cm)</button>
        <button onclick="window.location.href='buat_surat.php'" class="btn-kembali">« Kembali / Edit Surat</button>
    </div>

    <div class="container">
        <div class="isi-surat">
            <?php echo $isi_surat; ?>
        </div>

        <div class="tanda-tangan">
            Jakarta, <?php echo $tgl_format_indo; ?><br>
            <?php if ($jenis_surat === "Surat Balasan PKL") { ?>
                Yang menerangkan,<br>
                <?php echo $jabatan_penandatangan; ?>
                <div style="margin-top: 40px;"><?php echo $nama_penandatangan; ?></div>
            <?php } else { ?>
                Yang menerangkan,<br>
                <?php echo $jabatan_penandatangan; ?>
                <div style="margin-top: 30px; font-weight: bold; text-decoration: underline;"><?php echo $nama_penandatangan; ?></div>
                NIP. -
            <?php } ?>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>