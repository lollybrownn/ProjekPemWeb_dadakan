<?php
include "connection.php";
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
$nama = htmlspecialchars($_SESSION['nama']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBA News - HoopWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f9f9f9;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            padding-top: 170px; /* akan diatur otomatis oleh JS */
            color: #333;
        }

        /* Navbar Utama (sama seperti di home_games.php) */
        .navbar-main {
            z-index: 1050 !important;
            background-image: url(asset/background-navbar.png);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: transparent !important;
        }

        /* Sub-Navbar MIRIP Games & Scores */
        #subNavbar {
            z-index: 1040 !important;
            background: #f8f9fa !important;
            border-bottom: 1px solid #ddd;
            position: fixed;
            left: 0;
            right: 0;
        }

        /* Underline aktif mirip "Home" di Games */
        .nav-underline-custom {
            position: relative;
            color: #000 !important;
            font-weight: 600;
            padding: 0.75rem 1rem;
        }

        .nav-underline-custom::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 4px;
            background: #000;
            border-radius: 2px;
            transition: all .3s;
        }

        .nav-underline-custom:hover::after,
        .nav-underline-custom.active::after {
            width: 60px;
        }

        .nav-underline-custom.active {
            color: #000 !important;
        }

        /* Hover effect halus */
        .nav-link {
            color: #555 !important;
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: #000 !important;
        }

        /* Teams dropdown tetap lebar */
        .teams-dropdown {
            width: 360px !important;
            max-height: 80vh;
            overflow-y: auto;
            padding: 0.5rem 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .team-item { display:flex !important; align-items:center; gap:12px; padding:0.45rem 1rem; }
        .team-item img { width:26px; height:26px; }
        .team-item:hover { background:#f8f9fa; color:#0d6efd !important; border-radius:6px; }

        /* Hover buka dropdown di desktop */
        @media (min-width: 992px) {
            .dropdown:hover > .dropdown-menu { display: block; }
        }

        /* News Card */
        .news-hero, .news-card { 
            cursor:pointer; 
            transition:transform .3s, box-shadow .3s; 
            border-radius: 16px;
            overflow: hidden;
        }
        .news-hero:hover, .news-card:hover { 
            transform:translateY(-8px); 
            box-shadow:0 15px 40px rgba(0,0,0,0.15); 
        }
        .news-hero-img { height:380px; object-fit:cover; }
        .news-card-img { height:220px; object-fit:cover; }
    </style>
</head>
<body>

    <?php include "navbar.php"; ?>

    <!-- SUB-NAVBAR NEWS (mirip Games & Scores) -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom" id="subNavbar">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-4 text-dark mb-0">News</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#subnavNews">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="subnavNews">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-underline-custom active" href="news.php">Latest</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-underline-custom" href="news.php?tab=breaking">Breaking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-underline-custom" href="news.php?tab=teams">Teams</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-underline-custom" href="news.php?tab=players">Players</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-underline-custom" href="news.php?tab=analysis">Analysis</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ISI HALAMAN NEWS -->
    <div class="container my-5">

        <!-- HERO ARTICLE -->
        <a href="article.php?id=1" class="text-decoration-none text-dark">
            <div class="news-hero bg-white shadow mb-5">
                <img src="https://via.placeholder.com/1200x600/1e3a8a/ffffff?text=LAKERS+WIN+WEST" alt="Lakers" class="news-hero-img w-100">
                <div class="p-5">
                    <span class="badge bg-danger fs-6 mb-3">BREAKING</span>
                    <h1 class="fw-bold display-5">Lakers Secure No. 1 Seed with Dramatic OT Win Over Nuggets</h1>
                    <p class="text-muted fs-5">By Shams Charania • November 26, 2025 • 5 min read</p>
                </div>
            </div>
        </a>

        <div class="row g-4">
            <!-- KOLOM UTAMA -->
            <div class="col-lg-8">
                <!-- Card biasa -->
                <a href="article.php?id=2" class="text-decoration-none text-dark">
                    <div class="news-card bg-white shadow mb-4">
                        <img src="https://via.placeholder.com/800x450/166534/ffffff?text=CELTICS+TRADE" class="news-card-img">
                        <div class="p-4">
                            <h3 class="fw-bold">Celtics Finalizing Blockbuster Trade for All-Star Guard</h3>
                            <p class="text-muted">By Adrian Wojnarowski • 2 hours ago</p>
                        </div>
                    </div>
                </a>
                <!-- Tambah artikel lain sesuka hati -->
            </div>

            <!-- SIDEBAR TRENDING -->
            <div class="col-lg-4">
                <div class="bg-white rounded-4 shadow p-4 sticky-top" style="top:120px;">
                    <h4 class="fw-bold mb-4">Trending Now</h4>
                    <a href="article.php?id=6" class="text-decoration-none text-dark d-block mb-4">
                        <div class="d-flex gap-3">
                            <img src="https://via.placeholder.com/80/000000/ffffff?text=WEMBY" class="rounded" style="width:70px;height:70px;object-fit:cover;">
                            <div>
                                <div class="fw-bold">Wembanyama Records First Career 10-Block Game</div>
                                <small class="text-muted">28k views</small>
                            </div>
                        </div>
                    </a>
                    <hr>
                    <a href="article.php?id=7" class="text-decoration-none text-dark d-block">
                        <div class="d-flex gap-3">
                            <img src="https://via.placeholder.com/80/991111/ffffff?text=BUTLER" class="rounded" style="width:70px;height:70px;object-fit:cover;">
                            <div>
                                <div class="fw-bold">Jimmy Butler Requests Trade from Heat</div>
                                <small class="text-muted">42k views</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Script supaya sub-navbar selalu nempel di bawah navbar utama -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function adjustSubNavbar() {
            const main = document.querySelector('.navbar-main');
            const sub = document.getElementById('subNavbar');
            if (main && sub) {
                sub.style.top = main.offsetHeight + 'px';
                document.body.style.paddingTop = (main.offsetHeight + sub.offsetHeight + 30) + 'px';
            }
        }
        window.addEventListener('load', adjustSubNavbar);
        window.addEventListener('resize', adjustSubNavbar);
    </script>
</body>
</html>