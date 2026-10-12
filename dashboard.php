<?php
session_start();
include 'koneksi.php';

if($_SESSION['level_user'] == ""){
    header("location:login.php?pesan=belum_login");
    exit();
}

// Hitung total surat masuk
$q_masuk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM surat_masuk");
$d_masuk = mysqli_fetch_assoc($q_masuk);
$total_masuk = $d_masuk['total'] ?? 0;

// Hitung total surat keluar
$q_keluar = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM surat_keluar");
$d_keluar = mysqli_fetch_assoc($q_keluar);
$total_keluar = $d_keluar['total'] ?? 0;
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

        .welcome-card {
            background-color: #ffffff;
            padding: 20px 25px;
            border-radius: 8px;
            border-top: 5px solid #2e7d32;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        .welcome-card h2 {
            color: #2e7d32;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .badge-role {
            background-color: #2e7d32;
            color: white;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 13px;
        }

        /* Container untuk Kartu Statistik */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }
        .stat-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 5px solid #2e7d32;
        }
        .stat-card.blue { border-left-color: #0288d1; }
        .stat-card.orange { border-left-color: #f57c00; }

        .stat-info h4 {
            margin: 0;
            color: #666;
            font-size: 14px;
            font-weight: normal;
        }
        .stat-info div {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-top: 5px;
        }
        .stat-icon {
            font-size: 35px;
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            E-Arsip YMIK
        </div>
        <ul class="menu-list">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
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

    <div class="main-content">
        <div class="topbar">
            <span>Login sebagai: <b><?php echo $_SESSION['username'] ?? 'Admin'; ?></b></span>
        </div>
        
        <div class="content-area">
            <!-- Kartu Ucapan Selamat Datang -->
            <div class="welcome-card">
                <h2>Selamat Datang di Sistem Informasi Kearsipan</h2>
                <p>Halo <b><?php echo $_SESSION['username'] ?? 'User'; ?></b>, Anda memiliki akses sebagai <span class="badge-role"><?php echo $_SESSION['level_user']; ?></span>.</p>
                <p style="margin-bottom: 0; color: #666;">Gunakan menu di sebelah kiri untuk mengelola data surat SMK YMIK.</p>
            </div>

            <!-- Ringkasan Statistik Data -->
            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-info">
                        <h4>Total Surat Masuk</h4>
                        <div><?php echo $total_masuk; ?></div>
                    </div>
                    <div class="stat-icon">📥</div>
                </div>

                <div class="stat-card orange">
                    <div class="stat-info">
                        <h4>Total Surat Keluar</h4>
                        <div><?php echo $total_keluar; ?></div>
                    </div>
                    <div class="stat-icon">📤</div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>