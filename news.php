<?php
session_start();
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
        body { background:#f9f9f9; font-family:'Helvetica Neue',Arial,sans-serif; padding-top:170px; color:#333; }
        .navbar-main { z-index:1050 !important; background-image:url(asset/background-navbar.png); background-size:cover; background-position:center; background-repeat:no-repeat; }
        #subNavbar { z-index:1040 !important; background:#fff !important; border-bottom:1px solid #e0e0e0; position:fixed; left:0; right:0; }
        .sub-nav-item { color:#666; font-weight:500; padding:0.75rem 1rem; border-bottom:2px solid transparent; text-decoration:none; transition:all .3s; }
        .sub-nav-item:hover, .sub-nav-item.active { color:#000; border-bottom-color:#c8102e; background:#f8f9fa; }

        .news-hero, .news-card { cursor:pointer; transition:transform .3s, box-shadow .3s; }
        .news-hero:hover, .news-card:hover { transform:translateY(-5px); box-shadow:0 10px 30px rgba(0,0,0,0.15); }

        .news-hero-img { height:300px; object-fit:cover; width:100%; }
        .news-card-img { height:200px; object-fit:cover; width:100%; }
        .trending-img { width:60px; height:60px; object-fit:cover; border-radius:6px; }

        @media (max-width:992px) { .news-grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<!-- SUB-NAVBAR NEWS -->
<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom" id="subNavbar">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4 text-dark mb-0">News</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#subnavNews">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="subnavNews">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active sub-nav-item" href="news.php">Latest</a></li>
                <li class="nav-item"><a class="nav-link sub-nav-item" href="news.php?tab=breaking">Breaking News</a></li>
                <li class="nav-item"><a class="nav-link sub-nav-item" href="news.php?tab=teams">Teams</a></li>
                <li class="nav-item"><a class="nav-link sub-nav-item" href="news.php?tab=players">Players</a></li>
                <li class="nav-item"><a class="nav-link sub-nav-item" href="news.php?tab=analysis">Analysis</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">

    <!-- HERO ARTICLE (klik ke artikel 1) -->
    <a href="article.php?id=1" class="text-decoration-none">
        <div class="news-hero bg-white rounded-4 overflow-hidden shadow mb-4">
            <img src="https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/lal.png&w=100&h=100&transparent=true" alt="Lakers" class="news-hero-img">
            <div class="p-4">
                <span class="badge bg-danger mb-2">Breaking</span>
                <h1 class="fw-bold fs-2">Lakers Secure No. 1 Seed in Western Conference with Dramatic Overtime Win</h1>
                <p class="text-muted">By Shams Charania • November 26, 2025 • 5 min read</p>
                <p class="fs-5">LeBron James dan Anthony Davis mengantarkan Lakers mengalahkan Nuggets 112-108 di overtime...</p>
            </div>
        </div>
    </a>

    <div class="row">
        <div class="col-lg-8">
            <div class="news-grid">

                <!-- Artikel 2 -->
                <a href="article.php?id=2" class="text-decoration-none">
                    <article class="news-card bg-white rounded-4 overflow-hidden shadow mb-4">
                        <img src="https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/bos.png&w=100&h=100&transparent=true" alt="Celtics" class="news-card-img">
                        <div class="p-4">
                            <h2 class="fw-bold">Celtics Eye Blockbuster Trade for All-Star Guard</h2>
                            <p class="text-muted">By Adrian Wojnarowski • Nov 26, 2025</p>
                        </div>
                    </article>
                </a>

                <!-- Artikel 3 -->
                <a href="article.php?id=3" class="text-decoration-none">
                    <article class="news-card bg-white rounded-4 overflow-hidden shadow mb-4">
                        <img src="https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/nyk.png&w=100&h=100&transparent=true" alt="Knicks" class="news-card-img">
                        <div class="p-4">
                            <h2 class="fw-bold">Jalen Brunson Out for Season with Torn ACL</h2>
                            <p class="text-muted">By ESPN Staff • Nov 26, 2025</p>
                        </div>
                    </article>
                </a>

                <!-- Artikel 4 -->
                <a href="article.php?id=4" class="text-decoration-none">
                    <article class="news-card bg-white rounded-4 overflow-hidden shadow mb-4">
                        <img src="https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/gsw.png&w=100&h=100&transparent=true" alt="Warriors" class="news-card-img">
                        <div class="p-4">
                            <h2 class="fw-bold">Stephen Curry Breaks All-Time 3PT Record</h2>
                            <p class="text-muted">By Tim Bontemps • Nov 26, 2025</p>
                        </div>
                    </article>
                </a>

                <!-- Artikel 5 -->
                <a href="article.php?id=5" class="text-decoration-none">
                    <article class="news-card bg-white rounded-4 overflow-hidden shadow mb-4">
                        <img src="https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/mil.png&w=100&h=100&transparent=true" alt="Bucks" class="news-card-img">
                        <div class="p-4">
                            <h2 class="fw-bold">Giannis Signs $295M Supermax Extension</h2>
                            <p class="text-muted">By Bobby Marks • Nov 26, 2025</p>
                        </div>
                    </article>
                </a>

            </div>
        </div>

        <!-- SIDEBAR TRENDING (juga bisa diklik) -->
        <div class="col-lg-4">
            <div class="sidebar bg-white rounded-4 p-4 shadow">
                <h3 class="fw-bold mb-4">Trending</h3>
                <a href="article.php?id=6" class="text-decoration-none text-dark d-block mb-3">
                    <div class="d-flex gap-3">
                        <img src="https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/sas.png&w=100&h=100&transparent=true" class="trending-img">
                        <div>
                            <div class="fw-bold">Wembanyama Blocks 10 Shots in One Game</div>
                            <small class="text-muted">2k views</small>
                        </div>
                    </div>
                </a>
                <hr>
                <a href="article.php?id=7" class="text-decoration-none text-dark d-block mb-3">
                    <div class="d-flex gap-3">
                        <img src="https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/mia.png&w=100&h=100&transparent=true" class="trending-img">
                        <div>
                            <div class="fw-bold">Jimmy Butler Requests Trade</div>
                            <small class="text-muted">15k views</small>
                        </div>
                    </div>
                </a>
                <!-- tambah lagi sesuka hati -->
            </div>
        </div>
    </div>
</div>

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