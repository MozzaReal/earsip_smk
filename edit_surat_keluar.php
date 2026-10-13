<?php
session_start();
include 'koneksi.php';

if($_SESSION['level_user'] == ""){
    header("location:login.php?pesan=belum_login");
    exit();
}

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM surat_keluar WHERE id_keluar = '$id'");
$d = mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Surat Keluar - E-Arsip SMK YMIK</title>
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
            max-width: 700px;
        }
        .card-form h3 {
            color: #2e7d32;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #e8f5e9;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 6px;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group textarea,
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }
        .form-group input[type="text"]:focus,
        .form-group input[type="date"]:focus,
        .form-group textarea:focus {
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
        }
        .btn-submit:hover {
            background-color: #1b5e20;
        }
        .btn-back {
            background-color: #757575;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
            display: inline-block;
            margin-left: 10px;
            transition: 0.3s;
        }
        .btn-back:hover {
            background-color: #616161;
        }
        .file-info {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
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
            <?php if($_SESSION['level_user'] == 'Admin TU'){ ?>
                <li><a href="surat_masuk.php">Kelola Surat Masuk</a></li>
                <li><a href="surat_keluar.php" class="active">Kelola Surat Keluar</a></li>
                <li><a href="buat_surat.php">Membuat Surat Keluar</a></li>
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
                <h3>Edit Surat Keluar</h3>
                <form action="proses_edit_surat_keluar.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_keluar" value="<?php echo $d['id_keluar']; ?>">
                    
                    <div class="form-group">
                        <label>No Surat</label>
                        <input type="text" name="no_surat" value="<?php echo htmlspecialchars($d['no_surat']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Surat</label>
                        <input type="date" name="tgl_surat" value="<?php echo $d['tgl_surat']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tujuan Instansi</label>
                        <input type="text" name="tujuan" value="<?php echo htmlspecialchars($d['tujuan']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Perihal</label>
                        <textarea name="perihal" rows="3" required><?php echo htmlspecialchars($d['perihal']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>File Scan (Biarkan kosong jika tidak ingin mengubah file)</label>
                        <input type="file" name="file_scan" accept=".pdf,.jpg,.jpeg,.png">
                        <?php if(!empty($d['file_scan'])){ ?>
                            <div class="file-info">File saat ini: <b><?php echo $d['file_scan']; ?></b></div>
                        <?php } ?>
                    </div>
                    <div style="margin-top: 20px;">
                        <button type="submit" name="update" class="btn-submit">Update Data</button>
                        <a href="surat_keluar.php" class="btn-back">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>