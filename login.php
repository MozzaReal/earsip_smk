<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - E-Arsip SMK YMIK</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e8f5e9; /* Hijau sangat muda untuk background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 350px;
            text-align: center;
            border-top: 5px solid #2e7d32; /* Garis hijau tegas di atas */
        }
        .login-container h2 {
            color: #2e7d32; /* Hijau gelap SMK */
            margin-bottom: 5px;
        }
        .login-container p {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 15px;
        }
        .input-group input:focus {
            outline: none;
            border-color: #2e7d32;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #2e7d32;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-login:hover {
            background-color: #1b5e20;
        }
        .alert {
            color: #d32f2f;
            background-color: #ffebee;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>E-Arsip SMK YMIK</h2>
        <p>Silakan login untuk mengakses sistem</p>

        <?php 
        if(isset($_GET['pesan'])){
            if($_GET['pesan'] == "gagal"){
                echo "<div class='alert'>Login gagal! Username atau password salah.</div>";
            }else if($_GET['pesan'] == "belum_login"){
                echo "<div class='alert'>Anda harus login untuk mengakses halaman.</div>";
            }
        }
        ?>

        <form action="proses_login.php" method="POST">
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn-login">LOGIN</button>
        </form>
    </div>

</body>
</html>