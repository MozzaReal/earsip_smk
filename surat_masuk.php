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
    <title>Data Surat Masuk - E-Arsip SMK YMIK</title>
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
        .menu-list li a:hover {
            background-color: #e8f5e9;
            border-left: 4px solid #2e7d32;
            color: #2e7d32;
            font-weight: bold;
        }
        .menu-list li a.active {
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
        .content-area {
            padding: 30px;
        }
        .card-table {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            border-top: 5px solid #2e7d32;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .card-header h3 {
            color: #2e7d32;
            margin: 0;
        }
        .btn-add {
            background-color: #2e7d32;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
            transition: 0.3s;
        }
        .btn-add:hover {
            background-color: #1b5e20;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }
        table th {
            background-color: #2e7d32;
            color: white;
        }
        table tr:hover {
            background-color: #f9f9f9;
        }
        .btn-action {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
        }
        .btn-view { background-color: #0288d1; }
        .btn-edit { background-color: #f57c00; }
        .btn-delete { background-color: #d32f2f; }
        .btn-view:hover { background-color: #01579b; }
        .btn-edit:hover { background-color: #e65100; }
        .btn-delete:hover { background-color: #b71c1c; }
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
                <li><a href="surat_masuk.php" class="active">Kelola Surat Masuk</a></li>
                <li><a href="surat_keluar.php">Kelola Surat Keluar</a></li>
                <li><a href="buat_surat.php">Membuat Surat Keluar</a></li>
                <li><a href="cetak_surat.php">Cetak Laporan</a></li>
            <?php } else if($_SESSION['level_user'] == 'Kepala TU'){ ?>
                <li><a href="surat_masuk.php" class="active">Data Surat Masuk</a></li>
                <li><a href="surat_keluar.php">Data Surat Keluar</a></li>
                <li><a href="cetak_surat.php">Cetak Laporan</a></li>
            <?php } ?>
            <li><a href="logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <span>Login sebagai: <b><?php echo $_SESSION['username']; ?></b></span>
        </div>
        
        <div class="content-area">
            <div class="card-table">
                <div class="card-header">
                    <h3>Data Surat Masuk</h3>
                    <?php if($_SESSION['level_user'] == 'Admin TU'){ ?>
                        <a href="tambah_surat_masuk.php" class="btn-add">+ Tambah Surat Masuk</a>
                    <?php } ?>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Surat</th>
                            <th>Tgl Diterima</th>
                            <th>Tgl Surat</th>
                            <th>Asal Instansi</th>
                            <th>Perihal</th>
                            <th>File Scan</th>
                            <?php if($_SESSION['level_user'] == 'Admin TU'){ ?>
                                <th>Aksi</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($koneksi, "SELECT * FROM surat_masuk ORDER BY id_masuk DESC");
                        while($d = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $d['no_surat']; ?></td>
                            <td><?php echo $d['tgl_diterima']; ?></td>
                            <td><?php echo $d['tgl_surat']; ?></td>
                            <td><?php echo $d['asal_instansi']; ?></td>
                            <td><?php echo $d['perihal']; ?></td>
                            <td>
                                <a href="berkas_scan/<?php echo $d['file_scan']; ?>" target="_blank" class="btn-action btn-view">Lihat File</a>
                            </td>
                            <?php if($_SESSION['level_user'] == 'Admin TU'){ ?>
                            <td>
                                <a href="edit_surat_masuk.php?id=<?php echo $d['id_masuk']; ?>" class="btn-action btn-edit">Edit</a>
                                <a href="hapus_surat_masuk.php?id=<?php echo $d['id_masuk']; ?>" class="btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>