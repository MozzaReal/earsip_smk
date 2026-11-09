<?php
session_start();
include 'koneksi.php';

if ($_SESSION['level_user'] == "") {
    header("location:login.php?pesan=belum_login");
    exit();
}

// Proses Hapus Data History
if (isset($_GET['hapus_id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus_id']);
    mysqli_query($koneksi, "DELETE FROM history_surat WHERE id = '$id'");
    header("location:history_surat.php?pesan=berhasil_hapus");
    exit();
}

// Menangkap nilai filter jika admin memilih jenis surat
$filter_jenis = isset($_GET['filter_jenis']) ? mysqli_real_escape_string($koneksi, $_GET['filter_jenis']) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>History Pembuatan Surat - E-Arsip SMK YMIK</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; background-color: #f9fcfa; display: flex; height: 100vh; overflow: hidden; }
        .sidebar { width: 250px; background-color: #ffffff; box-shadow: 2px 0 5px rgba(0,0,0,0.05); display: flex; flex-direction: column; z-index: 100; }
        .sidebar-header { background-color: #2e7d32; color: white; padding: 20px; text-align: center; font-size: 20px; font-weight: bold; }
        .menu-list { list-style: none; padding: 0; margin: 0; }
        .menu-list li a { display: block; padding: 15px 20px; color: #333; text-decoration: none; border-left: 4px solid transparent; transition: 0.2s; }
        .menu-list li a:hover, .menu-list li a.active { background-color: #e8f5e9; border-left: 4px solid #2e7d32; color: #2e7d32; font-weight: bold; }
        .menu-list li a.logout-btn { color: #d32f2f; }
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .content-area { padding: 30px; }
        .card-table { background-color: #ffffff; padding: 25px; border-radius: 8px; border-top: 5px solid #2e7d32; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .card-table h3 { color: #2e7d32; margin-top: 0; margin-bottom: 20px; }
        
        .filter-form { margin-bottom: 20px; display: flex; gap: 10px; align-items: center; }
        .filter-form select { padding: 8px 12px; border-radius: 4px; border: 1px solid #ccc; font-size: 14px; outline: none; }
        .btn-filter { background-color: #2e7d32; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: bold; }
        .btn-reset { color: #d32f2f; text-decoration: none; font-size: 14px; font-weight: bold; margin-left: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
        table th, table td { border: 1px solid #ddd; padding: 10px 12px; text-align: left; vertical-align: middle; }
        table th { background-color: #e8f5e9; color: #1b5e20; }
        .badge { background: #2e7d32; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        
        .btn-grup { display: flex; gap: 5px; justify-content: center; }
        .btn-hapus { background-color: #d32f2f; color: white; padding: 6px 12px; border: none; border-radius: 4px; font-size: 13px; cursor: pointer; text-decoration: none; }
        .btn-hapus:hover { background-color: #b71c1c; }
        .btn-cetak { background-color: #1976d2; color: white; padding: 6px 12px; border: none; border-radius: 4px; font-size: 13px; cursor: pointer; text-decoration: none; }
        .btn-cetak:hover { background-color: #1565c0; }
        
        .alert-sukses { background-color: #e8f5e9; color: #2e7d32; padding: 10px 15px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c8e6c9; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header"><span>E-Arsip YMIK</span></div>
        <ul class="menu-list">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="surat_masuk.php">Kelola Surat Masuk</a></li>
            <li><a href="surat_keluar.php">Kelola Surat Keluar</a></li>
            <li><a href="buat_surat.php">Membuat Surat Keluar</a></li>
            <li><a href="history_surat.php" class="active">History Pembuatan Surat</a></li>
            <li><a href="cetak_surat.php">Cetak Laporan</a></li>
            <li><a href="logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="content-area">
            <div class="card-table">
                <h3>Riwayat (History) Pembuatan Surat Keluar</h3>
                <p>Daftar seluruh surat yang pernah dibuat dan dicetak melalui sistem.</p>

                <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil_hapus') { ?>
                    <div class="alert-sukses">Riwayat surat berhasil dihapus dari sistem!</div>
                <?php } ?>

                <!-- FITUR FILTER -->
                <form method="GET" action="history_surat.php" class="filter-form">
                    <select name="filter_jenis">
                        <option value="">-- Tampilkan Semua Jenis Surat --</option>
                        <option value="Surat Keterangan Aktif Peserta Didik" <?php if($filter_jenis == 'Surat Keterangan Aktif Peserta Didik') echo 'selected'; ?>>Surat Keterangan Aktif Peserta Didik</option>
                        <option value="Surat Permohonan PKL" <?php if($filter_jenis == 'Surat Permohonan PKL') echo 'selected'; ?>>Surat Permohonan PKL</option>
                        <option value="Surat Umum" <?php if($filter_jenis == 'Surat Umum') echo 'selected'; ?>>Surat Umum (Bebas)</option>
                    </select>
                    <button type="submit" class="btn-filter">Cari</button>
                    <?php if ($filter_jenis != '') { ?>
                        <a href="history_surat.php" class="btn-reset">✖ Reset Pencarian</a>
                    <?php } ?>
                </form>
                
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Surat</th>
                            <th>Nomor Surat</th>
                            <th>Tanggal</th>
                            <th>Tujuan</th>
                            <th>Perihal</th>
                            <th>Penandatangan</th>
                            <th>Waktu Dibuat</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        
                        // Modifikasi Query berdasarkan filter
                        $query_string = "SELECT * FROM history_surat";
                        if ($filter_jenis != '') {
                            $query_string .= " WHERE jenis_surat = '$filter_jenis'";
                        }
                        $query_string .= " ORDER BY id DESC";
                        
                        $query = mysqli_query($koneksi, $query_string);
                        if (mysqli_num_rows($query) > 0) {
                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>
                                    <td>{$no}</td>
                                    <td><span class='badge'>{$row['jenis_surat']}</span></td>
                                    <td>{$row['no_surat']}</td>
                                    <td>{$row['tgl_surat']}</td>
                                    <td>{$row['tujuan']}</td>
                                    <td>{$row['perihal']}</td>
                                    <td>{$row['nama_penandatangan']}</td>
                                    <td>{$row['dibuat_pada']}</td>
                                    <td>
                                        <div class='btn-grup'>
                                            <a href='cetak_ulang.php?id={$row['id']}' class='btn-cetak' target='_blank'>Cetak</a>
                                            <a href='history_surat.php?hapus_id={$row['id']}' class='btn-hapus' onclick='return confirm(\"Apakah kamu yakin ingin menghapus riwayat surat nomor {$row['no_surat']} ini?\")'>Hapus</a>
                                        </div>
                                    </td>
                                </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='9' style='text-align:center;'>Belum ada riwayat pembuatan surat.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>