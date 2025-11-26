<?php
include "connection.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
$nama = htmlspecialchars($_SESSION['nama']);

// Ambil video highlight terbaru yang punya youtube_url
$video = $conn->query("
    SELECT youtube_url, home_team, away_team, game_date 
    FROM games 
    WHERE youtube_url != '' AND youtube_url IS NOT NULL 
    ORDER BY game_date DESC, id DESC 
    LIMIT 1
")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HoopWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { padding-top: 100px; background: #ffffff; }
        .navbar { position: fixed; top: 0; left: 0; right: 0; z-index: 1030; }

        .welcome-section { padding: 3rem 0; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); }
        .highlight-video { 
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            aspect-ratio: 16 / 9;
        }
        .dashboard-card { background: #fff; border-radius: 20px; box-shadow: 0 12px 35px rgba(0,0,0,0.08); transition: all 0.3s ease; height: 100%; padding: 2.5rem; }
        .dashboard-card:hover { transform: translateY(-12px); box-shadow: 0 25px 50px rgba(0,0,0,0.15); }
        .card-icon { font-size: 4rem; background: linear-gradient(135deg, #c8102e, #dc3545); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .btn-hoop { background: #c8102e; border: none; border-radius: 50px; padding: 1rem 3rem; font-weight: 600; }
        .btn-hoop:hover { background: #a00d24; transform: translateY(-3px); }
        .about-section { padding: 5rem 0; background: #f8f9fa; }
        footer { background: #1e1e1e; color: #ccc; padding: 4rem 0 2rem; margin-top: 6rem; }
        footer a { color: #ddd; text-decoration: none; }
        footer a:hover { color: #c8102e; }
        .footer-title { color: white; font-weight: 700; }
        body { background:#f9f9f9; padding-top:170px; font-family:'Helvetica Neue',Arial,sans-serif; }
        .navbar-main { background-image:url(asset/background-navbar1.png); background-position:center; background-repeat: no-repeat;}
        #subNavbar { background:#fff !important; border-bottom:1px solid #ddd; }
        .game-wrapper {
            background:#fff;
            border-radius:16px;
            box-shadow:0 8px 30px rgba(0,0,0,0.1);
            overflow:hidden;
            margin-bottom:3rem;
        }
        .team-logo { width:72px; height:72px; }
        .score-big { font-size:3.5rem; font-weight:900; line-height:1; }
        .final-text { font-size:1.1rem; font-weight:700; color:#000; }
        .leader-img { width:60px; height:60px; border-radius:50%; object-fit:cover; border:3px solid #eee; }
        .btn-outline-dark { border-radius:50px; }
        .navbar-main {
            background-image: url(asset/background-navbar1.png);
            background-position: center;
            background-repeat: no-repeat;
            background-color: transparent !important;
            background-size: cover;
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
    </style>
</head>
<body>

<?php include "navbar.php" ?>

<!-- SELAMAT DATANG + VIDEO HIGHLIGHT (PAKAI IFRAME LANGSUNG) -->
<div class="welcome-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold text-dark mb-3">Selamat Datang!</h1>
        <p class="lead text-muted mb-5">Portal NBA terlengkap untuk penggemar basket Indonesia</p>

        <!-- VIDEO HIGHLIGHT DENGAN IFRAME -->
        <?php if ($video && !empty($video['youtube_url'])): ?>
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="highlight-video">
                        <iframe 
                            src="<?= htmlspecialchars($video['youtube_url']) ?>" 
                            title="Highlight NBA Terbaru" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen
                            class="w-100 h-100">
                        </iframe>
                    </div>
                    <p class="mt-3 text-muted fw-bold">
                        <?= htmlspecialchars($video['away_team']) ?> @ <?= htmlspecialchars($video['home_team']) ?>
                        <span class="text-secondary">— <?= date('d M Y', strtotime($video['game_date'])) ?></span>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info d-inline-block">
                Belum ada video highlight saat ini.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- MENU UTAMA (HANYA 2) -->
<div class="container py-5">
    <div class="row g-5 justify-content-center">
        <div class="col-lg-5">
            <div class="dashboard-card text-center">
                <div class="card-icon mb-4"><i class="fas fa-basketball-ball"></i></div>
                <h3 class="fw-bold mb-3">Jadwal & Skor</h3>
                <p class="text-muted mb-4">Live score, highlight video, box score, dan jadwal pertandingan NBA lengkap</p>
                <a href="home_games.php" class="btn btn-hoop text-white">Lihat Jadwal</a>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="dashboard-card text-center">
                <div class="card-icon mb-4"><i class="fas fa-newspaper"></i></div>
                <h3 class="fw-bold mb-3">Berita NBA</h3>
                <p class="text-muted mb-4">Update harian: trade, injury, rumor, analisis, dan breaking news</p>
                <a href="news.php" class="btn btn-hoop text-white">Baca Berita</a>
            </div>
        </div>
    </div>
</div>

<!-- TENTANG APLIKASI -->
<div class="about-section text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-4">Tentang HoopWave</h2>
                <p class="lead text-muted">
                    HoopWave adalah portal NBA modern yang dirancang khusus untuk penggemar basket di Indonesia. 
                    Kami menyajikan <strong>live score real-time</strong>, <strong>highlight video resmi</strong>, 
                    <strong>berita terupdate</strong>, dan <strong>statistik lengkap</strong> dalam satu tempat yang cepat, 
                    ringan, dan mudah digunakan — baik di HP maupun desktop.
                </p>
                <p class="text-muted">Dibuat dengan cinta oleh fans, untuk fans.</p>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="text-white">
    <div class="container">
        <div class="row g-5 text-start">
            <div class="col-lg-6">
                <h3 class="footer-title">HoopWave</h3>
                <p class="small">Portal NBA terlengkap untuk penggemar basket Indonesia.<br>
                Live score • Highlight • Berita • Statistik — Semua dalam satu tempat.</p>
            </div>
            <div class="col-lg-3">
                <h5 class="footer-title">Pintasan</h5>
                <ul class="list-unstyled small">
                    <li><a href="home_games.php">Jadwal & Skor</a></li>
                    <li><a href="news.php">Berita NBA</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
            <div class="col-lg-3 text-end">
                <p class="mb-0">© 2025 HoopWave<br>
                <small class="text-muted">Dibuat dengan cinta untuk NBA Fans Indonesia</small></p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>