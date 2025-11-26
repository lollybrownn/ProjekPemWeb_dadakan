<?php
include "connection.php";
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// === DATA ARTIKEL (bisa diganti ke database nanti) ===
$articles = [
    1 => [
        'title'   => 'Lakers Secure No. 1 Seed with Dramatic OT Win vs Nuggets',
        'author'  => 'Shams Charania',
        'date'    => 'November 26, 2025',
        'image'   => 'https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/lal.png&w=1200&h=675&transparent=true',
        'content' => '<p><strong>LOS ANGELES</strong> — LeBron James mencatatkan triple-double dan Anthony Davis menyumbang 32 poin saat Lakers mengalahkan Denver Nuggets 112-108 di overtime untuk mengunci unggulan No. 1 Wilayah Barat.</p>
                      <p>James mencatatkan 28 poin, 12 rebound, dan 11 assist dalam pertandingan penutup musim reguler yang dramatis ini. Ini menjadi kali kelima dalam kariernya Lakers finis sebagai unggulan teratas di Barat.</p>'
    ],
    2 => ['title' => 'Celtics Siap Lakukan Mega Trade untuk All-Star Guard', 'author' => 'Adrian Wojnarowski', 'date' => 'November 26, 2025', 'image' => 'https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/bos.png&w=1200&h=675&transparent=true', 'content' => '<p>Boston Celtics dilaporkan sangat agresif menjelang trade deadline. Sumber mengatakan Kristaps Porziņģis dan beberapa draft pick bisa masuk paket untuk mendatangkan guard bintang...</p>'],
    3 => ['title' => 'Jalen Brunson Alami Cedera ACL, Out Sampai Akhir Musim', 'author' => 'ESPN Staff', 'date' => 'November 26, 2025', 'image' => 'https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/nyk.png&w=1200&h=675&transparent=true', 'content' => '<p>NEW YORK — Knicks mengumumkan Jalen Brunson mengalami robekan ACL pada lutut kirinya dan akan absen sepanjang sisa musim 2025-26...</p>'],
    4 => ['title' => 'Stephen Curry Pecahkan Rekor 3-Pointer Sepanjang Masa', 'author' => 'Tim Bontemps', 'date' => 'November 26, 2025', 'image' => 'https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/gsw.png&w=1200&h=675&transparent=true', 'content' => '<p>Di depan publik Chase Center, Curry mencetak triple ke-2.974 di kariernya, melewati rekor Ray Allen yang sudah bertahan 13 tahun...</p>'],
    5 => ['title' => 'Giannis Teken Ek 5 Tahun/$295 Juta Supermax dengan Bucks', 'author' => 'Bobby Marks', 'date' => 'November 26, 2025', 'image' => 'https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/mil.png&w=1200&h=675&transparent=true', 'content' => '<p>MILWAUKEE — Giannis Antetokounmpo resmi menandatangani perpanjangan kontrak terbesar dalam sejarah NBA...</p>'],
    6 => ['title' => 'Wembanyama Lakukan 10 Blok dalam Satu Laga!', 'author' => 'HoopWave Staff', 'date' => 'November 26, 2025', 'image' => 'https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/sas.png&w=1200&h=675&transparent=true', 'content' => '<p>Victor Wembanyama mencatatkan 10 blok malam ini — rekor tertinggi dalam satu pertandingan musim ini...</p>'],
    7 => ['title' => 'Jimmy Butler Minta Trade ke Tim Kontender', 'author' => 'Shams Charania', 'date' => 'November 26, 2025', 'image' => 'https://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/mia.png&w=1200&h=675&transparent=true', 'content' => '<p>Jimmy Butler telah menginformasikan manajemen Heat bahwa ia ingin dipindahkan sebelum trade deadline...</p>'],
];

$article = $articles[$id] ?? $articles[1];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?> - HoopWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f9f9f9; padding-top:170px; font-family:'Helvetica Neue',Arial,sans-serif; color:#333; }
        .navbar-main { z-index:1050 !important; background-image:url(asset/background-navbar.png); background-size:cover; background-position:center; background-repeat:no-repeat; }
        #subNavbar { z-index:1040 !important; background:#fff !important; border-bottom:1px solid #e0e0e0; position:fixed; left:0; right:0; }
        .sub-nav-item { color:#666; font-weight:500; padding:0.75rem 1rem; border-bottom:2px solid transparent; text-decoration:none; transition:all .3s; }
        .sub-nav-item:hover, .sub-nav-item.active { color:#000; border-bottom-color:#c8102e; background:#f8f9fa; }
        .article-hero-img { width:100%; height:500px; object-fit:cover; border-radius:16px; }
        .article-content { font-size:1.15rem; line-height:1.8; }
        .navbar-main {
            background-image: url(asset/background-navbar.png);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: transparent !important;
        }
        
        .teams-dropdown {
            width: 360px !important;
            /* khusus Teams saja */
            max-height: 80vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.5rem 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        /* Dropdown lain (Games, dll) tetap normal */
        .dropdown-menu:not(.teams-dropdown) {
            width: auto;
            max-height: none;
            overflow: visible;
        }

        /* Logo + nama tim rapi */
        .team-item {
            display: flex !important;
            align-items: center;
            gap: 12px;
            padding: 0.45rem 1rem;
            transition: all 0.2s;
        }

        .team-item img {
            width: 26px;
            height: 26px;
            flex-shrink: 0;
        }

        .team-item:hover {
            background-color: #f8f9fa;
            color: #0d6efd !important;
            border-radius: 6px;
        }

        .dropdown-header {
            padding-left: 1.5rem;
            font-weight: 700;
            font-size: 0.95rem;
            color: #1a1a1a;
        }

        /* Hover buka dropdown di desktop (lebih smooth) */
        @media (min-width: 992px) {
            .dropdown:hover>.dropdown-menu {
                display: block;
            }
        }
        @media (max-width:768px) { .article-hero-img { height:300px; } }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<!-- SUB NAVBAR SAMA PERSIS DENGAN news.php -->
<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom shadow-sm" id="subNavbar">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4 text-dark mb-0">News</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#subnavNews">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="subnavNews">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link sub-nav-item active" href="news.php">Latest</a></li>
                <li class="nav-item"><a class="nav-link sub-nav-item" href="news.php?tab=breaking">Breaking News</a></li>
                <li class="nav-item"><a class="nav-link sub-nav-item" href="news.php?tab=teams">Teams</a></li>
                <li class="nav-item"><a class="nav-link sub-nav-item" href="news.php?tab=players">Players</a></li>
                <li class="nav-item"><a class="nav-link sub-nav-item" href="news.php?tab=analysis">Analysis</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <article class="bg-white rounded-4 shadow-lg overflow-hidden">
        <img src="<?= $article['image'] ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="article-hero-img">

        <div class="p-4 p-md-5">
            <h1 class="display-4 fw-bold mb-4"><?= htmlspecialchars($article['title']) ?></h1>
            <div class="d-flex align-items-center text-muted mb-4 fs-5">
                <strong><?= $article['author'] ?></strong>
                <span class="mx-2">•</span>
                <span><?= $article['date'] ?> • 5 min read</span>
            </div>
            <hr class="my-5">
            <div class="article-content">
                <?= $article['content'] ?>
                <p class="mt-5 text-muted fst-italic">Artikel ini akan terus diperbarui.</p>
            </div>
            <div class="mt-5">
                <a href="news.php" class="btn btn-outline-dark btn-lg px-5">Back to News</a>
            </div>
        </div>
    </article>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function adjustSubNavbar() {
        const mainNav = document.querySelector('.navbar-main');
        const subNav = document.getElementById('subNavbar');
        if (mainNav && subNav) {
            subNav.style.top = mainNav.offsetHeight + 'px';
            document.body.style.paddingTop = (mainNav.offsetHeight + subNav.offsetHeight + 30) + 'px';
        }
    }
    window.addEventListener('load', adjustSubNavbar);
    window.addEventListener('resize', adjustSubNavbar);
</script>
</body>
</html>