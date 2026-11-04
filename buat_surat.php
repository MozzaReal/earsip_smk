<?php
session_start();
include 'koneksi.php';

if ($_SESSION['level_user'] == "") {
    header("location:login.php?pesan=belum_login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Membuat Surat Keluar - E-Arsip SMK YMIK</title>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f9fcfa;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background-color: #ffffff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 100;
        }

        .sidebar-header {
            background-color: #2e7d32;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-list li a {
            display: block;
            padding: 15px 20px;
            color: #333;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: 0.2s;
        }

        .menu-list li a:hover,
        .menu-list li a.active {
            background-color: #e8f5e9;
            border-left: 4px solid #2e7d32;
            color: #2e7d32;
            font-weight: bold;
        }

        .menu-list li a.logout-btn {
            color: #d32f2f;
        }

        /* KONTEN UTAMA */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .content-area {
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        /* FORM */
        .card-form {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 8px;
            border-top: 5px solid #2e7d32;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 800px;
        }

        .card-form h3 {
            color: #2e7d32;
            margin-top: 0;
            margin-bottom: 25px;
            border-bottom: 2px solid #e8f5e9;
            padding-bottom: 10px;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: #444;
            margin-bottom: 8px;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            color: #333;
            transition: 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #2e7d32;
            outline: none;
            box-shadow: 0 0 5px rgba(46,125,50,0.2);
        }

        /* TOMBOL */
        .btn-submit {
            background-color: #2e7d32;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
            width: 100%;
        }

        .btn-submit:hover {
            background-color: #1b5e20;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">

        <div class="sidebar-header">
            <span>E-Arsip YMIK</span>
        </div>

        <ul class="menu-list">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="surat_masuk.php">Kelola Surat Masuk</a></li>
            <li><a href="surat_keluar.php">Kelola Surat Keluar</a></li>
            <li><a href="buat_surat.php" class="active">Membuat Surat Keluar</a></li>
            <li><a href="cetak_surat.php">Cetak Laporan</a></li>
            <li><a href="logout.php" class="logout-btn">Logout</a></li>
        </ul>

    </div>

    <!-- KONTEN UTAMA -->
    <div class="main-content">

        <div class="content-area">

            <div class="card-form">

                <h3>Membuat Surat Keluar (Otomatis PDF / Preview)</h3>

                <form action="cetak_preview_surat.php" method="POST" id="formSurat">

                    <div class="form-group">
                        <label>Nomor Surat</label>
                        <input
                            type="text"
                            name="no_surat"
                            required
                            placeholder="Contoh: 005/SMK-YMIK/X/2026">
                    </div>

                    <div class="form-group">
                        <label>Tanggal Surat</label>
                        <input
                            type="date"
                            name="tgl_surat"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Tujuan Surat</label>
                        <input
                            type="text"
                            name="tujuan"
                            required
                            placeholder="Masukkan nama yang ingin dituju...">
                    </div>

                    <div class="form-group">
                        <label>Perihal Surat</label>
                        <input
                            type="text"
                            name="perihal"
                            required
                            placeholder="Contoh: Permohonan Izin Magang">
                    </div>

                    <div class="form-group">
                        <label>Isi Lengkap Surat</label>
                        <textarea
                            name="isi_surat"
                            id="isi_surat"
                            rows="10"
                            required
                            placeholder="Tuliskan isi kalimat lengkap surat di sini..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Nama Penandatangan / Kepala Sekolah</label>
                        <input
                            type="text"
                            name="nama_penandatangan"
                            required
                            placeholder="Masukkan nama penandatangan surat...">
                    </div>

                    <div class="form-group">
                        <label>Jabatan Penandatangan</label>
                        <input
                            type="text"
                            name="jabatan_penandatangan"
                            required
                            placeholder="Contoh: Kepala SMK YMIK">
                    </div>

                    <button type="submit" class="btn-submit">
                        Pratinjau / Cetak PDF
                    </button>

                </form>

            </div>

        </div>

    </div>

    <!-- SCRIPT CKEDITOR DAN PENYIMPANAN DATA SEMENTARA -->
    <script>
        // Mengaktifkan CKEditor
        CKEDITOR.replace('isi_surat', {
            versionCheck: false,
            height: 250
        });

        // Menyimpan data form sementara sebelum membuka preview
        document.getElementById('formSurat').addEventListener('submit', function() {
            var dataSurat = {
                no_surat: document.querySelector('[name="no_surat"]').value,
                tgl_surat: document.querySelector('[name="tgl_surat"]').value,
                tujuan: document.querySelector('[name="tujuan"]').value,
                perihal: document.querySelector('[name="perihal"]').value,
                isi_surat: CKEDITOR.instances.isi_surat.getData(),
                nama_penandatangan: document.querySelector('[name="nama_penandatangan"]').value,
                jabatan_penandatangan: document.querySelector('[name="jabatan_penandatangan"]').value
            };

            sessionStorage.setItem('dataSuratYMIK', JSON.stringify(dataSurat));
        });

        // Mengembalikan data form ketika kembali dari preview (DIPERBAIKI AGAR CKEDITOR STABIL)
        window.addEventListener('load', function() {
            var dataTersimpan = sessionStorage.getItem('dataSuratYMIK');

            if (dataTersimpan) {
                var data = JSON.parse(dataTersimpan);

                document.querySelector('[name="no_surat"]').value = data.no_surat;
                document.querySelector('[name="tgl_surat"]').value = data.tgl_surat;
                document.querySelector('[name="tujuan"]').value = data.tujuan;
                document.querySelector('[name="perihal"]').value = data.perihal;
                document.querySelector('[name="nama_penandatangan"]').value = data.nama_penandatangan;
                document.querySelector('[name="jabatan_penandatangan"]').value = data.jabatan_penandatangan;

                // Memastikan CKEditor sudah siap sepenuhnya sebelum diisi data kembali
                if (CKEDITOR.instances.isi_surat) {
                    CKEDITOR.instances.isi_surat.on('instanceReady', function() {
                        this.setData(data.isi_surat);
                    });
                    if (CKEDITOR.instances.isi_surat.status === 'ready') {
                        CKEDITOR.instances.isi_surat.setData(data.isi_surat);
                    }
                }
            }
        });
    </script>

</body>
</html>