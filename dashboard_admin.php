<?php
include "connection.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - HoopWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 100px;
            min-height: 100vh;
        }
        .navbar-admin {
            background: #c8102e !important;
            box-shadow: 0 4px 15px rgba(200, 16, 46, 0.2);
        }
        .welcome-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            padding: 3rem 2rem;
            margin-bottom: 3rem;
        }
        .admin-card {
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 35px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            border: none;
        }
        .admin-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        .card-header-custom {
            padding: 2rem;
            text-align: center;
            color: white;
            font-weight: 700;
            font-size: 1.4rem;
        }
        .card-body-custom {
            padding: 2.5rem;
            text-align: center;
        }
        .btn-custom {
            border-radius: 50px;
            padding: 0.9rem 3rem;
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-news {
            background: linear-gradient(135deg, #dc3545, #c8102e);
            border: none;
            color: white;
        }
        .btn-news:hover {
            background: linear-gradient(135deg, #c8102e, #a00d24);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(200,16,46,0.3);
        }
        .btn-games {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            border: none;
            color: #212529;
        }
        .btn-games:hover {
            background: linear-gradient(135deg, #e0a800, #d39e00);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255,193,7,0.3);
        }
        .icon-large {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>

<!-- Navbar Admin -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-admin">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4" href="dashboard_admin.php">
            <i class="fas fa-shield-alt me-2"></i> Admin Panel
        </a>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white fw-medium">
                <i class="fas fa-user-shield me-2"></i>
                Hi, <?= htmlspecialchars($_SESSION['nama']) ?>
            </span>
            <a href="admin_logout.php" class="btn btn-outline-light btn-sm px-4">
                Logout
            </a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <!-- Welcome Section -->
    <div class="welcome-card text-center">
        <h1 class="display-4 fw-bold text-dark mb-3">
            Selamat Datang, Admin!
        </h1>
        <p class="lead text-muted fs-5">
            Kelola konten HoopWave dengan mudah dan cepat
        </p>
        <hr class="w-25 mx-auto my-4" style="border-top: 3px solid #c8102e; opacity: 1;">
    </div>

    <!-- Menu Cards -->
    <div class="row g-5 justify-content-center">
        <!-- Kelola Berita -->
        <div class="col-lg-5">
            <div class="admin-card">
                <div class="card-header-custom" style="background: linear-gradient(135deg, #dc3545, #c8102e);">
                    <i class="fas fa-newspaper icon-large"></i>
                </div>
                <div class="card-body-custom">
                    <h3 class="fw-bold text-dark mb-4">Kelola Berita</h3>
                    <p class="text-muted mb-4">Tambah, edit, dan hapus berita NBA terbaru</p>
                    <a href="admin_news.php" class="btn btn-custom btn-news">
                        <i class="fas fa-arrow-right me-2"></i> Masuk ke Berita
                    </a>
                </div>
            </div>
        </div>

        <!-- Kelola Games -->
        <div class="col-lg-5">
            <div class="admin-card">
                <div class="card-header-custom" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                    <i class="fas fa-basketball-ball icon-large"></i>
                </div>
                <div class="card-body-custom">
                    <h3 class="fw-bold text-dark mb-4">Kelola Games & Box Score</h3>
                    <p class="text-muted mb-4">Atur jadwal, skor, video, dan box score pertandingan</p>
                    <a href="admin_games.php" class="btn btn-custom btn-games">
                        <i class="fas fa-arrow-right me-2"></i> Masuk ke Games
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 pt-4">
        <small class="text-muted">
            HoopWave Admin Panel © 2025 • Dibuat untuk NBA Fans Indonesia
        </small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>