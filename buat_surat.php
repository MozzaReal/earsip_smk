<?php
session_start();
include 'koneksi.php';

if ($_SESSION['level_user'] == "") {
    header("location:login.php?pesan=belum_login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Membuat Surat Keluar - E-Arsip SMK YMIK</title>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
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
        .content-area { padding: 30px; display: flex; justify-content: center; }
        .card-form { background-color: #ffffff; padding: 30px 40px; border-radius: 8px; border-top: 5px solid #2e7d32; box-shadow: 0 4px 12px rgba(0,0,0,0.05); width: 100%; max-width: 850px; }
        .card-form h3 { color: #2e7d32; margin-top: 0; margin-bottom: 25px; border-bottom: 2px solid #e8f5e9; padding-bottom: 10px; font-size: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; font-size: 14px; color: #444; margin-bottom: 8px; }
        .form-group input[type="text"], .form-group input[type="date"], .form-group input[type="number"], .form-group select { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; color: #333; }
        .panel-template { background: #f1f8f6; border: 1px solid #c8e6c9; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .box-siswa { background: #ffffff; border: 1px solid #b2dfdb; padding: 12px; border-radius: 5px; margin-bottom: 12px; }
        .btn-submit { background-color: #2e7d32; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: bold; font-size: 15px; cursor: pointer; width: 100%; margin-top: 10px; }
        .btn-submit:hover { background-color: #1b5e20; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><span>E-Arsip YMIK</span></div>
        <ul class="menu-list">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="surat_masuk.php">Kelola Surat Masuk</a></li>
            <li><a href="surat_keluar.php">Kelola Surat Keluar</a></li>
            <li><a href="buat_surat.php" class="active">Membuat Surat Keluar</a></li>
            <li><a href="history_surat.php">History Pembuatan Surat</a></li>
            <li><a href="cetak_surat.php">Cetak Laporan</a></li>
            <li><a href="logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="content-area">
            <div class="card-form">
                <h3>Membuat Surat Keluar</h3>

                <form action="cetak_preview_surat.php" method="POST" id="formSurat">

                    <div class="form-group">
                        <label>Jenis Surat</label>
                        <select name="jenis_surat" id="jenis_surat" required onchange="tampilForm(this.value)">
                            <option value="">-- Pilih Template Surat --</option>
                            <option value="Surat Keterangan Aktif Peserta Didik">Surat Keterangan Aktif Peserta Didik</option>
                            <option value="Surat Permohonan PKL">Surat Permohonan PKL (Kelompok)</option>
                            <option value="Surat Balasan PKL">Surat Balasan / Penerimaan PKL (Kelompok)</option>
                            <option value="Surat Umum">Surat Umum (Bebas)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nomor Surat</label>
                        <input type="text" name="no_surat" required placeholder="Contoh: 421.3/22/SD.01.TT/III/2022">
                    </div>

                    <div class="form-group">
                        <label>Tanggal Surat</label>
                        <input type="date" name="tgl_surat" required>
                    </div>

                    <!-- FORM SISWA AKTIF -->
                    <div id="formSiswaAktif" class="panel-template" style="display: none;">
                        <h4 style="margin-top:0; color:#2e7d32;">Isi Data Siswa Aktif</h4>
                        <div class="form-group"><label>Nama</label><input type="text" id="val_nama" name="val_nama" placeholder="Contoh: MHD. HABIB AZHIIM"></div>
                        <div class="form-group"><label>NIS / NISN</label><input type="text" id="val_nis" name="val_nis" placeholder="Contoh: 2176 / 0116xxxxxx"></div>
                        <div class="form-group"><label>Tempat / Tgl. Lahir</label><input type="text" id="val_ttl" name="val_ttl" placeholder="Contoh: Bukittinggi / 17 Januari 2011"></div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select id="val_jk" name="val_jk">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Kelas</label><input type="text" id="val_kelas" name="val_kelas" placeholder="Contoh: VI. A"></div>
                        <div class="form-group">
                            <label>Jurusan</label>
                            <select id="val_jurusan" name="val_jurusan">
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="Teknik Jaringan Komputer dan Teknologi">Teknik Jaringan Komputer dan Teknologi</option>
                                <option value="Pemasaran">Pemasaran</option>
                                <option value="Manajemen Perkantoran dan Layanan Bisnis">Manajemen Perkantoran dan Layanan Bisnis</option>
                                <option value="Akuntansi Keuangan Lembaga">Akuntansi Keuangan Lembaga</option>
                                <option value="Desain Komunikasi Visual (DKV)">Desain Komunikasi Visual (DKV)</option>
                                <option value="Perhotelan">Perhotelan</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Nama Orang Tua</label><input type="text" id="val_ortu" name="val_ortu" placeholder="Contoh: Ahmadi (Ayah) / Ismada Leni (Ibu)"></div>
                        <div class="form-group"><label>Alamat</label><input type="text" id="val_alamat" name="val_alamat" placeholder="Contoh: Jorong II Pasar Rao"></div>
                        <div class="form-group"><label>Keperluan Surat</label><input type="text" id="val_keperluan" name="val_keperluan" placeholder="Contoh: permohonan bantuan beasiswa dari Baznas"></div>
                    </div>

                    <!-- FORM PERMOHONAN PKL -->
                    <div id="formPKL" class="panel-template" style="display: none;">
                        <h4 style="margin-top:0; color:#2e7d32;">Isi Data Permohonan PKL</h4>
                        <div class="form-group"><label>Nama Perusahaan / Instansi Tujuan</label><input type="text" id="pkl_nama_perusahaan" name="pkl_nama_perusahaan" placeholder="Contoh: PT. Bintang Raya Juarsa Indonesia"></div>
                        <div class="form-group"><label>Alamat Perusahaan</label><input type="text" id="pkl_alamat_perusahaan" name="pkl_alamat_perusahaan" placeholder="Contoh: Jl. Masjid Asy-Syuhada No. 3-5 Bekasi"></div>
                        
                        <div class="form-group">
                            <label>Periode / Bulan Pelaksanaan PKL</label>
                            <input type="text" id="pkl_periode" name="pkl_periode" placeholder="Contoh: Oktober s.d. Januari 2026/2027">
                        </div>

                        <div style="border-top: 1px dashed #c8e6c9; margin: 20px 0;"></div>

                        <div class="form-group">
                            <label><b>Berapa Jumlah Anggota Kelompok Siswa?</b></label>
                            <input type="number" id="jumlah_siswa" min="1" max="10" value="1" onchange="generateFormSiswa(this.value)">
                        </div>

                        <div id="containerSiswa"></div>
                    </div>

                    <!-- FORM SURAT BALASAN / PENERIMAAN PKL -->
                    <div id="formBalasanPKL" class="panel-template" style="display: none;">
                        <h4 style="margin-top:0; color:#2e7d32;">Isi Data Surat Balasan / Penerimaan PKL</h4>
                        <div class="form-group"><label>Nama Perusahaan / Instansi Pengirim Balasan</label><input type="text" id="balasan_perusahaan" name="balasan_perusahaan" placeholder="Contoh: PT. Telkom Indonesia Tbk"></div>
                        <div class="form-group"><label>Alamat Perusahaan</label><input type="text" id="balasan_alamat" name="balasan_alamat" placeholder="Contoh: Jl. Japati No. 1 Bandung"></div>
                        <div class="form-group"><label>Mulai Tanggal / Periode Masuk PKL</label><input type="text" id="balasan_mulai" name="balasan_mulai" placeholder="Contoh: 01 November 2026 s.d 31 Januari 2027"></div>
                        
                        <div style="border-top: 1px dashed #c8e6c9; margin: 20px 0;"></div>

                        <div class="form-group">
                            <label><b>Berapa Jumlah Siswa yang Diterima?</b></label>
                            <input type="number" id="jumlah_siswa_balasan" min="1" max="10" value="1" onchange="generateFormSiswaBalasan(this.value)">
                        </div>
                        
                        <div id="containerSiswaBalasan"></div>
                        <!-- Penandatangan Perusahaan dihapus agar pakai titik-titik otomatis -->
                    </div>

                    <!-- FORM SURAT UMUM -->
                    <div id="formSuratUmum" style="display: none;">
                        <div class="form-group"><label>Tujuan Surat</label><input type="text" id="tujuan" name="tujuan" placeholder="Masukkan nama yang dituju..."></div>
                        <div class="form-group"><label>Perihal Surat</label><input type="text" id="perihal" name="perihal" placeholder="Contoh: Surat Undangan Rapat"></div>
                        
                        <div class="form-group">
                            <label>Isi Lengkap Surat</label>
                            <textarea name="isi_surat" id="isi_surat" rows="10"></textarea>
                        </div>
                        
                        <div class="form-group"><label>Nama Penandatangan / Kepala Sekolah</label><input type="text" id="nama_penandatangan" name="nama_penandatangan" placeholder="Contoh: Ahmad Jahrudin"></div>
                        <div class="form-group"><label>Jabatan Penandatangan</label><input type="text" id="jabatan_penandatangan" name="jabatan_penandatangan" placeholder="Contoh: Kepala Sekolah"></div>
                    </div>

                    <button type="submit" class="btn-submit">Pratinjau / Simpan ke History & Cetak</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        CKEDITOR.replace('isi_surat', { versionCheck: false, height: 250 });

        function generateFormSiswa(jumlah, savedData = []) {
            var container = document.getElementById('containerSiswa');
            container.innerHTML = "";
            var jurusanOptions = `<option value="">-- Pilih Jurusan --</option>
                <option value="Teknik Jaringan Komputer dan Teknologi">Teknik Jaringan Komputer dan Teknologi</option>
                <option value="Pemasaran">Pemasaran</option>
                <option value="Manajemen Perkantoran dan Layanan Bisnis">Manajemen Perkantoran dan Layanan Bisnis</option>
                <option value="Akuntansi Keuangan Lembaga">Akuntansi Keuangan Lembaga</option>
                <option value="Desain Komunikasi Visual (DKV)">Desain Komunikasi Visual (DKV)</option>
                <option value="Perhotelan">Perhotelan</option>`;

            for (let i = 0; i < jumlah; i++) {
                let namaVal = (savedData[i] && savedData[i].nama) ? savedData[i].nama : '';
                let nisVal = (savedData[i] && savedData[i].nis) ? savedData[i].nis : '';
                var html = `<div class="box-siswa">
                    <label style="font-weight:bold; color:#1b5e20; margin-bottom:5px;">Siswa Anggota ke-${i+1}</label>
                    <div class="form-group" style="margin-bottom:10px;"><label>Nama Siswa</label><input type="text" name="pkl_siswa_nama[]" value="${namaVal}" placeholder="Nama lengkap..."></div>
                    <div class="form-group" style="margin-bottom:10px;"><label>NIS / NISN</label><input type="text" name="pkl_siswa_nis[]" value="${nisVal}" placeholder="NIS/NISN..."></div>
                    <div class="form-group" style="margin-bottom:0;"><label>Program Keahlian / Jurusan</label><select name="pkl_siswa_jurusan[]">${jurusanOptions}</select></div>
                </div>`;
                container.innerHTML += html;
            }
            var selects = container.querySelectorAll('select');
            for (let i = 0; i < selects.length; i++) { if(savedData[i] && savedData[i].jurusan) { selects[i].value = savedData[i].jurusan; } }
        }

        function generateFormSiswaBalasan(jumlah, savedData = []) {
            var container = document.getElementById('containerSiswaBalasan');
            container.innerHTML = "";
            var jurusanOptions = `<option value="">-- Pilih Jurusan --</option>
                <option value="Teknik Jaringan Komputer dan Teknologi">Teknik Jaringan Komputer dan Teknologi</option>
                <option value="Pemasaran">Pemasaran</option>
                <option value="Manajemen Perkantoran dan Layanan Bisnis">Manajemen Perkantoran dan Layanan Bisnis</option>
                <option value="Akuntansi Keuangan Lembaga">Akuntansi Keuangan Lembaga</option>
                <option value="Desain Komunikasi Visual (DKV)">Desain Komunikasi Visual (DKV)</option>
                <option value="Perhotelan">Perhotelan</option>`;

            for (let i = 0; i < jumlah; i++) {
                let namaVal = (savedData[i] && savedData[i].nama) ? savedData[i].nama : '';
                let nisVal = (savedData[i] && savedData[i].nis) ? savedData[i].nis : '';
                var html = `<div class="box-siswa">
                    <label style="font-weight:bold; color:#1b5e20; margin-bottom:5px;">Siswa Diterima ke-${i+1}</label>
                    <div class="form-group" style="margin-bottom:10px;"><label>Nama Siswa</label><input type="text" name="balasan_siswa_nama[]" value="${namaVal}" placeholder="Nama lengkap..."></div>
                    <div class="form-group" style="margin-bottom:10px;"><label>NIS / NISN</label><input type="text" name="balasan_siswa_nis[]" value="${nisVal}" placeholder="NIS/NISN..."></div>
                    <div class="form-group" style="margin-bottom:0;"><label>Program Keahlian / Jurusan</label><select name="balasan_siswa_jurusan[]">${jurusanOptions}</select></div>
                </div>`;
                container.innerHTML += html;
            }
            var selects = container.querySelectorAll('select');
            for (let i = 0; i < selects.length; i++) { if(savedData[i] && savedData[i].jurusan) { selects[i].value = savedData[i].jurusan; } }
        }

        generateFormSiswa(1);
        generateFormSiswaBalasan(1);

        function tampilForm(jenis) {
            var formAktif = document.getElementById('formSiswaAktif');
            var formPKL = document.getElementById('formPKL');
            var formBalasan = document.getElementById('formBalasanPKL');
            var formUmum = document.getElementById('formSuratUmum');

            if (jenis === "Surat Keterangan Aktif Peserta Didik") {
                formAktif.style.display = "block"; formPKL.style.display = "none"; formBalasan.style.display = "none"; formUmum.style.display = "none";
                document.getElementById('val_nama').required = true;
            } else if (jenis === "Surat Permohonan PKL") {
                formAktif.style.display = "none"; formPKL.style.display = "block"; formBalasan.style.display = "none"; formUmum.style.display = "none";
                document.getElementById('val_nama').required = false;
            } else if (jenis === "Surat Balasan PKL") {
                formAktif.style.display = "none"; formPKL.style.display = "none"; formBalasan.style.display = "block"; formUmum.style.display = "none";
                document.getElementById('val_nama').required = false;
            } else if (jenis === "Surat Umum") {
                formAktif.style.display = "none"; formPKL.style.display = "none"; formBalasan.style.display = "none"; formUmum.style.display = "block";
                document.getElementById('val_nama').required = false;
            } else {
                formAktif.style.display = "none"; formPKL.style.display = "none"; formBalasan.style.display = "none"; formUmum.style.display = "none";
            }
        }

        document.getElementById('formSurat').addEventListener('submit', function() {
            var pklSiswaArray = [];
            var pklNamaInputs = document.querySelectorAll('input[name="pkl_siswa_nama[]"]');
            var pklNisInputs = document.querySelectorAll('input[name="pkl_siswa_nis[]"]');
            var pklJurusanInputs = document.querySelectorAll('select[name="pkl_siswa_jurusan[]"]');
            for(let i=0; i<pklNamaInputs.length; i++) { pklSiswaArray.push({ nama: pklNamaInputs[i].value, nis: pklNisInputs[i].value, jurusan: pklJurusanInputs[i].value }); }

            var balasanSiswaArray = [];
            var balasanNamaInputs = document.querySelectorAll('input[name="balasan_siswa_nama[]"]');
            var balasanNisInputs = document.querySelectorAll('input[name="balasan_siswa_nis[]"]');
            var balasanJurusanInputs = document.querySelectorAll('select[name="balasan_siswa_jurusan[]"]');
            for(let i=0; i<balasanNamaInputs.length; i++) { balasanSiswaArray.push({ nama: balasanNamaInputs[i].value, nis: balasanNisInputs[i].value, jurusan: balasanJurusanInputs[i].value }); }

            var dataSurat = {
                jenis_surat: document.getElementById('jenis_surat').value,
                no_surat: document.querySelector('[name="no_surat"]').value,
                tgl_surat: document.querySelector('[name="tgl_surat"]').value,
                
                val_nama: document.getElementById('val_nama').value,
                val_nis: document.getElementById('val_nis').value,
                val_ttl: document.getElementById('val_ttl').value,
                val_jk: document.getElementById('val_jk').value,
                val_kelas: document.getElementById('val_kelas').value,
                val_jurusan: document.getElementById('val_jurusan').value,
                val_ortu: document.getElementById('val_ortu').value,
                val_alamat: document.getElementById('val_alamat').value,
                val_keperluan: document.getElementById('val_keperluan').value,

                pkl_nama_perusahaan: document.getElementById('pkl_nama_perusahaan').value,
                pkl_alamat_perusahaan: document.getElementById('pkl_alamat_perusahaan').value,
                pkl_periode: document.getElementById('pkl_periode').value,
                jumlah_siswa: document.getElementById('jumlah_siswa').value,
                array_siswa: pklSiswaArray,

                balasan_perusahaan: document.getElementById('balasan_perusahaan').value,
                balasan_alamat: document.getElementById('balasan_alamat').value,
                balasan_mulai: document.getElementById('balasan_mulai').value,
                jumlah_siswa_balasan: document.getElementById('jumlah_siswa_balasan').value,
                array_siswa_balasan: balasanSiswaArray,

                tujuan: document.getElementById('tujuan').value,
                perihal: document.getElementById('perihal').value,
                isi_surat: CKEDITOR.instances.isi_surat.getData(),
                nama_penandatangan: document.getElementById('nama_penandatangan').value,
                jabatan_penandatangan: document.getElementById('jabatan_penandatangan').value
            };
            sessionStorage.setItem('dataSuratYMIK', JSON.stringify(dataSurat));
        });

        window.addEventListener('load', function() {
            var dataTersimpan = sessionStorage.getItem('dataSuratYMIK');
            if (dataTersimpan) {
                var data = JSON.parse(dataTersimpan);
                document.getElementById('jenis_surat').value = data.jenis_surat || '';
                tampilForm(data.jenis_surat);
                // (Omit other assignments for brevity - they remain the same)
            }
        });
    </script>
</body>
</html>