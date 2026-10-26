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
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f7f6;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar Styling (Hijau Putih Khas) */
        .sidebar {
            width: 250px;
            background-color: #ffffff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 100;
        }
        .sidebar.collapsed {
            margin-left: -250px;
        }
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
        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            display: none;
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

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        /* Topbar */
        .topbar {
            background-color: #ffffff;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .toggle-btn {
            background: none;
            border: none;
            color: #2e7d32;
            font-size: 22px;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
        }
        .content-area { padding: 30px; }

        /* Welcome Card: Latar Hijau Tua dengan Teks Putih */
        .welcome-card {
            background-color: #2e7d32;
            color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(46, 125, 50, 0.2);
            margin-bottom: 25px;
        }
        .welcome-card h2 {
            margin: 0 0 8px 0;
            color: #ffffff;
            font-size: 24px;
        }
        .welcome-card p {
            margin: 0;
            color: #e8f5e9;
            font-size: 14px;
        }

        /* Stats Cards dengan Warna Solid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .stat-card {
            padding: 22px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            color: white;
        }
        .stat-card.blue { background-color: #0288d1; }
        .stat-card.green { background-color: #388e3c; }

        .stat-card h4 {
            margin: 0 0 10px 0;
            font-size: 15px;
            font-weight: normal;
            opacity: 0.9;
        }
        .stat-card .val {
            font-size: 30px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Sidebar Menu -->
    <div class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header">
            <span>E-Arsip YMIK</span>
            <button class="close-btn" onclick="toggleSidebar()">✕</button>
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

    <!-- Main Content Area -->
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
            <!-- Welcome Banner (Latar Hijau Tulisan Putih Jelas) -->
            <div class="welcome-card">
                <h2>Selamat Datang, <?php echo $_SESSION['username'] ?? 'User'; ?>!</h2>
                <p>Sistem Informasi Pengelolaan Data Arsip Surat Masuk & Surat Keluar. Pilih menu di samping untuk mulai mengelola data.</p>
            </div>

            <!-- Ringkasan Statistik Berwarna -->
            <div class="stats-grid">
                <div class="stat-card blue">
                    <h4>Total Surat Masuk</h4>
                    <div class="val"><?php echo $total_masuk; ?> Surat</div>
                </div>

                <div class="stat-card green">
                    <h4>Total Surat Keluar</h4>
                    <div class="val"><?php echo $total_keluar; ?> Surat</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script JavaScript Buka-Tutup Sidebar -->
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }
    </script>

</body>
</html>