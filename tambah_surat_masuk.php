<?php
session_start();
if($_SESSION['level_user'] != 'Admin TU'){
    echo "<script>alert('Akses Ditolak! Anda bukan Admin TU.'); window.location='surat_masuk.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Surat Masuk - E-Arsip SMK YMIK</title>
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
        .menu-list li a.logout-btn {
            color: #d32f2f;
        }
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
        }
        .content-area {
            padding: 30px;
        }
        .card-form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            border-top: 5px solid #2e7d32;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            max-width: 600px;
        }
        .card-form h3 {
            color: #2e7d32;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #2e7d32;
        }
        .btn-submit {
            background-color: #2e7d32;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            font-size: 15px;
        }
        .btn-submit:hover {
            background-color: #1b5e20;
        }
        .btn-back {
            display: inline-block;
            margin-bottom: 15px;
            color: #2e7d32;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            E-Arsip YMIK
        </div>
        <ul class="menu-list">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="surat_masuk.php" class="active">Kelola Surat Masuk</a></li>
            <li><a href="surat_keluar.php">Kelola Surat Keluar</a></li>
            <li><a href="buat_surat.php">Membuat Surat Keluar</a></li>
            <li><a href="cetak_surat.php">Cetak Laporan</a></li>
            <li><a href="logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <span>Login sebagai: <b><?php echo $_SESSION['username']; ?></b></span>
        </div>
        
        <div class="content-area">
            <a href="surat_masuk.php" class="btn-back">&laquo; Kembali ke Data Surat Masuk</a>
            <div class="card-form">
                <h3>Tambah Surat Masuk</h3>
                <form action="proses_tambah_surat_masuk.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>No Surat</label>
                        <input type="text" name="no_surat" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Diterima</label>
                        <input type="date" name="tgl_diterima" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Surat</label>
                        <input type="date" name="tgl_surat" required>
                    </div>
                    <div class="form-group">
                        <label>Asal Instansi</label>
                        <input type="text" name="asal_instansi" required>
                    </div>
                    <div class="form-group">
                        <label>Perihal</label>
                        <textarea name="perihal" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Upload File Scan (PDF/JPG/PNG max 5MB)</label>
                        <input type="file" name="file_scan" accept=".pdf, .jpg, .jpeg, .png" required>
                    </div>
                    <button type="submit" class="btn-submit">Simpan Data Surat</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>