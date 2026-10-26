<?php
session_start();
include 'koneksi.php';

if($_SESSION['level_user'] == ""){
    header("location:login.php?pesan=belum_login");
    exit();
}

$id_masuk = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM surat_masuk WHERE id_masuk = '$id_masuk'");
$d = mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Surat Masuk - E-Arsip SMK YMIK</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f7f6;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            width: 250px;
            background-color: #ffffff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 100;
        }
        .sidebar.collapsed { margin-left: -250px; }
        .sidebar-header {
            background-color: #2e7d32;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .close-btn { background: none; border: none; color: white; font-size: 18px; cursor: pointer; display: none; }
        .menu-list { list-style: none; padding: 0; margin: 0; }
        .menu-list li a {
            display: block; padding: 15px 20px; color: #333; text-decoration: none;
            border-left: 4px solid transparent; transition: 0.2s;
        }
        .menu-list li a:hover, .menu-list li a.active {
            background-color: #e8f5e9; border-left: 4px solid #2e7d32; color: #2e7d32; font-weight: bold;
        }
        .menu-list li a.logout-btn { color: #d32f2f; }
        .menu-list li a.logout-btn:hover { background-color: #ffebee; border-left-color: #d32f2f; }

        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar {
            background-color: #ffffff; padding: 15px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex; justify-content: space-between; align-items: center;
        }
        .topbar-left { display: flex; align-items: center; gap: 15px; }
        .toggle-btn { background: none; border: none; color: #2e7d32; font-size: 22px; cursor: pointer; padding: 0; display: flex; align-items: center; }
        
        .content-area { padding: 30px; display: flex; justify-content: center; align-items: flex-start; }

        .card-form {
            background-color: #ffffff; padding: 25px 30px; border-radius: 8px;
            border-top: 5px solid #2e7d32; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            width: 100%; max-width: 700px; margin-bottom: 30px;
        }
        .card-form h3 {
            color: #2e7d32; margin-top: 0; margin-bottom: 20px;
            border-bottom: 2px solid #e8f5e9; padding-bottom: 10px;
        }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-weight: 600; font-size: 14px; color: #333; margin-bottom: 6px; }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group textarea,
        .form-group input[type="file"] {
            width: 100%; padding: 10px 12px; border: 1px solid #cccccc; border-radius: 5px; font-size: 14px; font-family: inherit;
        }
        .btn-submit {
            background-color: #2e7d32; color: white; padding: 10px 20px;
            border: none; border-radius: 5px; font-weight: bold; font-size: 14px; cursor: pointer; transition: 0.3s;
        }
        .btn-submit:hover { background-color: #1b5e20; }
        .btn-batal {
            background-color: #757575; color: white; padding: 10px 20px;
            border: none; border-radius: 5px; font-weight: bold; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-block;
        }
        .btn-batal:hover { background-color: #616161; }
    </style>
</head>
<body>

    <div class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header">
            <span>E-Arsip YMIK</span>
            <button class="close-btn" onclick="toggleSidebar()">✕</button>
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
            <div class="topbar-left">
                <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
                <span>Login sebagai: <b><?php echo $_SESSION['username'] ?? 'Admin'; ?></b></span>
            </div>
            <div>
                <span style="font-size: 14px; color: #555;">E-Arsip SMK YMIK</span>
            </div>
        </div>

        <div class="content-area">
            <div class="card-form">
                <h3>Edit Surat Masuk</h3>
                <!-- PASTIKAN ACTION MENGARAH KE proses_edit_surat_masuk.php -->
                <form action="proses_edit_surat_masuk.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_masuk" value="<?php echo $d['id_masuk']; ?>">
                    
                    <div class="form-group">
                        <label>No Surat</label>
                        <input type="text" name="no_surat" value="<?php echo htmlspecialchars($d['no_surat']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Diterima</label>
                        <input type="date" name="tgl_diterima" value="<?php echo $d['tgl_diterima']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Surat</label>
                        <input type="date" name="tgl_surat" value="<?php echo $d['tgl_surat']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Asal Instansi</label>
                        <input type="text" name="asal_instansi" value="<?php echo htmlspecialchars($d['asal_instansi']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Perihal</label>
                        <textarea name="perihal" rows="4" required><?php echo htmlspecialchars($d['perihal']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>File Scan (Biarkan kosong jika tidak ingin mengubah file)</label>
                        <input type="file" name="file_scan">
                        <?php if(!empty($d['file_scan'])) { ?>
                            <small style="color: #666; display: block; margin-top: 5px;">File saat ini: <a href="berkas_scan/<?php echo $d['file_scan']; ?>" target="_blank"><?php echo $d['file_scan']; ?></a></small>
                        <?php } ?>
                    </div>
                    <button type="submit" name="update" class="btn-submit">Update Data</button>
                    <a href="surat_masuk.php" class="btn-batal">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }
    </script>

</body>
</html>