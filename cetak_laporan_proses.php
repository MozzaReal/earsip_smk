<?php
session_start();
include 'koneksi.php';

if($_SESSION['level_user'] == ""){
    header("location:login.php?pesan=belum_login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_surat = $_POST['jenis_surat'];
    $tgl_awal    = $_POST['tgl_awal'];
    $tgl_akhir   = $_POST['tgl_akhir'];

    if ($jenis_surat == 'masuk') {
        $query = mysqli_query($koneksi, "SELECT * FROM surat_masuk WHERE tgl_diterima BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY tgl_diterima ASC");
        $judul = "LAPORAN REKAPITULASI SURAT MASUK";
    } else {
        $query = mysqli_query($koneksi, "SELECT * FROM surat_keluar WHERE tgl_surat BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY tgl_surat ASC");
        $judul = "LAPORAN REKAPITULASI SURAT KELUAR";
    }
} else {
    header("location:cetak_surat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $judul; ?> - E-Arsip SMK YMIK</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            background: white;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }
        .kop-surat h4 {
            font-size: 14px;
            margin: 2px 0;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .kop-surat h2 {
            font-size: 18px;
            margin: 4px 0;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .kop-surat p {
            font-size: 11px;
            margin: 2px 0;
        }
        .judul-laporan {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 13px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .tanda-tangan {
            float: right;
            text-align: center;
            margin-top: 50px;
            width: 250px;
            font-size: 14px;
        }
        .btn-container {
            text-align: center;
            margin-bottom: 25px;
        }
        .btn-print {
            background-color: #0288d1;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-print:hover { background-color: #0277bd; }

        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Tombol Aksi Cetak -->
        <div class="no-print btn-container">
            <button onclick="window.print()" class="btn-print">🖨️ Cetak / Simpan PDF</button>
            <button onclick="window.close()" class="btn-print" style="background-color: #757575; margin-left: 10px;">&laquo; Tutup</button>
        </div>

        <!-- Kop Surat Resmi -->
        <div class="kop-surat">
            <h4>YAYASAN Memajukan Ilmu dan Kebudayaan</h4>
            <h2>SMP & SMK YMIK JAKARTA</h2>
            <p>Alamat: Jl. Raya Joglo (Komp. DPR-RI Pribadi), RT.2/RW.1, Kel. Joglo, Kec. Kembangan, Jakarta Barat 11640</p>
            <p>Telp: (021) 5859142 / (021) 5840517 | Email: smkymikcenter@gmail.com / smpymikjkt4@gmail.com</p>
        </div>

        <div class="judul-laporan">
            <?php echo $judul; ?><br>
            <span style="font-size: 13px; font-weight: normal;">Periode: <?php echo date('d-m-Y', strtotime($tgl_awal)); ?> s/d <?php echo date('d-m-Y', strtotime($tgl_akhir)); ?></span>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th width="40">No</th>
                    <th>Nomor Surat</th>
                    <th><?php echo ($jenis_surat == 'masuk') ? 'Tanggal Diterima' : 'Tanggal Surat'; ?></th>
                    <th><?php echo ($jenis_surat == 'masuk') ? 'Asal Instansi' : 'Tujuan Instansi'; ?></th>
                    <th>Perihal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td align="center"><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['no_surat']); ?></td>
                    <td align="center"><?php echo ($jenis_surat == 'masuk') ? $row['tgl_diterima'] : $row['tgl_surat']; ?></td>
                    <td><?php echo htmlspecialchars(($jenis_surat == 'masuk') ? $row['asal_instansi'] : $row['tujuan']); ?></td>
                    <td><?php echo htmlspecialchars($row['perihal']); ?></td>
                </tr>
                <?php 
                    }
                } else {
                ?>
                <tr>
                    <td colspan="5" align="center" style="padding: 15px;">Tidak ada data surat pada rentang tanggal tersebut.</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="tanda-tangan">
            <p>Jakarta, <?php echo date('d F Y'); ?><br>Kepala Tata Usaha</p>
            <br><br><br>
            <p><b><u>( ......................................... )</u></b></p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>