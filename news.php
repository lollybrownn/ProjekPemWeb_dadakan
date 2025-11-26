<?php
include "connection.php";

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

// Filter berdasarkan tab/kategori
$tab = $_GET['tab'] ?? 'latest';
$categoryMap = [
    'latest' => 'Latest',
    'breaking' => 'Breaking News',
    'teams' => 'Teams',
    'players' => 'Players',
    'analysis' => 'Analysis'
];
$selectedCategory = $categoryMap[$tab] ?? 'Latest';

// Query berita
$query = "SELECT * FROM news WHERE JSON_CONTAINS(categories, '\"$selectedCategory\"') ORDER BY created_at DESC";
$allNews = $conn->query($query);

// Ambil berita hero (berita terbaru)
$heroQuery = "SELECT * FROM news ORDER BY created_at DESC LIMIT 1";
$heroNews = $conn->query($heroQuery)->fetch_assoc();

// Ambil berita trending (berdasarkan views atau random 3 berita)
$trendingQuery = "SELECT * FROM news ORDER BY created_at DESC LIMIT 3, 5";
$trendingNews = $conn->query($trendingQuery);
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
            padding-top: 170px;
            color: #333;
        }

        .navbar-main {
            z-index: 3;
            background-image: url(asset/background-navbar1.png);
            background-position: center;
            background-repeat: no-repeat;
            background-color: transparent !important;
            background-size: cover;
        }

        #subNavbar {
            background: #f8f9fa !important;
            border-bottom: 1px solid #ddd;
            position: fixed;
            left: 0;
            right: 0;
        }

        .nav-underline-custom {
            position: relative;
            color: #555 !important;
            font-weight: 600;
            padding: 0.75rem 1rem;
            transition: color 0.3s;
        }

        .nav-underline-custom::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 4px;
            background: #c8102e;
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

        .nav-link:hover {
            color: #000 !important;
        }

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

        @media (min-width: 992px) {
            .dropdown:hover > .dropdown-menu { display: block; }
        }

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

    <!-- SUB-NAVBAR NEWS -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom" id="subNavbar" style="z-index: 1;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-4 text-dark mb-0">News</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#subnavNews">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="subnavNews">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-underline-custom <?= $tab === 'latest' ? 'active' : '' ?>" href="news.php?tab=latest">Latest</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-underline-custom <?= $tab === 'breaking' ? 'active' : '' ?>" href="news.php?tab=breaking">Breaking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-underline-custom <?= $tab === 'teams' ? 'active' : '' ?>" href="news.php?tab=teams">Teams</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-underline-custom <?= $tab === 'players' ? 'active' : '' ?>" href="news.php?tab=players">Players</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-underline-custom <?= $tab === 'analysis' ? 'active' : '' ?>" href="news.php?tab=analysis">Analysis</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ISI HALAMAN NEWS -->
    <div class="container my-5">

        <!-- HERO ARTICLE (Berita Terbaru) -->
        <?php if ($heroNews): 
            $heroCats = json_decode($heroNews['categories'], true);
        ?>
        <a href="article.php?id=<?= $heroNews['id'] ?>" class="text-decoration-none text-dark">
            <div class="news-hero bg-white shadow mb-5">
                <img src="<?= $heroNews['thumbnail'] ?>" alt="<?= htmlspecialchars($heroNews['title']) ?>" class="news-hero-img w-100">
                <div class="p-5">
                    <?php if(in_array('Breaking News', $heroCats)): ?>
                        <span class="badge bg-danger fs-6 mb-3">BREAKING</span>
                    <?php else: ?>
                        <span class="badge bg-primary fs-6 mb-3"><?= $heroCats[0] ?></span>
                    <?php endif; ?>
                    <h1 class="fw-bold display-5"><?= htmlspecialchars($heroNews['title']) ?></h1>
                    <p class="text-muted fs-5">By <?= htmlspecialchars($heroNews['author']) ?> • <?= date('F j, Y', strtotime($heroNews['created_at'])) ?></p>
                </div>
            </div>
        </a>
        <?php endif; ?>

        <div class="row g-4">
            <!-- KOLOM UTAMA - Berita Lainnya -->
            <div class="col-lg-8">
                <?php 
                $count = 0;
                while($news = $allNews->fetch_assoc()): 
                    // Skip hero news
                    if($heroNews && $news['id'] == $heroNews['id']) continue;
                    $count++;
                    if($count > 10) break; // Batasi 10 berita
                ?>
                <a href="article.php?id=<?= $news['id'] ?>" class="text-decoration-none text-dark">
                    <div class="news-card bg-white shadow mb-4">
                        <img src="<?= $news['thumbnail'] ?>" class="news-card-img w-100" alt="<?= htmlspecialchars($news['title']) ?>">
                        <div class="p-4">
                            <h3 class="fw-bold"><?= htmlspecialchars($news['title']) ?></h3>
                            <p class="text-muted">By <?= htmlspecialchars($news['author']) ?> • <?= date('M j, Y', strtotime($news['created_at'])) ?></p>
                        </div>
                    </div>
                </a>
                <?php endwhile; ?>

                <?php if($count == 0): ?>
                    <div class="alert alert-info">Belum ada berita untuk kategori ini.</div>
                <?php endif; ?>
            </div>

            <!-- SIDEBAR TRENDING -->
            <div class="col-lg-4">
                <div class="bg-white rounded-4 shadow p-4 sticky-top" style="top:120px;">
                    <h4 class="fw-bold mb-4">Trending Now</h4>
                    <?php 
                    $trendCount = 0;
                    while($trend = $trendingNews->fetch_assoc()): 
                        $trendCount++;
                    ?>
                    <a href="article.php?id=<?= $trend['id'] ?>" class="text-decoration-none text-dark d-block mb-4">
                        <div class="d-flex gap-3">
                            <img src="<?= $trend['thumbnail'] ?>" class="rounded" style="width:70px;height:70px;object-fit:cover;" alt="">
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars($trend['title']) ?></div>
                                <small class="text-muted"><?= date('M j', strtotime($trend['created_at'])) ?></small>
                            </div>
                        </div>
                    </a>
                    <?php if($trendCount < $trendingNews->num_rows): ?>
                        <hr>
                    <?php endif; ?>
                    <?php endwhile; ?>
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