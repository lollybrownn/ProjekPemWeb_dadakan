<?php
include "connection.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - HoopWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #1e1e1e; color: #fff; padding-top: 100px; }
        .navbar { background: #c8102e !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">Admin Panel</a>
        <div>
            <span class="me-3">Hi, <?= htmlspecialchars($_SESSION['nama']) ?> (ADMIN)</span>
            <a href="logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container text-center mt-5">
    <h1 class="display-4">Selamat Datang, Admin!</h1>
    <p class="lead">Sekarang kamu bisa kelola konten website.</p>
    
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card bg-dark border-danger">
                <div class="card-body">
                    <h3>Kelola Berita</h3>
                    <a href="admin_news.php" class="btn btn-danger btn-lg">Masuk</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-dark border-warning">
                <div class="card-body">
                    <h3>Kelola Games & Box Score</h3>
                    <a href="admin_games.php" class="btn btn-warning btn-lg text-dark">Masuk</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>