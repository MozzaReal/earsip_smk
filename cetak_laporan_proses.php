<?php
session_start();
include 'koneksi.php';

if(isset($_POST['cetak'])) {
    $jenis_surat = $_POST['jenis_surat'];
    $tgl_awal    = $_POST['tgl_awal'];
    $tgl_akhir   = $_POST['tgl_akhir'];
} else {
    header("location: cetak_surat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Surat <?php echo ucfirst($jenis_surat); ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 30px; color: #000; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2, .header h3, .header p { margin: 3px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid #000; padding: 8px 10px; font-size: 13px; text-align: left; }
        table th { background-color: #f2f2f2; }
        .btn-print { position: fixed; top: 20px; right: 20px; background: #2e7d32; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        @media print { .btn-print { display: none; } }
    </style>
</head>
<body>

    <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>

    <div class="header">
        <h2>SMK YMIK JAKARTA</h2>
        <h3>LAPORAN REKAPITULASI SURAT <?php echo strtoupper($jenis_surat); ?></h3>
        <p>Periode: <?php echo date('d-m-Y', strtotime($tgl_awal)); ?> s/d <?php echo date('d-m-Y', strtotime($tgl_akhir)); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Surat</th>
                <th>Tanggal</th>
                <th><?php echo ($jenis_surat == 'masuk') ? 'Asal Instansi' : 'Tujuan Instansi'; ?></th>
                <th>Perihal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if($jenis_surat == 'masuk') {
                $query = mysqli_query($koneksi, "SELECT * FROM surat_masuk WHERE tgl_surat BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY id_masuk DESC");
                while($d = mysqli_fetch_array($query)) {
                    echo "<tr>
                        <td>".$no++."</td>
                        <td>".htmlspecialchars($d['no_surat'])."</td>
                        <td>".htmlspecialchars($d['tgl_surat'])."</td>
                        <td>".htmlspecialchars($d['asal_instansi'])."</td>
                        <td>".htmlspecialchars($d['perihal'])."</td>
                    </tr>";
                }
            } else {
                $query = mysqli_query($koneksi, "SELECT * FROM surat_keluar WHERE tgl_surat BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY id_keluar DESC");
                while($d = mysqli_fetch_array($query)) {
                    echo "<tr>
                        <td>".$no++."</td>
                        <td>".htmlspecialchars($d['no_surat'])."</td>
                        <td>".htmlspecialchars($d['tgl_surat'])."</td>
                        <td>".htmlspecialchars($d['tujuan'])."</td>
                        <td>".htmlspecialchars($d['perihal'])."</td>
                    </tr>";
                }
            }

            if(mysqli_num_rows($query) == 0) {
                echo "<tr><td colspan='5' style='text-align:center;'>Data tidak ditemukan pada periode ini.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>