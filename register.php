<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-image: url(asset/background-register.avif);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Mulai dari atas */
            min-height: 100vh;
            margin: 0;
            padding-top: 50px; /* Jarak dari atas */
        }

        .login-container {
            width: 100%;
            max-width: 450px; /* Lebar maksimal konten */
            background-color: white;
            padding: 40px 30px;
            margin-bottom: 50px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); /* Sedikit bayangan */
            border-radius: 4px;
        }

        .header {
            margin-bottom: 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .logo img {
            /* Asumsikan Anda menggunakan logo NBA */
            filter: invert(0); /* Pastikan logo berwarna gelap/hitam */
        }

        .logo-id {
            font-size: 24px;
            font-weight: bold;
            color: #000;
        }

        h1 {
            font-size: 32px;
            font-weight: 800; /* Ekstra bold */
            color: #000;
            line-height: 1.1;
            margin-top: 0;
            margin-bottom: 0;
            letter-spacing: -0.5px;
        }

        .description, .instruction {
            font-size: 16px;
            color: #555;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .instruction {
            /* Gaya untuk meniru teks "Enter your email address to get started" */
            color: #000;
            font-weight: 500;
            margin-bottom: 25px;
        }

        .form-box {
            /* Meniru tampilan kotak putih yang menampung form */
            padding: 0;
            border: none;
            background: none;
        }

        .input-group {
            margin-bottom: 25px;
            position: relative;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            color: #888;
            margin-bottom: 5px;
            /* Meniru gaya label di atas input */
        }

        .input-group input {
            width: 100%;
            padding: 15px 10px;
            font-size: 16px;
            border: none;
            border-bottom: 2px solid #ccc; /* Garis bawah yang menonjol */
            box-sizing: border-box;
            outline: none;
            transition: border-bottom-color 0.3s;
            background-color: transparent;
        }

        .input-group input:focus {
            border-bottom-color: #000; /* Garis bawah hitam saat fokus */
        }

        .btn-continue {
            width: 100%;
            background-color: #000; /* Warna tombol hitam */
            color: white;
            padding: 18px 20px;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .btn-continue:hover {
            background-color: #333; /* Sedikit lebih terang saat hover */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="header">
            <div class="logo">
                <img src="asset/logo1.png" alt="NBA Logo" style="height: 120px; width: 120px;">
            </div>
            <h1>SIGN UP YOUR ACCOUNT</h1>
        </div>
        
        <p class="description">
            Your Account grants access to exclusive offers, personalized content, NBA events, and more - all just for being a fan.
        </p>

        <p class="instruction">
            Enter your email address and password to get started.
        </p>

        <div class="form-box">
            <?php 
                if (isset($_SESSION['registration_error'])) {
                    echo '<p style="color: red; text-align: center; font-weight: bold;">' . $_SESSION['registration_error'] . '</p>';
                    unset($_SESSION['registration_error']); 
                }
            ?>
            <form action="register_proses.php" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Masukkan Email">
                </div>
                
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan Password">
                </div>

                <button type="submit" class="btn-continue">
                    Continue
                </button>
            </form>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <p style="font-size: 14px; color: #555;">Sudah punya akun? 
                <a href="login.php" style="color: #000; font-weight: bold; text-decoration: none;">Login</a>
            </p>
        </div>
    </div>
</body>
</html>