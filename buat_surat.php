<?php
session_start();
include 'koneksi.php';

if($_SESSION['level_user'] == ""){
    header("location:login.php?pesan=belum_login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Membuat Surat Keluar - E-Arsip SMK YMIK</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f7f6;
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #ffffff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
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
        .menu-list li a:hover, .menu-list li a.active {
            background-color: #e8f5e9;
            border-left: 4px solid #2e7d32;
            color: #2e7d32;
            font-weight: bold;
        }
        .menu-list li a.logout-btn { color: #d32f2f; }
        .menu-list li a.logout-btn:hover { background-color: #ffebee; border-left-color: #d32f2f; }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .topbar {
            background-color: #ffffff;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .content-area { padding: 30px; }

        .card-form {
            background-color: #ffffff;
            padding: 25px 30px;
            border-radius: 8px;
            border-top: 5px solid #2e7d32;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            max-width: 800px;
        }
        .card-form h3 {
            color: #2e7d32;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #e8f5e9;
            padding-bottom: 10px;
        }
        .section-title {
            font-size: 15px;
            font-weight: bold;
            color: #2e7d32;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
            box-sizing: border-box;
        }
        .form-group input:focus, .form-group textarea:focus {
            border-color: #2e7d32;
            outline: none;
        }
        .btn-submit {
            background-color: #2e7d32;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 20px;
        }
        .btn-submit:hover { background-color: #1b5e20; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            E-Arsip YMIK
        </div>
        <ul class="menu-list">
            <li><a href="dashboard.php">Dashboard</a></li>
            <?php if($_SESSION['level_user'] == 'Admin TU'){ ?>
                <li><a href="surat_masuk.php">Kelola Surat Masuk</a></li>
                <li><a href="surat_keluar.php">Kelola Surat Keluar</a></li>
                <li><a href="buat_surat.php" class="active">Membuat Surat Keluar</a></li>
                <li><a href="cetak_surat.php">Cetak Laporan</a></li>
            <?php } else if($_SESSION['level_user'] == 'Kepala TU'){ ?>
                <li><a href="surat_masuk.php">Data Surat Masuk</a></li>
                <li><a href="surat_keluar.php">Data Surat Keluar</a></li>
                <li><a href="cetak_surat.php">Cetak Laporan</a></li>
            <?php } ?>
            <li><a href="logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <span>Login sebagai: <b><?php echo $_SESSION['username'] ?? 'Admin'; ?></b></span>
        </div>
        
        <div class="content-area">
            <div class="card-form">
                <h3>Form Pembuatan Surat Keluar Otomatis</h3>
                <form action="cetak_preview_surat.php" method="POST" target="_blank">
                    
                    <div class="section-title">1. Pengaturan Kop Surat</div>
                    <div class="form-group">
                        <label>Nama Yayasan / Instansi Atas</label>
                        <input type="text" name="nama_yayasan" value="YAYASAN MASJID AL-IKHLAS (YMIK)" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Unit / Sekolah</label>
                        <input type="text" name="nama_sekolah" value="SMK YMIK JAKARTA" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat & Kontak Sekolah</label>
                        <input type="text" name="alamat_sekolah" value="Jl. H. Usa No. 1, Cipayung, Jakarta Timur | Telp. (021) 8430..." required>
                    </div>

                    <div class="section-title">2. Detail Isi Surat</div>
                    <div class="form-group">
                        <label>Nomor Surat</label>
                        <input type="text" name="no_surat" placeholder="Contoh: 421.5/123-SMK/YMIK/2026" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Surat</label>
                        <input type="date" name="tgl_surat" required>
                    </div>
                    <div class="form-group">
                        <label>Kepada Yth. / Tujuan</label>
                        <input type="text" name="tujuan" placeholder="Contoh: Orang Tua / Wali Murid Kelas XII" required>
                    </div>
                    <div class="form-group">
                        <label>Perihal Surat</label>
                        <input type="text" name="perihal" placeholder="Contoh: Undangan Rapat Orang Tua Murid" required>
                    </div>
                    <div class="form-group">
                        <label>Isi Paragraf Surat</label>
                        <textarea name="isi_surat" rows="6" placeholder="Tuliskan isi pengumuman atau maksud surat di sini..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Nama Penandatangan</label>
                        <input type="text" name="penandatangan" placeholder="Contoh: Drs. H. Hairulloh, M.Pd" required>
                    </div>
                    <div class="form-group">
                        <label>Jabatan Penandatangan</label>
                        <input type="text" name="jabatan" placeholder="Contoh: Kepala Sekolah SMK YMIK" required>
                    </div>

                    <div>
                        <button type="submit" name="preview" class="btn-submit">📄 Cetak / Preview Surat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>