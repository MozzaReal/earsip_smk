<?php 
session_start();
if($_SESSION['level_user']==""){
    header("location:login.php?pesan=belum_login");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - E-Arsip SMK YMIK</title>
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
            background-color: #2e7d32; /* Hijau SMK */
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
        .menu-list li a:hover {
            background-color: #e8f5e9;
            border-left: 4px solid #2e7d32;
            color: #2e7d32;
            font-weight: bold;
        }
        .menu-list li a.logout-btn {
            color: #d32f2f;
        }
        .menu-list li a.logout-btn:hover {
            background-color: #ffebee;
            border-left-color: #d32f2f;
        }
        
        
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background-color: #ffffff;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .content-area {
            padding: 30px;
        }
        .welcome-card {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            border-top: 5px solid #2e7d32;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .welcome-card h3 {
            color: #2e7d32;
            margin-top: 0;
        }
        .badge {
            background-color: #2e7d32;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-header">
            E-Arsip YMIK
        </div>
        <ul class="menu-list">
            <li><a href="dashboard.php" style="background-color:#e8f5e9; border-left:4px solid #2e7d32; color:#2e7d32; font-weight:bold;">Dashboard</a></li>
            
            <?php if($_SESSION['level_user'] == 'Admin TU'){ ?>
                <li><a href="surat_masuk.php">Kelola Surat Masuk</a></li>
                <li><a href="surat_keluar.php">Kelola Surat Keluar</a></li>
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

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="topbar">
            <span>Login sebagai: <b><?php echo $_SESSION['username']; ?></b></span>
        </div>
        
        <div class="content-area">
            <div class="welcome-card">
                <h3>Selamat Datang di Sistem Informasi Kearsipan</h3>
                <p>Halo <b><?php echo $_SESSION['username']; ?></b>, Anda memiliki akses sebagai <span class="badge"><?php echo $_SESSION['level_user']; ?></span>.</p>
                <p>Gunakan menu di sebelah kiri untuk mengelola data surat SMK YMIK.</p>
            </div>
        </div>
    </div>

</body>
</html>