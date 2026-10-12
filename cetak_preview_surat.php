<?php
if(isset($_POST['preview'])) {
    $nama_yayasan   = $_POST['nama_yayasan'];
    $nama_sekolah   = $_POST['nama_sekolah'];
    $alamat_sekolah = $_POST['alamat_sekolah'];
    $no_surat       = $_POST['no_surat'];
    $tgl_surat      = $_POST['tgl_surat'];
    $tujuan         = $_POST['tujuan'];
    $perihal        = $_POST['perihal'];
    $isi_surat      = $_POST['isi_surat'];
    $penandatangan  = $_POST['penandatangan'];
    $jabatan        = $_POST['jabatan'];
} else {
    header("location: buat_surat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat - <?php echo htmlspecialchars($no_surat); ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 40px; color: #000; background: #fff; }
        .kop-surat { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h2, .kop-surat h3, .kop-surat p { margin: 2px 0; }
        .isi-surat { line-height: 1.6; text-align: justify; margin-top: 20px; }
        .ttd { float: right; text-align: center; margin-top: 50px; width: 250px; }
        .btn-print { position: fixed; top: 20px; right: 20px; background: #2e7d32; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        @media print { .btn-print { display: none; } }
    </style>
</head>
<body>

    <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>

    <div class="kop-surat">
        <h3><?php echo htmlspecialchars($nama_yayasan); ?></h3>
        <h2><?php echo htmlspecialchars($nama_sekolah); ?></h2>
        <p><?php echo htmlspecialchars($alamat_sekolah); ?></p>
    </div>

    <table width="100%" style="margin-bottom: 20px;">
        <tr>
            <td width="15%">Nomor</td>
            <td width="2%">:</td>
            <td><?php echo htmlspecialchars($no_surat); ?></td>
            <td width="30%" align="right">Jakarta, <?php echo date('d F Y', strtotime($tgl_surat)); ?></td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td colspan="2"><b><?php echo htmlspecialchars($perihal); ?></b></td>
        </tr>
    </table>

    <div style="margin-bottom: 20px;">
        Kepada Yth.<br>
        <b><?php echo htmlspecialchars($tujuan); ?></b><br>
        di Tempat
    </div>

    <div class="isi-surat">
        <p>Dengan hormat,</p>
        <p><?php echo nl2br(htmlspecialchars($isi_surat)); ?></p>
        <p>Demikian surat ini disampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
    </div>

    <div class="ttd">
        <p>Hormat kami,<br><?php echo htmlspecialchars($jabatan); ?></p>
        <br><br><br>
        <p><b><u><?php echo htmlspecialchars($penandatangan); ?></u></b></p>
    </div>

</body>
</html>