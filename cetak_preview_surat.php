
<?php
session_start();
include 'koneksi.php';

if ($_SESSION['level_user'] == "") {
    header("location:login.php?pesan=belum_login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $no_surat = htmlspecialchars($_POST['no_surat'] ?? '');
    $tgl_surat = htmlspecialchars($_POST['tgl_surat'] ?? '');
    $tujuan = htmlspecialchars($_POST['tujuan'] ?? '');
    $perihal = htmlspecialchars($_POST['perihal'] ?? '');
    $nama_penandatangan = htmlspecialchars($_POST['nama_penandatangan'] ?? '');
    $jabatan_penandatangan = htmlspecialchars($_POST['jabatan_penandatangan'] ?? '');

    $isi_surat = $_POST['isi_surat'] ?? '';

    $bulan_indo = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    );

    $tanggal_pisah = explode('-', $tgl_surat);

    if (count($tanggal_pisah) == 3) {
        $tgl_format_indo = (int)$tanggal_pisah[2] . ' ' .
            $bulan_indo[(int)$tanggal_pisah[1]] . ' ' .
            $tanggal_pisah[0];
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

        @page {
            size: A3;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        /* KERTAS SURAT */
        .container {
            background-color: white;
            width: 297mm;
            min-height: 420mm;
            margin: 0 auto;
            padding: 20mm 23mm 25mm 23mm;
            box-sizing: border-box;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* ================= KOP SURAT ================= */

        .tabel-kop {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 0;
        }

        /* LOGO */
        .kolom-logo {
            width: 30%;
            text-align: left;
            vertical-align: top;
            padding-top: 5px;
        }

        .kolom-logo img {
            width: 300px;
            height: auto;
            display: block;
            margin-left: -25px;
        }

        /* TEKS KOP */
        .kolom-teks {
            width: 75%;
            text-align: center;
            vertical-align: middle;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* YAYASAN */
        .teks-yayasan {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 26px;
            font-weight: bold;
            color: #000000;
            letter-spacing: 0;
            line-height: 1.2;
            white-space: nowrap;
        }

        /* SEKOLAH MENENGAH KEJURUAN */
        .teks-sekolah {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 26px;
            font-weight: bold;
            color: #000000;
            letter-spacing: 0;
            line-height: 1.2;
        }

        /* SMK YMIK */
        .teks-smk {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 76px;
            font-weight: 900;
            color: #00512B;
            letter-spacing: 6px;
            line-height: 1.05;
            margin: 0;
        }

        /* AKREDITASI */
        .teks-akreditasi {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 24px;
            font-weight: bold;
            color: #000000;
            line-height: 1.2;
            margin-bottom: 3px;
        }

        /* ALAMAT */
        .teks-alamat {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 19px;
            font-weight: normal;
            color: #222222;
            line-height: 1.3;
            white-space: nowrap;
        }

        /* GARIS GANDA KOP */
        .garis-pembatas {
            border-top: 6px solid #000000;
            border-bottom: 2px solid #000000;
            height: 3px;
            margin-top: 15px;
            margin-bottom: 28px;
        }

        /* ================= IDENTITAS SURAT ================= */

        .identitas-surat {
            width: 100%;
            margin-bottom: 25px;
            font-size: 16px;
        }

        .identitas-surat td {
            padding: 3px 0;
            vertical-align: top;
        }

        .identitas-surat .label-kolom {
            width: 90px;
        }

        .identitas-surat .titik-dua {
            width: 15px;
        }

        .tgl-surat-kanan {
            text-align: right;
        }

        /* ================= TUJUAN SURAT ================= */

        .tujuan-surat {
            margin-bottom: 30px;
            font-size: 16px;
            line-height: 1.5;
        }

        /* ================= ISI SURAT ================= */

        .isi-surat {
            font-size: 16px;
            line-height: 1.6;
            text-align: justify;
            margin-bottom: 50px;
        }

        .isi-surat p {
            margin-top: 0;
            margin-bottom: 12px;
        }

        /* ================= TANDA TANGAN ================= */

        .tanda-tangan {
            float: right;
            width: 300px;
            text-align: center;
            font-size: 16px;
            margin-right: 20px;
        }

        .tanda-tangan .nama-pejabat {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 80px;
        }

        /* ================= TOMBOL PREVIEW ================= */

        .action-buttons {
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-cetak {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 15px;
            border-radius: 4px;
            cursor: pointer;
            display: inline-block;
            font-weight: bold;
        }

        .btn-kembali {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 15px;
            border-radius: 4px;
            cursor: pointer;
            display: inline-block;
            margin-left: 10px;
            font-weight: bold;
        }

        /* ================= PENGATURAN CETAK PDF ================= */

        @media print {

            body {
                background-color: white;
                padding: 0;
                margin: 0;
            }

            .container {
                box-shadow: none;
                margin: 0;
                padding: 20mm 23mm 25mm 23mm;
                width: 297mm;
                min-height: 420mm;
            }

            .action-buttons {
                display: none;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

    </style>
</head>

<body>

    <!-- TOMBOL CETAK DAN KEMBALI -->
    <div class="action-buttons">

        <button onclick="window.print()" class="btn-cetak">
            🖨 Cetak / Simpan PDF
        </button>

        <button onclick="window.location.href='buat_surat.php'" class="btn-kembali">
            « Kembali / Edit Surat
        </button>

    </div>

    <!-- HALAMAN SURAT -->
    <div class="container">

        <!-- KOP SURAT SMK YMIK -->
        <table class="tabel-kop">

            <tr>

                <!-- LOGO -->
                <td class="kolom-logo">

                    <img src="logo_ymik.png" alt="Logo SMK YMIK">

                </td>

                <!-- TULISAN KOP -->
                <td class="kolom-teks">

                    <div class="teks-yayasan">
                        YAYASAN MEMAJUKAN ILMU DAN KEBUDAYAAN (YMIK)
                    </div>

                    <div class="teks-sekolah">
                        SEKOLAH MENENGAH KEJURUAN
                    </div>

                    <div class="teks-smk">
                        SMK YMIK
                    </div>

                    <div class="teks-akreditasi">
                        AKREDITASI : A
                    </div>

                    <div class="teks-alamat">
                        Jl. Raya Joglo (Komp. DPR. RI Pribadi) Kembangan Jakarta Barat 11640
                    </div>

                    <div class="teks-alamat">
                        Telp. 021-5840517 Fax. 5859142 e-mail : ka_smkymik@yahoo.com
                    </div>

                </td>

            </tr>

        </table>

        <!-- GARIS GANDA -->
        <div class="garis-pembatas"></div>

        <!-- IDENTITAS SURAT -->
        <table class="identitas-surat">

            <tr>

                <td class="label-kolom">Nomor</td>

                <td class="titik-dua">:</td>

                <td>
                    <?php echo $no_surat; ?>
                </td>

                <td class="tgl-surat-kanan">
                    Jakarta, <?php echo $tgl_format_indo; ?>
                </td>

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

                <td>
                    <b><?php echo $perihal; ?></b>
                </td>

                <td></td>

            </tr>

        </table>

        <!-- TUJUAN SURAT -->
        <div class="tujuan-surat">

            Kepada Yth.<br>

            <b><?php echo $tujuan; ?></b><br>

            di Tempat

        </div>

        <!-- PEMBUKA -->
        <div class="tujuan-surat">

            Dengan hormat,

        </div>

        <!-- ISI SURAT -->
        <div class="isi-surat">

            <?php echo $isi_surat; ?>

        </div>

        <!-- TANDA TANGAN -->
        <div class="tanda-tangan">

            <?php echo $jabatan_penandatangan; ?>,

            <div class="nama-pejabat">

                <?php echo $nama_penandatangan; ?>

            </div>

        </div>

        <div style="clear: both;"></div>

    </div>

</body>
</html>
