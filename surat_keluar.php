<?php
session_start();
include 'koneksi.php';

if($_SESSION['level_user'] == ""){
    header("location:login.php?pesan=belum_login");
    exit();
}

$query = mysqli_query($koneksi, "SELECT * FROM surat_keluar ORDER BY id_keluar DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Surat Keluar - E-Arsip SMK YMIK</title>
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

        /* Sidebar Styling (Buka-Tutup) */
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

        /* Card & Table Styling */
        .card-table {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            border-top: 5px solid #2e7d32;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .table-header h3 {
            margin: 0;
            color: #2e7d32;
        }
        .btn-add {
            background-color: #2e7d32;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
            transition: 0.2s;
        }
        .btn-add:hover { background-color: #1b5e20; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background-color: #2e7d32;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }
        table tr:hover { background-color: #f9f9f9; }

        .btn-view {
            background-color: #0288d1;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .btn-edit {
            background-color: #f57c00;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 4px;
        }
        .btn-delete {
            background-color: #d32f2f;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>
<body>

    <!-- Sidebar Menu (Bisa Buka-Tutup) -->
    <div class="sidebar collapsed" id="sidebar">
        <div class="sidebar-header">
            <span>E-Arsip YMIK</span>
            <button class="close-btn" onclick="toggleSidebar()">✕</button>
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
                <li><a href="surat_keluar.php" class="active">Data Surat Keluar</a></li>
                <li><a href="cetak_surat.php">Cetak Laporan</a></li>
            <?php } ?>
            <li><a href="logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
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
            <div class="card-table">
                <div class="table-header">
                    <h3>Data Surat Keluar</h3>
                    <?php if($_SESSION['level_user'] == 'Admin TU'){ ?>
                        <a href="tambah_surat_keluar.php" class="btn-add">+ Tambah Surat Keluar</a>
                    <?php } ?>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>No Surat</th>
                            <th>Tgl Surat</th>
                            <th>Tujuan</th>
                            <th>Perihal</th>
                            <th>File Scan</th>
                            <?php if($_SESSION['level_user'] == 'Admin TU'){ ?>
                                <th width="100">Aksi</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($d = mysqli_fetch_array($query)){ 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($d['no_surat']); ?></td>
                            <td><?php echo $d['tgl_surat']; ?></td>
                            <td><?php echo htmlspecialchars($d['tujuan']); ?></td>
                            <td><?php echo htmlspecialchars($d['perihal']); ?></td>
                            <td>
                                <?php if(!empty($d['file_scan'])){ ?>
                                    <a href="berkas_scan/<?php echo $d['file_scan']; ?>" target="_blank" class="btn-view">Lihat File</a>
                                <?php } else { echo "-"; } ?>
                            </td>
                            <?php if($_SESSION['level_user'] == 'Admin TU'){ ?>
                            <td>
                                <a href="edit_surat_keluar.php?id=<?php echo $d['id_keluar']; ?>" class="btn-edit">Edit</a>
                                <a href="hapus_surat_keluar.php?id=<?php echo $d['id_keluar']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
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